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
        if ($job->type->computer_job){
            $new_job = new Job;
            $new_job->gold_wage = $job->gold_wage;
            $new_job->silver_wage = $job->silver_wage;
            $new_job->building_id = $job->building_id;
            $new_job->job_type_id = $job->job_type_id;
            $new_job->starts_on_hour = $job->starts_on_hour;
            $new_job->ends_on_hour = $job->ends_on_hour;
            $new_job->save();
        }

    }
}
