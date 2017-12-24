@extends('layouts.app')

@section('content')

<form method='POST' action="{{route('RoomType.store')}}" class='container'>
    {{csrf_field()}}
    <div>Name:<input type='text' name='RoomTypeName'  class='form-control'/></div>
    <div>
        @foreach ($building_types as $building_type)
            {{$building_type->name}}<input type='radio' name='BuildingTypeID' value='{{$building_type->id}}' />
        @endforeach
    </div>
    <div>Max. Storage (m3):<input type='text' name='maxStorage' /></div>
    <div>Can the owner sleep here? <input type='checkbox' name='sleep' value='true' /></div>
    <div>Is this a public area? <input type='checkbox' name='public' value='true' /> </div>
    <div>Is this area enclosed? <input type='checkbox' name='walled' value='true' checked /> </div>


    <input type='submit' value='Create'/>
</form>

    @foreach($room_types as $room_type)
        <form method="POST" action="{{route('RoomType.destroy', ['id'=>$room_type->id])}}">
        {{method_field('delete')}}
        {{csrf_field()}}
        <div><input type='submit' class='btn btn-danger' value='x' />{{$room_type->name}}</div>
      </form>
    @endforeach
@endsection
