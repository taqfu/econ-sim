@extends('layouts.app')

@section('content')

<form method='POST' action="{{route('ActivityType.store')}}" class='container'>
    {{csrf_field()}}
    <div>Name:<input type='text' name='ActivityTypeName'/></div>
    <div>Location Required:<input type='checkbox' name='LocationRequired' value='true' /></div>
    <div>
    None <input type='radio' name='RelatedTo' value='null' checked/>
    @foreach($activity_types as $activity_type)
        {{$activity_type->name}}<input type='radio' name='RelatedTo' value='{{$activity_type->id}}' />
    @endforeach
  </div>
    <input type='submit' value='Create'/>
</form>

    @foreach($activity_types as $activity_type)
        <form method="POST" action="{{route('ActivityType.destroy', ['id'=>$activity_type->id])}}">
        {{method_field('delete')}}
        {{csrf_field()}}
        <div><input type='submit' class='btn btn-danger' value='x' />{{$activity_type->name}}</div>
      </form>
    @endforeach
@endsection
