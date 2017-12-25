<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function type(){
        return $this->hasOne('App\ItemType', 'id', 'item_type_id');
    }

    public static function spawn_in_room($item_type_id, $quantity, $room_id){
        $item = Item::where('item_type_id', $item_type_id)
          ->where('room_id', $room_id)->where('quantity', '>', 0)->first();
        if ($item==null){
            $item = new Item;
            $item->item_type_id = $item_type_id;
            $item->room_id = $room_id;
            $item->quantity = $quantity;
            $item->save();
        } else {
            $item->quantity += $quantity;
            $item->save();
        }
        Room::update_storage($room_id);
    }
}
