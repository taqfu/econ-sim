<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public function type(){
        return $this->hasOne('App\JobType', 'id', 'job_type_id');
    }
    public function building(){
        return $this->hasOne('App\Building', 'id', 'building_id');
    }
    public function employee(){
        return $this->hasOne('App\Avatar', 'id', 'employee_avatar_id');
    }
    public static function fire_employee ($id){
        $job = Job::find($id);
        $avatar = Avatar::find($job->employee_avatar_id);
        $avatar->job_id = null;
        $avatar->save();
        $job->fired_at = date("Y-m-d H:i:s");
        $job->save();
    }
}
