@extends('layouts.app')

@section('content')

<form method='POST' action="{{route('BuildingType.store')}}" class='container'>
    {{csrf_field()}}
    <div>Name:<input type='text' name='BuildingTypeName'/></div>
    <div>Length:<input type='text' name='length'/></div>
    <div>Width:<input type='text' name='width'/></div>
    <input type='submit' value='Create'/>
</form>

    @foreach($building_types as $building_type)
        <form method="POST" action="{{route('BuildingType.destroy', ['id'=>$building_type->id])}}">
        {{method_field('delete')}}
        {{csrf_field()}}
        <div><input type='submit' class='btn btn-danger' value='x' />{{$building_type->name}}</div>
      </form>
    @endforeach
@endsection
