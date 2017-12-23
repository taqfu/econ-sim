@extends('layouts.app')

@section('content')

<form method='POST' action="{{route('ItemType.store')}}" class='container'>
    {{csrf_field()}}
    <div>Name:<input type='text' name='ItemTypeName'  class='form-control'/></div>
    <div>
        Item Category:
        <select name='category'>
          <option value='food'>Food</option>
          <option value='building materials'>Building Materials</option>
          <option value='tools'>Tools</option>
          <option value='currency'>Currency</option>
        </select>
    </div>
    <div>Unit Of Measurement: <input type='text' name='unitOfMeasurement' class='form-control' /> </div>
    <div>Cubic Meters: <input type='text' name='cubicMeters' class='form-control' /> </div>
    <div>Kilograms: <input type='text' name='kilograms' class='form-control' /> </div>
    <div>Only carry one item at a time? <input type='checkbox' name='inventoryStackable'  value='false'/> </div>
    <input type='submit' value='Create'/>
</form>

    @foreach($item_types as $item_type)
        <form method="POST" action="{{route('ItemType.destroy', ['id'=>$item_type->id])}}">
        {{method_field('delete')}}
        {{csrf_field()}}
        <div><input type='submit' class='btn btn-danger' value='x' />{{$item_type->name}}</div>
      </form>
    @endforeach
@endsection
