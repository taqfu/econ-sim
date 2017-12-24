<?php
header("Refresh:2");

use App\Avatar;
use App\Activity;
use App\Game;
use App\Schedule;

?>
@extends('layouts.app')

@section('content')
<div class='container'>
<div class='row'>
<div class='col-md-2'>
    <div>Buildings</div>
    @foreach ($buildings as $building)
        <div><a href="{{route('building.show', ['id'=>$building->id])}}"> {{$building->name}}</a></div>

    @endforeach
    @if (count($jobs))
    <div>Job Openings</div>
    @endif
    @foreach ($jobs as $job)
        <div>
          <a href="{{route('job.show', ['id'=>$job->id])}}">
              {{$job->type->name}}
          </a> @
          <a href="{{route('building.show', ['id'=>$job->building->id])}}"> {{$job->building->name}}</a></div>
    @endforeach
</div>
<div class='col-md-8'>
    <h1 class='text-center'>{{$avatar->name}}</h1>
    <h3 class='text-center'>
      <a href="{{route('job.show', ['id'=>$avatar->job->id])}}">{{$avatar->job->type->name}}</a>
      @
      <a href="{{route('building.show', ['id'=>$avatar->job->building->id])}}">{{$avatar->job->building->name}}</a>
    </h3>
    <div>
      Sex:{{Avatar::SEX[$avatar->sex]}}
      Age:{{$avatar->age}}
      Schedule: {{Schedule::CAPTION[Schedule::fetch_type($avatar->id, Game::fetch_hour())]}}
    </div><div>
      Health:{{$avatar->health}}%
      Food:{{$avatar->calories / $avatar->calories_req * 100}}%
      Sleep:{{round($avatar->sleep / $avatar->sleep_req, 1) * 100}}%
      @if ($avatar->exhausted)
          <span class='text-danger'>Exhausted</span>
      @elseif ($avatar->tired)
          <span class='text-danger'>Tired</span>
      @endif

      {{ucfirst($activity->type->name )}} {{Activity::fetch_number_of_hours($activity)}}h
    </div>

    <div id='map' class='clearfix'>
        {!!$map!!}
    </div>

    <div>
      {{Game::clock()}}
      Coordinates: ({{$avatar->x}}, {{$avatar->y}})
    </div>
</div>
<div class='col-md-2'>
    <div>
        <a href="{{route('schedule.index')}}">Schedule</a>
    </div>
</div>
</div>
</div>
@endsection
