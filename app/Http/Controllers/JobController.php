<?php

namespace App\Http\Controllers;
use Auth;
use App\Avatar;
use App\Job;
use Illuminate\Http\Request;

class JobController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Job::find($id);
        $avatars = Avatar::where('user_id', Auth::user()->id)->get();

        return view('Job.show', [
            "avatars"=>$avatars,
            "job"=>$job,
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

    public function apply($id, Request $request){
        if (Auth::guest()){
            echo "You need to be logged in to do this.";
        }
        $avatars = Avatar::where('user_id', Auth::user()->id)->get();
        if (count($avatars)==0){
            echo "You don't have any avatars.";
        }
        $job = Job::find($id);
        if ($job->type->computer_job){ //EVENTUALLY HAVE COMPUTER WAIT ONE IN-GAME DAY AND PICK RANDOM OR BEST APPLICATION
            $job->employee_avatar_id = $request->avatarID;
            $job->save();
            $avatar= Avatar::find($request->avatarID);
            $avatar->job_id = $job->id;
            $avatar->save();
        } else {
            //CREATE APPLICATIONS FOR EMPLOYERS TO PERUSE
        }
        return back();

    }
}
