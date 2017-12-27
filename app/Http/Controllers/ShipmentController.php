<?php

namespace App\Http\Controllers;
use Auth;
use App\Avatar;
use App\JobType;
use App\ItemType;
use App\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
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
        $item_type_ids = [1, 6, 7];
        $days_until_shipment = Shipment::fetch_num_of_days_until_shipment();
        $are_they_an_overseer = false;
        $avatars = Avatar::where('user_id', Auth::user()->id)->get();
        foreach ($avatars as $avatar){
            if ($avatar->job->type->id==JobType::OVERSEER){
                $are_they_an_overseer = true;
            }
        }
        $shipment_item_types
          = ItemType::whereNotNull('shipment_unload_to_room_id')->get();
        $item_types = ItemType::
          orWhere('shipment_per_ship', '>', 0)->whereNull('shipment_unload_to_room_id')

          ->orWhere('reserve', '>', 0)->whereNull('shipment_unload_to_room_id')
          ->orWhere('shipment_per_person', '>', 0)->whereNull('shipment_unload_to_room_id')->get();
        $population = Avatar::fetch_population();
        return view('Shipment.create', [
            "are_they_an_overseer"=>$are_they_an_overseer,
            "days_until_next_shipment"=>$days_until_shipment,
            "item_types"=>$item_types,
            "population"=>$population,
            "shipment_item_types"=>$shipment_item_types

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
