<?php

namespace App;
use App\Avatar;
use App\Map;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
		const AVG_CAL_REQ = 2500;
		const AVG_SLEEP_REQ = 8;
		const AVG_DAYS_TO_STARVE=24;
		const SEX = ["invalid", "XY", "XX"];
		public function job(){
				return $this->hasOne('App\Job', 'id', 'job_id');

		}
		public static function are_they_in_the_building($id, $building_id){
			$avatar = Avatar::find($id);
			$building = Building::find($building_id);
			return ($building->begin_x >= $avatar->x && $building->end_x <= $avatar->x
				&& $building->begin_y >= $avatar->y && $building->end_y <= $avatar->y);
		}
		public static function are_they_outside($avatar_id){
				$avatar = Avatar::find($avatar_id);

				$map_type = Map::fetch_type($avatar->x, $avatar->y);
			 	return $map_type!=3;
		}
		public static function fetch_hours_of_sleep($activity){
				$num_of_hours_sleeping  =floor((time()-strtotime($activity->started_at))/150);
				$sleep_modifier = Avatar::fetch_sleep_modifier($activity->avatar_id);
				return $num_of_hours_sleeping*$sleep_modifier;
		}
		public static function fetch_nearest_place_to_sleep($id){
				$closest_building = null;
				$distance_to_sleeping_place = 0;
				$avatar = Avatar::find($id);
				$rooms = Room::where('owner_avatar_id', $id)->where('sleep', true)->get();
				foreach ($rooms as $room){
						$building_pos = Building::fetch_center_pos($room->building_id);
						$new_distance = sqrt(pow(($avatar->x - $building_pos["x"]), 2) + pow(($avatar->y - $building_pos["y"]), 2));
						if ($new_distance > $distance_to_sleeping_place){
								$distance_to_sleeping_place = $new_distance;
								$closest_building = $room->building_id;
						}

				}
				return $closest_building==null ? null : Building::find($closest_building);
		}
		public static function fetch_population(){
				return count(Avatar::whereNull('died_at')->get());
		}
		public static function fetch_sleep_modifier($avatar_id){
				$avatar = Avatar::find($avatar_id);
				$are_they_outside = Avatar::are_they_outside($avatar_id);
				$are_they_in_bed = !(($avatar->bed_x==null && $avatar->bed_y==null )
					|| ($avatar->bed_x != $avatar->x && $avatar->bed_y != $avatar->y));
				$sleep_modifier = $are_they_in_bed ? 1 : .75;
				if ($are_they_outside){
						$sleep_modifier=.5;
				}
				return $sleep_modifier;
		}
		public static function change_age($avatar_id, $new_age){
				$avatar = Avatar::find($avatar_id);
				$avatar->age = $new_age;
				$avatar->save();
		}
		public static function increase_sleep($activity){
				$avatar = Avatar::find($activity->avatar_id);
				$avatar->sleep = Avatar::fetch_hours_of_sleep($activity);
				$avatar->save();

		}
		public static function lose_exhaustion($avatar_id){
				$avatar = Avatar::find($avatar_id);
				$avatar->exhausted = false;
				$avatar->save();
		}
		public static function lose_tiredness($avatar_id){
			$avatar = Avatar::find($avatar_id);
			$avatar->tired = false;
			$avatar->save();
		}
		public static function make_exhausted($avatar_id){
				Avatar::lose_tiredness($avatar_id);
				$avatar = Avatar::find($avatar_id);
				$avatar->exhausted=true;
				$avatar->save();
		}
		public static function make_tired($avatar_id){
			$avatar = Avatar::find($avatar_id);
			$avatar->tired=true;
			$avatar->save();
		}
		public static function move_towards($id, $pos){
				$meter = 0;
				$avatar = Avatar::find($id);
				$player_pos = ["x"=>$avatar->x, "y"=>$avatar->y];
				$moves = null;
				while ($meter < Game::NUM_OF_METERS_PER_RL_SECOND){
						$x_offset = $pos['x'] - $player_pos['x'];
						$y_offset = $pos['y'] - $player_pos['y'];
						if ($x_offset == 0 && $y_offset == 0){
								$meter = Game::NUM_OF_METERS_PER_RL_SECOND + 1;
						}
						if ($x_offset!=0){
								$x_offset = $x_offset>0 ? 1 : -1;
								if ($y_offset!=0){
										$x_offset = rand(0,1) ? $x_offset : 0;
								}
						}
						if ($y_offset!=0){
								$y_offset = $y_offset>0 ? 1 : -1;
								if ($x_offset!=0){
										$y_offset = rand(0,1) ? $y_offset : 0;
								}
						}
						$new_pos = ["x"=>$player_pos['x'] + $x_offset,
							"y"=>$player_pos['y'] + $y_offset];
						echo "X:" . $x_offset . "/Y:" . $y_offset . ": Trying (" . $new_pos["x"] . ", " . $new_pos["y"] . ")\n";
						$map = Map::where("x", $new_pos["x"])->where("y", $new_pos["y"])->first();

						if ($player_pos!=$new_pos && $new_pos["x"]>=0 && $new_pos["x"]< Map :: MAX_X
						&& $new_pos["y"]>=0 && $new_pos["y"]< Map :: MAX_Y
						&& sizeof($map)>0 && $map->type!=1 && ($moves == null || !in_array($new_pos, $moves))){
								$meter++;
								$moves[] = $new_pos;
								$player_pos = $new_pos;
								echo "Meter #" . $meter . " : (" . $player_pos["x"] . ", " . $player_pos["y"] . ")\n";
						}
				}
				//var_dump($pos, $player_pos, $new_pos);
				echo "Ended up at (" . $player_pos["x"] . ", " . $player_pos["y"] . ")\n";
				$avatar_db = Avatar::find($avatar->id);
				$avatar_db->x = $player_pos["x"];
				$avatar_db->y = $player_pos["y"];
				$avatar_db->save();
		}


		public static function wander($avatar_id){
				$avatar = Avatar::find($avatar_id);
				$meter=0;
				$new_player_pos=null;
				$player_pos = ["x"=>$avatar->x, "y"=>$avatar->y];
				while ($meter < Game::NUM_OF_METERS_PER_RL_SECOND){
						$move_x = rand (-1, 1);
						$move_y = rand (-1, 1);
						$new_player_pos = ["x"=>$player_pos["x"]+$move_x, "y"=>$player_pos["y"] + $move_y];
						$map = Map::where("x", $new_player_pos["x"])->where("y", $new_player_pos["y"])->first();

						if ($new_player_pos["x"]>=0 && $new_player_pos["x"]< Map :: MAX_X
						&& $new_player_pos["y"]>=0 && $new_player_pos["y"]< Map :: MAX_Y
						&& sizeof($map)>0 && $map->type!=1){
								$meter++;
								$player_pos = $new_player_pos;
						}
				}
				echo "Wandering: moving from (" . $avatar->x . ", " . $avatar->y . ") to (" . $new_player_pos["x"] . ", " . $new_player_pos["y"] . ")\n";

				$avatar_db = Avatar::find($avatar->id);
				$avatar_db->x = $new_player_pos["x"];
				$avatar_db->y = $new_player_pos["y"];
				$avatar_db->save();
		}

}
