<?php

namespace App\Http\Controllers;

use App\Hero;
use Illuminate\Support\Facades\DB;

class HeroController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){
        $heroes = DB::table('participations')
            ->join('heroes', 'participations.hero_id', '=', 'heroes.id')
            ->select(
                'participations.hero_id',
                'heroes.id',
                'heroes.name',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->groupBy('hero_id')
            ->orderBy('heroes.name')
            ->get();

        return view('heroes.index', ['heroes' => $heroes]);
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
     * @param  int $id
     *
     * @return Response
     */
    public function show($id){
        // Get the Hero
        $hero = DB::table('participations')
            ->join('heroes', 'participations.hero_id', '=', 'heroes.id')
            ->select(
                'participations.hero_id',
                'heroes.id',
                'heroes.name',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('participations.hero_id', $id)
            ->groupBy('hero_id')
            ->orderBy('heroes.name')
            ->get();
    
    
        // Get stats for each Map
        $maps = DB::table('participations')
            ->join('heroes', 'participations.hero_id', '=', 'heroes.id')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->join('maps', 'games.map_id', '=', 'maps.id')
            ->select(
                'maps.id',
                'maps.name',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('participations.hero_id', $id)
            ->groupBy('maps.id')
            ->orderBy('maps.name')
            ->get();

        // Get 10 bests players with this hero
        $players = DB::table('participations')
            ->join('players', 'participations.player_id', '=', 'players.id')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->select(
                'players.id',
                'players.battletag',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('CONCAT_WS(":", FLOOR(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) / 60), LPAD(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) % 60, 2, 0)) AS avg_length'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('participations.hero_id', $id)
            ->groupBy('players.id')
            // ->having('total_games', '>', 10)
            ->orderBy('total_games', 'desc')
            ->orderBy('percent_win', 'desc')
            ->limit(10)
            ->get();
            
        return view('heroes.show', ['hero' => $hero[0], 'maps' => $maps, 'players' => $players]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {

    }

}

?>