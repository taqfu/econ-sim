<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    public function item_type(){
        return $this->hasOne('App\ItemType', 'id', 'item_type_id');
    }
    public static function fetch_num_of_days_until_shipment(){
          $days_until_shipment = [1, 0, 5, 4, 3, 2];
          $ig_day = date("G");
          return $days_until_shipment[$ig_day%6];
    }

    public static function create_new_shipment($item_type_id, $quantity, $overseer_avatar_id, $week){
        $shipment = new Shipment;
        $shipment->week = $week;
        $shipment->overseer_avatar_id = $overseer_avatar_id;
        $shipment->item_type_id = $item_type_id;
        $shipment->quantity = $quantity;
        $shipment->save();
    }
}
