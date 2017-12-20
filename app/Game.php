<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    const NUM_OF_METERS_PER_RL_SECOND = 16;
    public static function run(){
        $avatars = Avatar::get();
        foreach ($avatars as $avatar){
            $meter=0;
            $interval_start = microtime(true);
            $searching_for_next_move=true;
            $player_pos = ["x"=>$avatar->x, "y"=>$avatar->y];
            $new_player_pos=null;
            $minute = date("i");
            $second = date("s");
            $ig_hour = floor(($minute + (round(($second+1)/60, 2)))/2.5);
            $schedule_type = Schedule::fetch_type($avatar->id, $ig_hour);
            if ($schedule_type==0){
              
            }
            $sleeping = false; //($minute>57 || $minute<17 || ($second>29 && ($minute==57 || $minute==17)));
            if ($sleeping){
                echo "Character is sleeping.\n";
            } else if (!$sleeping){ //wandering
                    $poss_map_pos = Map :: where("x", ">=", $avatar->x - 1 )->where("x", "<=", $avatar->x + 1)
                      ->where("y", ">=", $avatar->y - 1 )->where("y", "<=", $avatar->y + 1)->get();
                    while ($meter < Game::NUM_OF_METERS_PER_RL_SECOND){
                        $move_x = rand (-1, 1);
                        $move_y = rand (-1, 1);
                        $new_player_pos = ["x"=>$player_pos["x"]+$move_x, "y"=>$player_pos["y"] + $move_y];
                        $map = Map::where("x", $new_player_pos["x"])->where("y", $new_player_pos["y"])->first();
                        if ($new_player_pos["x"]>=0 && $new_player_pos["x"]< Map :: MAX_X
                        && $new_player_pos["y"]>=0 && $new_player_pos["y"]< Map :: MAX_Y
                        && sizeof($map)>0 && $map->type!=1){
                            echo $meter . " -  (" . $new_player_pos["x"] . ", " . $new_player_pos["y"] . ") - " . $map->type . "\n";
                            $meter++;
                        }
                        $player_pos = $new_player_pos;
                    }
                echo "Moving from (" . $player_pos["x"] . ", " . $player_pos["y"] . ") to (" . $new_player_pos["x"] . ", " . $new_player_pos["y"] . ")\n";
            }
            $avatar_db = Avatar::find($avatar->id);
            $avatar_db->x = $new_player_pos["x"];
            $avatar_db->y = $new_player_pos["y"];
            $avatar_db->save();
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
        $ig_hour = floor(($minute + (round(($second+1)/60, 2)))/2.5);
        $ig_minute = floor((($minute + (round(($second+1)/60, 2)))/2.5 - floor(($minute + (round(($second+1)/60, 2)))/2.5))*60);
        $ig_day = $hour;
        $ig_week = round($ig_day/6);

        if ($ig_hour < 10){
           $ig_hour = "0" . $ig_hour;
        }
        if ($ig_minute < 10){
           $ig_minute = "0" . $ig_minute;
        }
        $ig_year = floor (date("z") / 12);

        return $month_names[$ig_month] . " " . $ig_day . ", " . $ig_year . "EC  " . $ig_hour . ":" . $ig_minute  . " Week #" . $ig_week ;
    }
}
