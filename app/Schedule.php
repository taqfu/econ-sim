<?php

namespace App;
use Auth;
use App\Schedule;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public static function fetch_type($avatar_id, $hour){
        
        $schedule = Schedule::where('avatar_id', $avatar_id)->where('hour', $hour)->first();
        return $schedule->type;
    }
}
