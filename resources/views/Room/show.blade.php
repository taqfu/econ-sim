<?php
    use App\Game;
 ?>
@extends('layouts.app')

@section('content')
<div class='container'>
    <div class='row'>
        <h1 class='text-center'>
            {{$room->name}} @
            <a href="{{route('building.show', ['id'=>$room->building_id])}}">{{$room->building->name}}</a>
        </h1>
        <h3>Items In Storage {{$room->current_storage}}/{{$room->max_storage}}m3</h3>
        <ul>
        @forelse ($items as $item)
            <li>{{$item->quantity}} {{$item->type->unit_of_measurement}}s of
              {{$item->type->name}}
              ({{$item->quantity*$item->type->cubic_meters}}m3 /
              {{$item->quantity*$item->type->kilograms}}kg)
            </li>
        @empty
            <div>There is nothing here.</div>
        @endforelse
      </ul>
    </div>
</div>
@endsection
