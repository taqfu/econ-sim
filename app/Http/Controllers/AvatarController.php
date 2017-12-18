<?php

namespace App\Http\Controllers;

use Auth;
use App\Avatar;
use App\Map;

use Illuminate\Http\Request;

class AvatarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return view('Avatar.create', ["name"=>"Ronald Wilkerson"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    if (Auth::guest()){
        echo "You need to be logged in in order to do this.";
    }
		$avatar = new Avatar;
		$avatar->name = $request->name; // There's a real risk that users can edit this in the form and create whatever names they want
    $avatar->user_id = Auth::user()->id;
    $avatar->sleep_at = date("Y-m-d H:i:s");
	  $avatar->sleep_req = rand(Avatar::AVG_SLEEP_REQ-ceil(Avatar::AVG_SLEEP_REQ*.25), Avatar::AVG_SLEEP_REQ+ceil(Avatar::AVG_SLEEP_REQ*.25));
		$avatar->sleep = $avatar->sleep_req;
    $avatar->eat_at = date("Y-m-d H:i:s");
		$avatar->calories_req = rand(Avatar::AVG_CAL_REQ-round(Avatar::AVG_CAL_REQ*.1), Avatar::AVG_CAL_REQ-round(Avatar::AVG_CAL_REQ*.1));
		$avatar->calories = $avatar->calories_req;
		$avatar->max_days_to_starve =  rand(Avatar::AVG_DAYS_TO_STARVE-ceil(Avatar::AVG_DAYS_TO_STARVE), Avatar::AVG_DAYS_TO_STARVE+ceil(Avatar::AVG_DAYS_TO_STARVE));
		$avatar->save();


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view("Avatar.show", ["id"=>$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function map ($id){
        define("MAP_SIZE", 25);
        $map_type = NULL;
        $avatar = Avatar::find($id);
        $map_min_x = $avatar->x - floor(MAP_SIZE/2) >= 0 ? $avatar->x - floor(MAP_SIZE/2) : 0;
        $map_max_x = $avatar->x + floor(MAP_SIZE/2) > Map::MAX_X ? Map::MAX_X : $avatar->x + floor(MAP_SIZE/2);
        $map_min_y = $avatar->y - floor(MAP_SIZE/2) >= 0 ? $avatar->y - floor(MAP_SIZE/2) : 0;
        $map_max_y = $avatar->y + floor(MAP_SIZE/2) > Map::MAX_Y ? Map::MAX_Y : $avatar->y + floor(MAP_SIZE/2);

        $db_maps = Map::where('x', ">=", $map_min_x)->where("x", "<=", $map_max_x)->where("y", ">=", $map_min_y)->where("y", "<=", $map_max_y)->orderBy("y")->orderBy("x")->get();
        $tyle_types = ["void", "water", "land", "docks"];

        for ($y=$avatar->y - floor(MAP_SIZE/2); $y<=$avatar->y + floor(MAP_SIZE/2); $y++){
              echo "<div class='row'>";
            for ($x=$avatar->x - floor(MAP_SIZE/2); $x<=$avatar->x + floor(MAP_SIZE/2); $x++){
                $map_type=0;
                foreach($db_maps as $db_map){
                    if ($db_map->x == $x && $db_map->y == $y){
                        $map_type = $db_map->type;
                    }
                }


                echo "<div class='tile " . $tyle_types[$map_type] . "'
                  title='(" . $avatar->x . ", " . $avatar->y . ") ";
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

    }
}
