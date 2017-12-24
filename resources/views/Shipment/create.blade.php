@extends('layouts.app')

@section('content')
    <table class='table'>
    <tr><td>Item</td><td>Kilograms</td><td>Cubic Meters</td></tr>
    @foreach ($item_types as $item_type)
    <tr>
        <td>
            <input type='number' name='{{$item_type->id}}' min='0' value='{{$item_type->shipment_per_person*$population}}' class='shipment' disabled/> {{$item_type->unit_of_measurement}}s of {{strtolower($item_type->name)}}
        </td><td>
            <input type='text' id='kilograms{{$item_type->id}}'  class='shipment' value='{{$item_type->shipment_per_person*$population*$item_type->kilograms}}' disabled/>
        </td><td>
            <input type='text' id='cubicMeters{{$item_type->id}}'  class='shipment' value='{{$item_type->shipment_per_person*$population*$item_type->cubic_meters}}' disabled/>
        </td>
    </tr>
    @endforeach
  </table>

  <h1 class='text-center'>{{$days_until_next_shipment}} days until the next shipment</h1>
@endsection
