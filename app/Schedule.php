<?php

namespace App;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public static function fetch_type($avatar_id, $hour){

        if (Auth::guest()){
            return false;
        }
        $schedule = Schedule::where('user_id', Auth::user()->id)->where('hour', $hour)->first();
        return $schedule->type;
    }
}
