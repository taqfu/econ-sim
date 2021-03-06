<?php

namespace App\Http\Controllers;
use Auth;
use App\ItemType;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
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
        $item_types = ItemType::get();
        return view('ItemType.create', [
          "item_types"=>$item_types,
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
        $item_type = new ItemType;
        $item_type->name = $request->ItemTypeName;
        $item_type->category = $request->category;
        $item_type->unit_of_measurement = $request->unitOfMeasurement;
        $item_type->cubic_meters = $request->cubicMeters;
        $item_type->kilograms = $request->kilograms;
        $item_type->inventory_stackable = !$request->inventoryStackable=="false";
        $item_type->save();
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
