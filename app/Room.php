<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    const SHIP = 4;

    public function building(){
        return $this->hasOne('App\Building', 'id', 'building_id');
    }

    public static function clear_ship($room_id){
        $items = Item::where('room_id', $room_id)->get();
        foreach($items as $item){
            $item->delete();
        }
    }
    public static function update_storage($id){
        $total_cubic_meters=0;
        $items = Item::where('room_id', $id)->get();
        foreach($items as $item){
            $total_cubic_meters += $item->quantity*$item->type->cubic_meters;
        }
        $room = Room::find($id);        
        $room->current_storage = ceil($total_cubic_meters);
        $room->save();
    }
}
