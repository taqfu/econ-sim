@extends('layouts.app')

@section('content')
<?php
use App\Map;

for ($y=0; $y<=Map::MAX_Y; $y++){
      echo "<div class='game-row'>";
    for ($x=0; $x<=Map::MAX_X; $x++){
        foreach($db_maps as $db_map){
            if ($db_map->x == $x && $db_map->y == $y){
                $map_type = $db_map->type;
            }
        }


        echo "<div class='tile " . MAP::TILE_TYPES[$map_type] . "'
          title='(" . $x . ", " . $y . ") ";

          echo "'>";



        echo "</div>";
        //echo "<div class='tile " . $tyle_types[$map[$x][$y]] . "'>" . $map[$x][$y] . "</div>";
    }
    echo "</div>";
}
?>
@endsection
