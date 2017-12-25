<?php

namespace App\Http\Controllers;

use Auth;
use App\Avatar;
use App\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($avatar_id)
    {
        if (Auth::guest()){
            echo "You need to be logged in in order to do this.";
        }
        $schedules = Schedule::where("avatar_id", $avatar_id)->orderBy('hour')->get();
        $num_of_sleep_hours = count(Schedule::where("user_id", Auth::user()->id)->where('type', 0)->orderBy('hour')->get());
        return view("Schedule.index", [
          "schedules"=>$schedules,
          "num_of_sleep_hours"=>$num_of_sleep_hours,
          "avatar"=>Avatar::find(Auth::user()->id),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        for ($hour=0;$hour<24;$hour++){
            $schedule = Schedule::where("user_id", Auth::user()->id)->where('hour', $hour)->first();
            $schedule->type = $request[$hour];
            $schedule->save();
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
