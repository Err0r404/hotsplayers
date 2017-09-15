<?php 

namespace App\Http\Controllers;

use App\Map;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(){
      $maps = DB::table('participations')
          ->join('games', 'participations.game_id', '=', 'games.id')
          ->join('maps', 'games.map_id', '=', 'maps.id')
          ->select(
              'maps.id',
              'maps.name',
              DB::raw('COUNT(1) AS total_games')
          )
          ->groupBy('games.map_id')
          ->orderBy('maps.name')
          ->get();

      return view('maps.index', ['maps' => $maps]);

  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id){
      $map = Map::find($id);



      return view('maps.show', ['map' => $map]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    
  }
  
}

?>