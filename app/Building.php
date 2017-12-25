<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    public static function avatar(){
        return $this->hasOne('App\Avatar', 'id', 'avatar_id');
    }
    public static function fetch_center_pos($id){
        $building = Building::find($id);
        $x_offset = round(($building->end_x - $building->begin_x)/2);
        $y_offset = round(($building->end_y - $building->begin_y)/2);
        $center_pos = ["x"=> $building->begin_x + $x_offset, "y"=> $building->begin_y + $y_offset];
        return $center_pos;

    }

}
