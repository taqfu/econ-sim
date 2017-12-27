<?php

namespace App\Http\Controllers;

use Auth;
use App\Activity;

use App\Avatar;
use App\Building;
use App\Job;
use App\Game;
use App\Map;

use Illuminate\Http\Request;

class AvatarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		    return view('Avatar.create', ["name"=>"Ronald Wilkerson"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::guest()){
            echo "You need to be logged in in order to do this.";
        }
    		$avatar = new Avatar;
    		$avatar->name = $request->name; // There's a real risk that users can edit this in the form and create whatever names they want
        $avatar->user_id = Auth::user()->id;
        $avatar->sleep_at = date("Y-m-d H:i:s");
    	  $avatar->sleep_req = rand(Avatar::AVG_SLEEP_REQ-ceil(Avatar::AVG_SLEEP_REQ*.25), Avatar::AVG_SLEEP_REQ+ceil(Avatar::AVG_SLEEP_REQ*.25));
    		$avatar->sleep = $avatar->sleep_req;
        $avatar->eat_at = date("Y-m-d H:i:s");
    		$avatar->calories_req = rand(Avatar::AVG_CAL_REQ-round(Avatar::AVG_CAL_REQ*.1), Avatar::AVG_CAL_REQ-round(Avatar::AVG_CAL_REQ*.1));
    		$avatar->calories = $avatar->calories_req;
    		$avatar->max_days_to_starve =  rand(Avatar::AVG_DAYS_TO_STARVE-ceil(Avatar::AVG_DAYS_TO_STARVE), Avatar::AVG_DAYS_TO_STARVE+ceil(Avatar::AVG_DAYS_TO_STARVE));
    		$avatar->save();

        for($hour=0;$hour<24;$hour++){
            $schedule = new Schedule;
            $schedule->user_id = Auth::user()->id;
            $schedule->hour = $hour;
            $wake_up_at = $avatar->sleep_req-4;
            $schedule->type= ($hour>19 || $hour<=$wake_up_at ) ? 0 : 1;
            $schedule->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $activity = Activity::fetch_current($id);
        $avatar=Avatar::find($id);
        $map = Map::fetch_player_map($avatar->x, $avatar->y);
        $jobs = Job::whereNull('employee_avatar_id')->get();
        $buildings = Building::get();
        return view("Avatar.show", [
          "id"=>$id,
          "avatar"=>$avatar,
          "activity"=>$activity,
          "buildings"=>$buildings,
          "jobs"=>$jobs,
          "map"=>$map,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
