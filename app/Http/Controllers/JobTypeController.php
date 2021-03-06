<?php

namespace App\Http\Controllers;

use Auth;

use App\BuildingType;
use App\JobType;
use Illuminate\Http\Request;

class JobTypeController extends Controller
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
      $job_types = JobType::get();
      $building_types = BuildingType::get();
      return view('JobType.create', [
        "job_types"=>$job_types,
        "building_types"=>$building_types,
      ]);
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
            return "Please leave and don't come back";
        }

        if (Auth::user()->id!=1){
            return "You are not an Administrator.";
        }
        $job_type = new JobType;
        $job_type->name = $request->JobTypeName;
        $job_type->computer_job = $request->computerJob=="true";
        $job_type->description = $request->description;
        $job_type->building_type_id = $request->BuildingTypeID;
        $job_type->save();
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
