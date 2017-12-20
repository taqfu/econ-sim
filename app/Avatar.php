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
	const MAP_SIZE=25;
	const SEX = ["invalid", "XY", "XX"];
	public static function change_age($avatar_id, $new_age){
			$avatar = Avatar::find($avatar_id);
			$avatar->age = $new_age;
			$avatar->save();
	}
	public static function make_exhausted($avatar_id){
			$avatar = Avatar::find($avatar_id);
			$avatar->exhausted=true;
			$avatar->save();
	}
	public static function lose_exhaustion($avatar_id){
			$avatar = Avatar::find($avatar_id);
			$avatar->exhausted = false;
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
					if ($new_player_pos["x"]>=0 && $new_player_pos["x"]< Map :: MAX_X
					&& $new_player_pos["y"]>=0 && $new_player_pos["y"]< Map :: MAX_Y
					&& sizeof($map)>0 && $map->type!=1){
							//echo $meter . " -  (" . $new_player_pos["x"] . ", " . $new_player_pos["y"] . ") - " . $map->type . "\n";
							$meter++;
					}
					$player_pos = $new_player_pos;
			}
			echo "Moving from (" . $player_pos["x"] . ", " . $player_pos["y"] . ") to (" . $new_player_pos["x"] . ", " . $new_player_pos["y"] . ")\n";

			$avatar_db = Avatar::find($avatar->id);
			$avatar_db->x = $new_player_pos["x"];
			$avatar_db->y = $new_player_pos["y"];
			$avatar_db->save();
	}
}
