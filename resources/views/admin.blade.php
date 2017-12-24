@extends('layouts.app')

@section('content')
    <div class='container'>
        <div><a href="{{route('ActivityType.create')}}">ActivityType</a></div>
        <div><a href="{{route('BuildingType.create')}}">BuildingType</a></div>
        <div><a href="{{route('ItemType.create')}}">ItemType</a></div>
        <div><a href="{{route('JobType.create')}}">JobType</a></div>
        <div><a href="{{route('RoomType.create')}}">RoomType</a></div>

    </div>
@endsection
