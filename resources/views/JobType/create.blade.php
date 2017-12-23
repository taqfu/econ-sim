@extends('layouts.app')

@section('content')

<form method='POST' action="{{route('JobType.store')}}" class='container'>
    {{csrf_field()}}
    <div>Name:<input type='text' name='JobTypeName'  class='form-control'/></div>
    <div>Computer Job: <input type='checkbox' name='computerJob' value='true' />
    <div>
        Associated Building Type: none <input type='radio' name='BuildingTypeID' value='null' checked/>
        @foreach ($building_types as $building_type)
            {{$building_type->name}}<input type='radio' name='BuildingTypeID' value='{{$building_type->id}}' />
        @endforeach
    </div>
    <div>
        <textarea name='description' class='form-control'></textarea>
    <input type='submit' value='Create'/>
</form>

    @foreach($job_types as $job_type)
        <form method="POST" action="{{route('JobType.destroy', ['id'=>$job_type->id])}}">
        {{method_field('delete')}}
        {{csrf_field()}}
        <div><input type='submit' class='btn btn-danger' value='x' />{{$job_type->name}}</div>
      </form>
    @endforeach
@endsection
