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


	public static function wander($avatar_id){
			$avatar = Avatar::find($avatar_id);
			$meter=0;
			$new_player_pos=null;
			$player_pos = ["x"=>$avatar->x, "y"=>$avatar->y];

			$poss_map_pos = Map :: where("x", ">=", $avatar->x - 1 )->where("x", "<=", $avatar->x + 1)
				->where("y", ">=", $avatar->y - 1 )->where("y", "<=", $avatar->y + 1)->get();
			while ($meter < Game::NUM_OF_METERS_PER_RL_SECOND){
					$move_x = rand (-1, 1);
					$move_y = rand (-1, 1);
					$new_player_pos = ["x"=>$player_pos["x"]+$move_x, "y"=>$player_pos["y"] + $move_y];
					$map = Map::where("x", $new_player_pos["x"])->where("y", $new_player_pos["y"])->first();
					/*
					var_dump($new_player_pos, $new_player_pos["x"]>=0, $new_player_pos["x"]< Map :: MAX_X,
						$new_player_pos["y"]>=0, $new_player_pos["y"]< Map :: MAX_Y,
						sizeof($map)>0);
						if ($map!=null){
								var_dump($map->type!=1);
						} else {
								echo "map type is null\n";
						}
						*/
					if ($new_player_pos["x"]>=0 && $new_player_pos["x"]< Map :: MAX_X
					&& $new_player_pos["y"]>=0 && $new_player_pos["y"]< Map :: MAX_Y
					&& sizeof($map)>0 && $map->type!=1){
							//echo $meter . " -  (" . $new_player_pos["x"] . ", " . $new_player_pos["y"] . ") - " . $map->type . "\n";
							$meter++;
							$player_pos = $new_player_pos;

					}
			}
			echo "Moving from (" . $player_pos["x"] . ", " . $player_pos["y"] . ") to (" . $new_player_pos["x"] . ", " . $new_player_pos["y"] . ")\n";

			$avatar_db = Avatar::find($avatar->id);
			$avatar_db->x = $new_player_pos["x"];
			$avatar_db->y = $new_player_pos["y"];
			$avatar_db->save();
	}
}
