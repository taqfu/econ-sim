<?php
    use App\Game;
 ?>
@extends('layouts.app')

@section('content')
<div class='container'>
<div class='row'>
  <h2 class='text-center'>{{$job->type->name}}</h2>
  <div>
      Location:
      <a href="{{route('building.show', ['id'=>$job->building_id])}}">
          {{$job->building->name}}
      </a>
  <div>
      Description: {{$job->type->description}}
  </div>
  <div>Filled By:
      @if ($job->employee_avatar_id==null)
          No One

      @else
          <a href="{{route('a.show', ['id'=>$job->employee_avatar_id])}}">
              {{$job->employee->name}}
          </a>
      @endif
  <div>
      Wage (per week):
      @if ($job->gold_wage>0)
          {{$job->gold_wage}} gold
      @endif
      @if ($job->silver_wage>0)
          {{$job->silver_wage}} silver
      @endif
  </div>

    @if ($job->employee_avatar_id==null && count($avatars)>0)
        <form method="POST" action="{{route('job.apply', ['id'=>$job->id])}}" class=''>
            {{csrf_field()}}
            Apply Using The Following Avatar:
            <select class='form-control' name='avatarID'>
            @foreach ($avatars as $avatar)
                <option value='{{$avatar->id}}'>{{$avatar->name}}
                    @if ($avatar->job_id==null)
                        (unemployed)
                    @else
                        - {{$avatar->job->type->name}} @ {{$avatar->job->building->name}}
                    @endif
                </option>
            @endforeach
          </select>
            <input type='submit' class='btn btn-primary btn-block' value='Apply'/>
        </form>
    @elseif ($job->employee_avatar_id==null && count($avatars)==0)
        Create an avatar to apply to this job position.
    @endif
</div>
</div>
@endsection
