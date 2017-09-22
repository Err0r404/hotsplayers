<?php

namespace App\Http\Controllers;

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

        // Get stats for all his Heroes played without filtering the Games's type
        $heroes = DB::table('participations')
            ->join('heroes', 'participations.hero_id', '=', 'heroes.id')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->select(
                'heroes.id',
                'heroes.name',
                DB::raw("'All games' AS type"),
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('CONCAT_WS(":", FLOOR(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) / 60), LPAD(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) % 60, 2, 0)) AS avg_length'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('player_id', '=', $id)
            ->groupBy('hero_id')
            ->orderBy('total_games', 'desc')
            ->get();
        
        // Get stats for all his Heroes played without filtering the Games's type
        $heroesByTypes = DB::table('participations')
            ->join('heroes', 'participations.hero_id', '=', 'heroes.id')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->select(
                'heroes.id',
                'heroes.name',
                'games.type',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('CONCAT_WS(":", FLOOR(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) / 60), LPAD(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) % 60, 2, 0)) AS avg_length'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('player_id', '=', $id)
            ->groupBy('hero_id')
            ->groupBy('games.type')
            ->orderBy('total_games', 'desc')
            ->get();
    
        // Mix the 2 Heroes's collections
        foreach($heroesByTypes as $hero){
            $heroes->push($hero);
        }
        
        // Get stats for his Maps played
        $maps = DB::table('participations')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->join('maps', 'games.map_id', '=', 'maps.id')
            ->select(
                'maps.id',
                'maps.name',
                DB::raw("'All games' AS type"),
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('CONCAT_WS(":", FLOOR(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) / 60), LPAD(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) % 60, 2, 0)) AS avg_length'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('player_id', '=', $id)
            ->groupBy('games.map_id')
            ->orderBy('total_games', 'desc')
            ->get();
        
        // Get stats for his Maps played
        $mapsByTypes = DB::table('participations')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->join('maps', 'games.map_id', '=', 'maps.id')
            ->select(
                'maps.id',
                'maps.name',
                'games.type',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('CONCAT_WS(":", FLOOR(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) / 60), LPAD(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) % 60, 2, 0)) AS avg_length'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('player_id', '=', $id)
            ->groupBy('games.map_id')
            ->groupBy('games.type')
            ->orderBy('total_games', 'desc')
            ->get();
        
        // Mix the 2 Heroes's collections
        foreach($mapsByTypes as $map){
            $maps->push($map);
        }
        
        // Find teammates
        $teammates = DB::table('participations')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->join('participations as p2', 'p2.game_id', '=', 'games.id')
            ->join('players', 'p2.player_id', '=', 'players.id')
            ->select(
                'players.id',
                'players.battletag',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('CONCAT_WS(":", FLOOR(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) / 60), LPAD(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) % 60, 2, 0)) AS avg_length'),
                DB::raw('SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('participations.player_id', '=', $id)
            ->where('p2.player_id', '<>', $id)
            ->groupBy('p2.player_id')
            ->having('total_games', '>=', '5')
            ->orderBy('total_games', 'desc')
            ->orderBy('players.battletag', 'asc')
            ->get();
        
        foreach($teammates as $teammate){
            $mostPlayed = DB::table('participations')
                ->join('games', 'participations.game_id', '=', 'games.id')
                ->join('participations as p2', 'p2.game_id', '=', 'games.id')
                ->join('heroes', 'p2.hero_id', '=', 'heroes.id')
                ->select(
                    'heroes.id',
                    'heroes.name',
                    DB::raw('COUNT(1) AS total_games')
                )
                ->where('participations.player_id', '=', $id)
                ->where('p2.player_id', '=', $teammate->id)
                ->groupBy('heroes.id')
                // ->having('total_games', '>', '1')
                ->orderBy('total_games', 'desc')
                ->orderBy('heroes.name', 'asc')
                ->limit(3)
                ->get();
    
            $teammate->{'heroes'} = $mostPlayed;
       }
            
        // Get all Games's types that the Player did
        $types = DB::table('participations')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->select(
                'games.type as name',
                DB::raw('COUNT(1)')
            )
            ->where('player_id', '=', $id)
            ->groupBy('games.type')
            ->orderBy('type', 'asc')
            ->get();

        return view(
            'players.show',
            [
                'player'    => $player[0],
                'types'     => $types,
                'heroes'    => $heroes,
                'maps'      => $maps,
                'teammates' => $teammates,
            ]
        );
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