<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    const MAP_SIZE=20; // INCREASING THIS WILL INCREASE MAP LOAD TIME
    CONST MAX_X = 100;
    CONST MAX_Y = 100;
    CONST TREE_PERCENT = .5;
    CONST TILE_TYPES = ["void", "water", "land", "docks", "tree"];

    public static function fetch_type($x, $y){
        $map = Map::where('x', $x)->where('y', $y)->first();
        if ($map==null){
            return null;
        }
        return $map->type;
    }
    public static function generate_map(){
        return Map::generate_forest(Map::generate_buildings(Map::generate_water_and_land(Map::generate_blank_map())));
    }
    public static function generate_blank_map(){
        $map = [];
        for ($y=0;$y<Map::MAX_Y;$y++){
            for ($x=0; $x<Map::MAX_X;$x++){
                $map[$x][$y]=0;
            }
        }
        return $map;
    }
    public static function generate_forest($map){
        $max_num_of_trees = Map::MAX_X * Map::MAX_Y * Map::TREE_PERCENT;
        $num_of_trees = 0;
        while ($num_of_trees<$max_num_of_trees){
            $x = rand (0, Map::MAX_X-1);
            $y = rand (0, Map::MAX_Y-1);
            if ($map[$x][$y]==2){
                $num_of_trees++;
                $map[$x][$y]=4;
            }
        }
        return $map;
    }
    public static function generate_water_and_land($map){
        for ($y=0; $y<Map::MAX_Y; $y++){
            for ($x=0; $x<Map::MAX_X; $x++){
                    if ($x<Map::MAX_X*.05){
                        $map[$x][$y]=1;
                    }  else {
                        $map[$x][$y]=2;
                    }
            }
        }
        return $map;
    }

    public static function generate_buildings ($map){
        $buildings["docks"]=false;
        for ($y=0; $y<Map::MAX_Y; $y++){
            for ($x=0; $x<Map::MAX_X; $x++){
                if ((1==rand(1, Map::MAX_Y/5) || $y==Map::MAX_Y-10) && $x==Map::MAX_X*.05 && $map[$x][$y]==2 && Map::is_it_clear ($map, $x, $y, 10,10) && !$buildings["docks"]){
                    $map = Map::place_building ($map, $x, $y, 10, 10, 3);
                    $buildings["docks"]=true;
                }
            }
        }
        return $map;
    }

    public static function is_it_clear($map, $start_x, $start_y, $x_iterations, $y_iterations){
        for ($y=$start_y; $y<$start_y+$y_iterations; $y++){
            for ($x=$start_x; $x<$start_x+$x_iterations; $x++){
                if ($map[$x][$y]!=2){
                  return false;
                }
            }
        }
        return true;
    }
    public static function place_building ($map, $start_x, $start_y, $x_iterations, $y_iterations, $value){
        for ($y=$start_y; $y<$start_y+$y_iterations; $y++){
            for ($x=$start_x; $x<$start_x+$x_iterations; $x++){
                $map[$x][$y]=$value;
            }
        }
        return $map;
    }
    public static function fetch_player_map($x_coord, $y_coord){
        $string="";
        $map_min_x = $x_coord - floor(Map::MAP_SIZE/2) >= 0 ? $x_coord - floor(Map::MAP_SIZE/2) : 0;
        $map_max_x = $x_coord + floor(Map::MAP_SIZE/2) > Map::MAX_X ? Map::MAX_X : $x_coord + floor(Map::MAP_SIZE/2);
        $map_min_y = $y_coord - floor(Map::MAP_SIZE/2) >= 0 ? $y_coord - floor(Map::MAP_SIZE/2) : 0;
        $map_max_y = $y_coord + floor(Map::MAP_SIZE/2) > Map::MAX_Y ? Map::MAX_Y : $y_coord + floor(Map::MAP_SIZE/2);
        $avatars = Avatar::where('x', ">=", $map_min_x)->where("x", "<=", $map_max_x)->where("y", ">=", $map_min_y)->where("y", "<=", $map_max_y)->orderBy("y")->orderBy("x")->get();
        for ($y=$y_coord - floor(Map::MAP_SIZE/2); $y<=$y_coord + floor(Map::MAP_SIZE/2); $y++){
              $string .= "<div class='game-row'>";
            for ($x=$x_coord - floor(Map::MAP_SIZE/2); $x<=$x_coord + floor(Map::MAP_SIZE/2); $x++){
                $map_type=Map::fetch_type($x, $y);
                //$map_type=null;
                if ($map_type==null){
                    $map_type = 0;
                }
                $string .=  "<div class='tile " . MAP::TILE_TYPES[$map_type] . "'
                  title='(" . $x . ", " . $y . ") ";
                  foreach ($avatars as $avatar){
                      if ($x == $avatar->x && $y == $avatar->y){
                          $string .=  $avatar->name;
                      }
                  }
                  $string .=  "'>";
                  foreach ($avatars as $avatar){
                      if ($x == $avatar->x && $y == $avatar->y && $map_type!=3){
                        $string .=  "O";
                      }
                  }
                $string .=  "</div>";
            }
            $string .=  "</div>";
        }
        return $string;
    }
}
