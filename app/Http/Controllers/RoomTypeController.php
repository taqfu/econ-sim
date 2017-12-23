<?php

namespace App\Http\Controllers;
use Auth;
use App\RoomType;
use App\BuildingType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
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
      $room_types = RoomType::get();
      $building_types = BuildingType::get();

      return view('RoomType.create', [
        "room_types"=>$room_types,
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
        $room_type = new RoomType;
        $room_type->name = $request->RoomTypeName;
        $room_type->building_type_id = $request->BuildingTypeID;
        $room_type->max_storage = $request->maxStorage;
        $room_type->public = $request->public=="true";
        $room_type->enclosed = $request->enclosed=="true";
        $room_type->sleep = $request->sleep=="true";
        $room_type->save();
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
