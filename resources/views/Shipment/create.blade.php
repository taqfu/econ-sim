@extends('layouts.app')

@section('content')
    <table class='table'>
    <tr><td>Item</td><td>Kilograms</td><td>Cubic Meters</td></tr>
    @foreach ($shipment_item_types as $shipment_item_type)
    <tr>
        <td>
            <input type='number' name='{{$shipment_item_type->id}}' min='0'
              value='{{$shipment_item_type->shipment_per_person*$population}}'
              class='shipment' disabled/>
                {{$shipment_item_type->unit_of_measurement}}s of
                {{strtolower($shipment_item_type->name)}}
        </td><td>
            <input type='text' id='kilograms{{$shipment_item_type->id}}'
              class='shipment' value='{{$shipment_item_type->shipment_per_person*$population*$shipment_item_type->kilograms}}' disabled/>
        </td><td>
            <input type='text' id='cubicMeters{{$shipment_item_type->id}}'  class='shipment' value='{{$shipment_item_type->shipment_per_person*$population*$shipment_item_type->cubic_meters}}' disabled/>
        </td>
    </tr>
    @endforeach
  </table>

  <h1 class='text-center'>{{$days_until_next_shipment}} days until the next shipment</h1>
  <div class='container'>
      <form method="{{route('shipment.store')}}" action="POST">
          {{csrf_field()}}
          @foreach ($item_types as $item_type)
              <?php
              if ($item_type->shipment_per_ship>0){
                  $shipment_type = "per ship";
                  $quantity_requested = $item_type->shipment_per_ship;
              } else if ($item_type->shipment_per_person>0){
                  $shipment_type = "per person";
                  $quantity_requested = $item_type->shipment_per_person;
              } else if ($item_type->reserve>0){
                  $shipment_type = "reserve";
                  $quantity_requested = $item_type->reserve;
              }
               ?>
              <div class='row'>


                  {{$quantity_requested}} {{$item_type->unit_of_measurement}}s of {{$item_type->name}} - {{$shipment_type}}
              </div>
          @endforeach
      </form>
  </div>
@endsection
