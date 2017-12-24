<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public function type(){
        return $this->hasOne('App\ActivityType', 'id', 'activity_type_id');
    }
    public static function fetch_number_of_hours($activity){ //active only
        return floor((time()-strtotime($activity->started_at))/150);
    }
    public static function fetch_last_sleep($avatar_id){
        return Activity::where('avatar_id', $avatar_id)
          ->where('activity_type_id', ActivityType::SLEEP)->orderBy('ended_at', "desc")->first();
    }
    public static function have_they_slept_in_the_past_24_ig_hours($activity){
        return  !($activity==null || time()-strtotime($activity->ended_at)>3600);

    }
    public static function fetch_current($avatar_id){
        return Activity::where('avatar_id', $avatar_id)->where('ended_at', null)->first();

    }
    public static function end_last($avatar_id){
        $activity = Activity::fetch_current($avatar_id);
        if ($activity!=null){
            $activity->ended_at = date("Y-m-d H:i:s");
            $activity->save();
        }
    }
    public static function head_to_bed($avatar_id){
      Activity::end_last($avatar_id);

      $activity = new Activity;
      $activity->activity_type_id = ActivityType::HEAD_TO_BED;
      $activity->avatar_id = $avatar_id;
      $activity->started_at = date("Y-m-d H:i:s");
      $activity->save();
    }
    public static function sleep($avatar_id){
      Activity::end_last($avatar_id);
      $activity = new Activity;
      $activity->activity_type_id = ActivityType::SLEEP;
      $activity->avatar_id = $avatar_id;
      $activity->started_at = date("Y-m-d H:i:s");
      $activity->save();
      $avatar = Avatar::find($avatar_id);
      $avatar->sleep = 0;
      $avatar->save();
    }
    public static function wander($avatar_id){
        Activity::end_last($avatar_id);
        $activity = new Activity;
        $activity->activity_type_id = ActivityType::WANDER;
        $activity->avatar_id = $avatar_id;
        $activity->started_at = date("Y-m-d H:i:s");
        $activity->save();
    }
}
