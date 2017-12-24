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
        $item_types = ItemType::whereIn('id', $item_type_ids)->get();
        $population = Avatar::fetch_population();
        return view('Shipment.create', [
            "item_types"=>$item_types,
            "are_they_an_overseer"=>$are_they_an_overseer,
            "population"=>$population,
            "days_until_next_shipment"=>$days_until_shipment,
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
