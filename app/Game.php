<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    CONST THE_COMPANY = "United Public Capital";
    const NUM_OF_METERS_PER_RL_SECOND = 16;
    public static function run(){
        $week = Game::fetch_week();
        $hour = Game::fetch_hour();
        $shipments = Shipment::whereNull('delivered_at')->where('week', $week)->get();
        if (Shipment::fetch_num_of_days_until_shipment()==0
          && count($shipments)>0 && $hour>=9 && $hour<=17){
            $overseer = Job::where('job_type_id', JobType::OVERSEER)->whereNull('fired_at')->whereNull('promoted_at')->first();
            Room::clear_ship(Room::SHIP);

            foreach($shipments as $shipment){
                $shipment->delivered_at = date("Y-m-d H:i:s");
                $shipment->save();
                Item::spawn_in_room($shipment->item_type_id,
                  $shipment->quantity, Room::SHIP);
                Shipment::create_new_shipment($shipment->item_type_id,
                  $shipment->item_type->shipment_per_person, $overseer->employee->id,
                  $week + 1 > 4 ? 1 : $week + 1);
            }
            $activity = Activity::fetch_current($overseer->employee_avatar_id);
            if (Schedule::fetch_type($overseer->employee_avatar_id, $hour) != Schedule::WORK){
                Job::fire_employee($overseer->id);
            } else if ($activity->type->id == ActivityType::SLEEP){
                Job::fire_employee($overseer->id);
            }
        }

        $avatars = Avatar::get();
        foreach ($avatars as $avatar){
            $age_ig = 18+floor((time()-strtotime($avatar->created_at))/(86400*12));
            if ($avatar->age<$age_ig){
                Avatar::change_age($avatar->id, $age_ig);
            } else if ($avatar->age>$age_ig){
                error_log("In-game age is lower than avatar's age for Avatar #" . $avatar->id);
            }

            $minute = date("i");
            $second = date("s");
            $ig_hour = floor(($minute + (round(($second+1)/60, 2)))/2.5);
            $schedule_type = Schedule::fetch_type($avatar->id, (int)$ig_hour);
            $activity = Activity::fetch_current($avatar->id);
            //echo "Schedule #" . $schedule_type . ": Avatar #" . $avatar->id . " is currently doing activity #" . $activity->activity_type_id . "\n";
            if ($avatar->tired && $avatar->sleep>=$avatar->sleep_req){
                echo "Avatar #" . $avatar->id . " is no longer tired.\n";
                Avatar::lose_tiredness($avatar->id);
            }
            if (!$avatar->exhausted){
                if(!Activity::have_they_slept_in_the_past_24_ig_hours(Activity::fetch_last_sleep($avatar->id))){
                    echo "Avatar #" . $avatar->id . " is exhausted now.\n";
                    Avatar::make_exhausted($avatar->id);
                } else if (!$avatar->tired){
                    if ($avatar->sleep<$avatar->sleep_req){
                        Avatar::make_tired($avatar->id);
                    }
                }

            }
            if ($avatar->exhausted){

                if ($activity==null || $activity->activity_type_id!=ActivityType::SLEEP){
                    echo "Avatar #" . $avatar->id . " fell asleep from exhaustion.\n";
                    Activity::sleep($avatar->id);
                } else if ($activity->activity_type_id==ActivityType::SLEEP && $avatar->sleep>=$avatar->sleep_req){
                    echo "Avatar #" . $avatar->id . " is no longer exhausted after sleeping " . Avatar::fetch_hours_of_sleep($activity) . " hours.\n";
                    Avatar::lose_exhaustion($avatar->id);
                }
            }

            if ($activity!=null && $activity->type->id==ActivityType::SLEEP){
                $num_of_hours_sleeping = Avatar::fetch_hours_of_sleep($activity);
                //var_dump(floor($num_of_hours_sleeping), $avatar->sleep);
                if (floor($num_of_hours_sleeping)!=$avatar->sleep){
                    Avatar::increase_sleep($activity);
                    echo "Avatar #" . $avatar->id . " has been sleeping for " . $avatar->sleep . " hours. \n";
                }
            } else if ($activity!=null &&  $activity->type->id==ActivityType::WANDER){

                Avatar::wander($avatar->id);
            }

            if (!$avatar->exhausted && $schedule_type==0){
                $nearest_building_to_sleep = Avatar::fetch_nearest_place_to_sleep($avatar->id);
                if ($nearest_building_to_sleep!=null){
                    if (Avatar::are_they_in_the_building($avatar->id, $nearest_building_to_sleep->id)){
                        echo "Going to sleep inside room\n";
                        Activity::sleep($avatar->id);
                    } else {
                        if ($activity==null || ($activity!=null && $activity->type->id != ActivityType::HEAD_TO_BED)){
                            Activity::head_to_bed($avatar->id);
                        }
                        $new_pos = Building::fetch_center_pos($nearest_building_to_sleep->id);
                        echo "Heading from (" . $avatar->x . ", " . $avatar->y
                          . ") to (" . $new_pos['x'] . ", " . $new_pos['y']
                          . ") to go to sleep\n";
                        Avatar::move_towards($avatar->id, $new_pos);
                    }

                } else {
                    if ($activity->type->id != ActivityType::SLEEP){
                        Activity::sleep($avatar->id);
                    }
                }
            } else if (!$avatar->exhausted && $schedule_type==1 ){
                if ($activity->type->id != ActivityType::WANDER){
                    echo "Wandering \n";
                    Activity::wander($avatar->id);
                }
            }


        }
    }

    public static function clock (){
        $month_names = ["Joineary", "Fabruary", "Morch", "Avril", "Muy", "Joen",
          "Jolie", "Aggie", "Saptupey", "Octupey", "Novemschma", "Dagvember"];
        $hour = date("G");
        $minute = date("i");
        $second = date("s");
        $milliseconds = date ("v");

        //var_dump ($minute, (round(($second+1)/60, 2)));
        $ig_month = date("z") % 12;
        $ig_hour = Game::fetch_hour();
        $ig_minute = floor((($minute + (round(($second+1)/60, 2)))/2.5 - floor(($minute + (round(($second+1)/60, 2)))/2.5))*60);
        $ig_day = $hour;
        $ig_week = ceil($ig_day/6);

        if ($ig_hour < 10){
           $ig_hour = "0" . $ig_hour;
        }
        if ($ig_minute < 10){
           $ig_minute = "0" . $ig_minute;
        }
        $ig_year = floor (date("z") / 12);

        return $month_names[$ig_month] . " " . $ig_day . ", " . $ig_year . "EC  " . $ig_hour . ":" . $ig_minute  . " Week #" . $ig_week ;
    }

    public static function fetch_week(){
        return ceil(date("G")/6);

    }
    public static function fetch_hour(){
        $minute = date("i");
        $second = date("s");
        return floor(($minute + (round(($second+1)/60, 2)))/2.5);
    }
}
