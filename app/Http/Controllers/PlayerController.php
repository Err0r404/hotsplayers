<?php

namespace App\Http\Controllers;

use App\Hero;
use App\Player;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller{
    
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){
        $players = DB::table('players')->orderBy('battletag')->paginate(50);

        return view('players.index', ['players' => $players]);
    }
    
    /**
     * Return Players with the best win rates
     */
    public function getBestPlayers(){
        // TODO : convert this request to Eloquent ?
        //
        // SELECT  `battletag`
        //     , COUNT(1) AS total_games
        //     , SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win
        //     , COUNT(1)-SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_lose
        //     , ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win
        // FROM    `participations`, `players`
        // WHERE 	`participations`.`player_id` = `players`.`id`
        // GROUP BY `player_id`
        // HAVING 	total_games > 150
        // #ORDER BY total_win DESC
        // #ORDER BY percent_win  DESC
        // ORDER BY total_win DESC, percent_win  DESC
        // #ORDER BY percent_win desc, total_win  DESC
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
        // Get the Player
        $player = DB::table('participations')
            ->join('players', 'participations.player_id', '=', 'players.id')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->select(
                'players.id',
                'battletag',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win'),
                DB::raw('SUM(games.length) AS total_length')
            )
            ->where('player_id', '=', $id)
            ->groupBy('player_id')
            ->get();
        
        // Convert seconds played to a readable format
        $player[0]->total_length = $this->secondsToHumanReadableString($player[0]->total_length);

        // Get stats for all his Heroes played
        $heroes = DB::table('participations')
            ->join('heroes', 'participations.hero_id', '=', 'heroes.id')
            ->select(
                'heroes.id',
                'heroes.name',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('player_id', '=', $id)
            ->groupBy('hero_id')
            ->orderBy('total_games', 'desc')
            ->get();
        
        // Get stats for his Maps played
        $maps = DB::table('participations')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->join('maps', 'games.map_id', '=', 'maps.id')
            ->select(
                'maps.id',
                'maps.name',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('player_id', '=', $id)
            ->groupBy('games.map_id')
            ->orderBy('total_games', 'desc')
            ->get();

        return view('players.show', ['player' => $player[0], 'heroes' => $heroes, 'maps' => $maps]);
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