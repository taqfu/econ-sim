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
		const INVENTORY_SIZE_LIMIT = 1;
		const SEX = ["invalid", "XY", "XX"];
		public function job(){
				return $this->hasOne('App\Job', 'id', 'job_id');

		}

		public static function add_to_inventory($id, $item_id){
				$avatar = Avatar::find($id);
				$item = Item::find($item_id);
				$item_in_inventory = Item::where('item_type_id', $item->type->id)
					->where('inventory_avatar_id', $id)->where('hauling', false)
					->where('owner_avatar_id', $id)->first();
				if ($item_in_inventory==null){
						$item->room_id = null;
						$item->inventory_avatar_id = $id;
						$item->owner_avatar_id = $id;
						$item->hauling = false;
						$item->save();

				} else {
						$item_in_inventory->quantity += $item->quantity;
						$item_in_inventory->save();
						$item->delete();
				}
		}
		public static function are_they_in_the_building($id, $building_id){
			$avatar = Avatar::find($id);
			$building = Building::find($building_id);


			return ($building->begin_x <= $avatar->x && $building->end_x >= $avatar->x
				&& $building->begin_y <= $avatar->y && $building->end_y >= $avatar->y);
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
						//echo "X:" . $x_offset . "/Y:" . $y_offset . ": Trying (" . $new_pos["x"] . ", " . $new_pos["y"] . ")\n";
						$map = Map::where("x", $new_pos["x"])->where("y", $new_pos["y"])->first();

						if ($player_pos!=$new_pos && $new_pos["x"]>=0 && $new_pos["x"]< Map :: MAX_X
						&& $new_pos["y"]>=0 && $new_pos["y"]< Map :: MAX_Y
						&& sizeof($map)>0 && $map->type!=1 && ($moves == null || !in_array($new_pos, $moves))){
								$meter++;
								$moves[] = $new_pos;
								$player_pos = $new_pos;
							//	echo "Meter #" . $meter . " : (" . $player_pos["x"] . ", " . $player_pos["y"] . ")\n";
						}
				}
				//var_dump($pos, $player_pos, $new_pos);
				//echo "Ended up at (" . $player_pos["x"] . ", " . $player_pos["y"] . ")\n";
				$avatar_db = Avatar::find($avatar->id);
				$avatar_db->x = $player_pos["x"];
				$avatar_db->y = $player_pos["y"];
				$avatar_db->save();
		}

		public static function unload_ship($avatar_id){
				$avatar = Avatar::find($avatar_id);
				$items_in_inventory = Item::where('room_id', Room::STORAGE_ROOM)
					->where('inventory_avatar_id', $avatar_id)->where('hauling', true)
					->get();
				$storage_room = Room::find(Room::STORAGE_ROOM);
				foreach ($items_in_inventory as $item_in_inventory){
						if ($item_in_inventory->quantity
							* $item_in_inventory->type->cubic_meters
							<= $storage_room->max_storage - $storage_room->current_storage){

								$matching_item_in_the_storage_room
									= Item::where('room_id', Room::STORAGE_ROOM)
									->where('hauling', false)
									->where('item_type_id', $item_in_inventory->item_type_id)
									->first();



								if ($matching_item_in_the_storage_room!=null){
										$matching_item_in_the_storage_room->quantity
											+= $item_in_inventory->quantity;
										$matching_item_in_the_storage_room->save();
										$item_in_inventory->delete();

								} else {
									$item_in_inventory->inventory_avatar_id = null;
									$item_in_inventory->hauling = false;
									$item_in_inventory->save();
								}

								Room::update_storage(Room::STORAGE_ROOM);
						}
				}
				if (count($items_in_inventory)==0){
						$items_in_ship = Item::where('room_id', Room::SHIP)
							->whereNull('inventory_avatar_id' )->get();
						foreach ($items_in_ship as $item_in_ship){
								if ($item_in_ship->quantity * $item_in_ship->type->cubic_meters
									<= Avatar::INVENTORY_SIZE_LIMIT - $avatar->inventory_size
									&& $item_in_ship->quantity * $item_in_ship->type->kilograms
									<= $avatar->inventory_weight_limit - $avatar->inventory_weight){
										$item_in_ship->inventory_avatar_id = $avatar_id;
										$item_in_ship->room_id = Room::STORAGE_ROOM;
										$item_in_ship->hauling = true;
										$item_in_ship->save();
								} else {
										$free_inventory_weight = $avatar->inventory_weight_limit
											- $avatar->inventory_weight;
										$free_inventory_size = Avatar::INVENTORY_SIZE_LIMIT
											- $avatar->inventory_size;
										$quantity_for_weight = floor ($free_inventory_weight
											/ $item_in_ship->kilograms);
										$quantity_for_size = floor($free_inventory_size
											/ $item_in_ship->cubic_meters);
										$quantity_used = $quantity_for_weight > $quantity_for_size
											? $quantity_for_size : $quantity_for_weight;

										$item_in_ship->quantity = $item_in_ship->quantity
											- $quantity_used;
										$item_in_ship->save();

										$new_item_in_inventory = new Item;
										$new_item_in_inventory->quantity = $quantity_used;
										$new_item_in_inventory->item_type_id
											= $item_in_ship->item_type_id;
										$new_item_in_inventory->$room_id = Room::STORAGE_ROOM;
										$new_item_in_inventory->inventory_avatar_id = $avatar_id;
										$new_item_in_inventory->hauling = true;
										$new_item_in_inventory->save();
								}
								Room::update_storage(Room::SHIP);
								Avatar::update_inventory($avatar_id);
						}
				}

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

				$avatar_db = Avatar::find($avatar->id);
				$avatar_db->x = $new_player_pos["x"];
				$avatar_db->y = $new_player_pos["y"];
				$avatar_db->save();
		}
		public static function update_inventory($avatar_id){
				$total_kilograms = 0;
				$total_cubic_meters = 0;
				$avatar = Avatar::find($avatar_id);
				$items = Item::where('inventory_avatar_id', $avatar_id)->get();

				foreach ($items as $item){
						$total_kilograms += $item->quantity * $item->type->kilograms;
						$total_cubic_meters += $item->quantity * $item->type->cubic_meters;
				}
				if ($total_kilograms > $avatar->inventory_weight_limit){
						error_log ("Too much inventory weight for Avatar #" . $avatar->id);
				}
				if ($total_cubic_meters > $avatar->inventory_size_limit){
						error_log ("Too much inventory bulk for Avatar #" . $avatar->id);
				}
				$avatar->inventory_weight = $total_kilograms;
				$avatar->inventory_size = $total_cubic_meters;
				$avatar->save();

		}
}
