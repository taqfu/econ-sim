@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{route('home')}}">Home</a>
</div>
<form method='POST' action="{{route('a.store')}}" class='container'>
    {{csrf_field()}}
    <div class='row'>Name: <input type='text' name='name' value='{{$name}}'  disabled /> </div>
    <div class='row'>Age: 18</div>
    <div class='row'><input type='submit' value='Create'/></div>
</form>
@endsection
