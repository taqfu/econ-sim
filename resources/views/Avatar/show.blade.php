@extends('layouts.app')

@section('content')
<script>
    refresh_map();
    setInterval(function (){
      refresh_map({{$id}});
    }, 500);
</script>
<div class='container'>
<div class='row'>
<div class='col-md-2'></div>
<div id='map' class='col-md-8'></div>
<div class='col-md-2'></div>
</div>
</div>
@endsection
