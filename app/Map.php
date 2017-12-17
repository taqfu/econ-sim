<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    CONST MAX_X = 100;
    CONST MAX_Y = 100;
    public static function generate_map(){
        return Map::generate_buildings(Map::generate_water_and_land(Map::generate_blank_map()));
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
    public static function place_building ($map, $start_x, $start_y, $x_iterations, $y_iterations, $value){
        for ($y=$start_y; $y<$start_y+$y_iterations; $y++){
            for ($x=$start_x; $x<$start_x+$x_iterations; $x++){
                $map[$x][$y]=$value;
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
}
