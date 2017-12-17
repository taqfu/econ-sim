@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{route('a.create')}}">New Avatar</a>
    @foreach ($avatars as $avatar)
        <div>
        <a href="{{route('a.show', ['id'=>$avatar->id])}}">Play</a> - {{$avatar->name}} - {{$avatar->age}} years old - Health: {{$avatar->health}}% -
        Created: {{date("m/d/y", strtotime($avatar->created_at))}} <!--replace with subject refence-->


        </div>
    @endforeach
</div>

@endsection
