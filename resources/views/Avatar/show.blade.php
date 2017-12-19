@extends('layouts.app')

@section('content')
<script>
    refresh_map();
    setInterval(function (){
      refresh_map({{$id}});
      refresh_clock({{$id}});
    }, 1000);
</script>

<div id='map'></div>
@endsection
