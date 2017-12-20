<?php
    use App\Activity;
    use App\Avatar;
    use App\Game;

?>

<div id='status' class='game-row'>
  Name:{{$avatar->name}}
  Sex:{{Avatar::SEX[$avatar->sex]}}
  Age:{{$avatar->age}}
  Health:{{$avatar->health}}%
  Food:{{$avatar->calories / $avatar->calories_req * 100}}%
  Sleep:{{round($avatar->sleep / $avatar->sleep_req, 1) * 100}}%
  @if ($avatar->exhausted)
      <span class='text-danger'>Exhausted</span>
  @elseif ($avatar->tired)
      <span class='text-danger'>Tired</span>
  @endif

  {{ucfirst($activity->type->name . "ing")}} {{Activity::fetch_number_of_hours($activity)}}h

</div>
<?php
for ($y=$avatar->y - floor(Avatar::MAP_SIZE/2); $y<=$avatar->y + floor(Avatar::MAP_SIZE/2); $y++){
      echo "<div class='game-row'>";
    for ($x=$avatar->x - floor(Avatar::MAP_SIZE/2); $x<=$avatar->x + floor(Avatar::MAP_SIZE/2); $x++){
        $map_type=0;
        foreach($db_maps as $db_map){
            if ($db_map->x == $x && $db_map->y == $y){
                $map_type = $db_map->type;
            }
        }


        echo "<div class='tile " . $tile_types[$map_type] . "'
          title='(" . $x . ", " . $y . ") ";
          if ($x == $avatar->x && $y == $avatar->y){
              echo " Player here";
          }
          echo "'>";
          if ($x == $avatar->x && $y == $avatar->y && $map_type!=3){
            echo "O";
          }


        echo "</div>";
        //echo "<div class='tile " . $tyle_types[$map[$x][$y]] . "'>" . $map[$x][$y] . "</div>";
    }
    echo "</div>";
}
?>
<div class='game-row'>

  {{Game::clock()}}
  Coordinates: ({{$avatar->x}}, {{$avatar->y}})

</div>
