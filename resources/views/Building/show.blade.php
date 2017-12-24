<?php
    use App\Game;
 ?>
@extends('layouts.app')

@section('content')
<div class='container'>
<div class='row'>
<div class='col-md-2'>
      <div>Rooms</div>
      <ul>
      @foreach ($rooms as $room)
        <li>
            <a href="{{route('room.show', ['id'=>$room->id])}}">{{$room->name}}</a></li> ({{$room->current_storage}}/{{$room->max_storage}}m3)
      @endforeach
      </ul>
      <div>Jobs</div>
      @foreach ($jobs as $job)
          <a href="{{route('job.show', ['id'=>$job->id])}}">
              {{$job->type->name}}
          </a>:
          @if ($job->employee_avatar_id==null)
              <span class='text-muted'><i>
                  (unfilled)
              </i></span>
          @else
              <a href="{{route('a.show', ['id'=>$job->employee_avatar_id])}}">{{$job->employee->name}}</a>
          @endif

      @endforeach
</div>
<div class='col-md-8'>

    <h1 class='text-center'>{{$building->name}}</h1>
    @if ($building->avatar_id==null)
        <div>Owned By: {{Game::THE_COMPANY}}</div>
    @else
        <div><a href="{{route('a.show', ['id'=>$avatar->id])}}">{{$building->avatar->name}}</a></div>
    @endif
    <div id='map' class='clearfix'>
        {!!$map!!}
    </div>
    <div>
        <form method='get' action="{{route('shipment.create')}}">
            <input type='submit' class='btn btn-primary btn-block' value='Order A Delivery' />
        </form>
    </div>

</div>
<div class='col-md-2'></div>
</div>
</div>
@endsection
