@extends('layouts.app')

@section('content')
<script>
    refresh_map();
    setInterval(function (){
      refresh_map({{$id}});
    }, 500);
</script>

<div id='map'></div>
@endsection
