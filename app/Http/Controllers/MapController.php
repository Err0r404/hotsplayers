<?php

namespace App\Http\Controllers;

use App\Map;
use Illuminate\Support\Facades\DB;

class MapController extends Controller{
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){
        $maps = DB::table('participations')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->join('maps', 'games.map_id', '=', 'maps.id')
            ->select('maps.id', 'maps.name', DB::raw('COUNT(1) AS total_games'))
            ->groupBy('games.map_id')
            ->orderBy('maps.name')
            ->get();
    
        foreach ($maps as $map) {
            $map->total_games = $this->numbertoHumanReadableFormat($map->total_games);
        }
        
        return view('maps.index', ['maps' => $maps]);
        
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(){
    
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(){
    
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id){
        // Get Map's information
        $map = Map::find($id);
        
        // Get Heroes's winrates for the Map
        $heroes = DB::table('participations')
            ->join('heroes', 'participations.hero_id', '=', 'heroes.id')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->join('maps', 'games.map_id', '=', 'maps.id')
            ->select(
                'heroes.id', 'heroes.name',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->groupBy('participations.hero_id')
            ->orderBy('heroes.name')
            ->get();
        
        // Get 10 bests players with the Map
        $players = DB::table('participations')
             ->join('players', 'participations.player_id', '=', 'players.id')
             ->join('games', 'participations.game_id', '=', 'games.id')
             ->select(
                 'players.id', 'players.battletag',
                 DB::raw('COUNT(1) AS total_games'),
                 DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                 DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
             )
             ->where('games.map_id', $id)
             ->groupBy('players.id')
             ->having('total_games', '>', 10)
             ->orderBy('percent_win', 'desc')
             ->limit(10)
             ->get();
        
        return view('maps.show', ['map' => $map, 'heroes' => $heroes, 'players' => $players]);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id){
    
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function update($id){
    
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id){
    
    }
    
}

?>