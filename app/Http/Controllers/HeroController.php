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
        $heroes = DB::table('heroes')
            ->select(
                'heroes.id',
                'heroes.name',
                'heroes.games',
                'heroes.games as original_games',
                DB::raw('ROUND((victories/games)*100,2) AS winrate')
            )
            ->orderBy('heroes.name')
            ->get();
    
        foreach ($heroes as $hero) {
            $hero->games = $this->numbertoHumanReadableFormat($hero->games);
        }

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
                DB::raw("'All games' AS type"),
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('CONCAT_WS(":", FLOOR(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) / 60), LPAD(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) % 60, 2, 0)) AS avg_length'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('participations.hero_id', $id)
            ->groupBy('maps.id')
            ->orderBy('maps.name')
            ->get();
    
        // Get same stats as previous but filtering by Game's type
        $mapsByTypes = DB::table('participations')
            ->join('heroes', 'participations.hero_id', '=', 'heroes.id')
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
            ->where('participations.hero_id', $id)
            ->groupBy('maps.id')
            ->groupBy('games.type')
            ->orderBy('maps.name')
            ->get();
    
        // Mix the 2 Map's collections
        foreach($mapsByTypes as $map){
            $maps->push($map);
        }
    
        // Get Heroes stats against the chosen Hero
        $enemies = DB::table('participations')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->join('participations as p2', 'p2.game_id', '=', 'games.id')
            ->join('heroes', 'p2.hero_id', '=', 'heroes.id')
            ->select(
                'heroes.id',
                'heroes.name',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw("'All games' AS type"),
                DB::raw('SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('participations.hero_id', '=', $id)
            ->where('participations.win', '<>', DB::raw('`p2`.`win`'))
            ->where('participations.id', '<>', DB::raw('`p2`.`id`'))
            ->groupBy('heroes.id')
            ->orderBy('total_games', 'desc')
            ->orderBy('percent_win', 'desc')
            ->get();
    
        // Get Heroes stats against the chosen Hero filtered by Game's type
        $enemiesByType = DB::table('participations')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->join('participations as p2', 'p2.game_id', '=', 'games.id')
            ->join('heroes', 'p2.hero_id', '=', 'heroes.id')
            ->select(
                'heroes.id',
                'heroes.name',
                'games.type',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('participations.hero_id', '=', $id)
            ->where('participations.win', '<>', DB::raw('`p2`.`win`'))
            ->where('participations.id', '<>', DB::raw('`p2`.`id`'))
            ->groupBy('heroes.id')
            ->groupBy('games.type')
            ->orderBy('total_games', 'desc')
            ->orderBy('percent_win', 'desc')
            ->get();
    
        // Mix the 2 Enemies's collections
        foreach($enemiesByType as $enemy){
            $enemies->push($enemy);
        }
    
        // Get Heroes stats with the chosen Hero
        $allies = DB::table('participations')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->join('participations as p2', 'p2.game_id', '=', 'games.id')
            ->join('heroes', 'p2.hero_id', '=', 'heroes.id')
            ->select(
                'heroes.id',
                'heroes.name',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw("'All games' AS type"),
                DB::raw('SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('participations.hero_id', '=', $id)
            ->where('participations.win', '=', DB::raw('`p2`.`win`'))
            ->where('participations.id', '<>', DB::raw('`p2`.`id`'))
            ->groupBy('heroes.id')
            ->orderBy('total_games', 'desc')
            ->orderBy('percent_win', 'desc')
            ->get();
    
        // Get Heroes stats with the chosen Hero
        $alliesByTypes = DB::table('participations')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->join('participations as p2', 'p2.game_id', '=', 'games.id')
            ->join('heroes', 'p2.hero_id', '=', 'heroes.id')
            ->select(
                'heroes.id',
                'heroes.name',
                'games.type',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('participations.hero_id', '=', $id)
            ->where('participations.win', '=', DB::raw('`p2`.`win`'))
            ->where('participations.id', '<>', DB::raw('`p2`.`id`'))
            ->groupBy('heroes.id')
            ->groupBy('games.type')
            ->orderBy('total_games', 'desc')
            ->orderBy('percent_win', 'desc')
            ->get();
    
        // Mix the 2 Heroes's collections
        foreach($alliesByTypes as $ally){
            $allies->push($ally);
        }
    
        // Get 10 bests players with this hero
        $players = DB::table('participations')
            ->join('players', 'participations.player_id', '=', 'players.id')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->select(
                'players.id',
                'players.battletag',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw("'All games' AS type"),
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
    
        // Get 10 bests players with this hero
        $playersByTypes = DB::table('participations')
            ->join('players', 'participations.player_id', '=', 'players.id')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->select(
                'players.id',
                'players.battletag',
                'games.type',
                DB::raw('COUNT(1) AS total_games'),
                DB::raw('CONCAT_WS(":", FLOOR(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) / 60), LPAD(SEC_TO_TIME(ROUND(AVG(`games`.`length`),0)) % 60, 2, 0)) AS avg_length'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win'),
                DB::raw('ROUND((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS percent_win')
            )
            ->where('participations.hero_id', $id)
            ->groupBy('players.id')
            ->groupBy('games.type')
            // ->having('total_games', '>', 10)
            ->orderBy('total_games', 'desc')
            ->orderBy('percent_win', 'desc')
            ->limit(10)
            ->get();
    
        // Mix the 2 Player's collections
        foreach($playersByTypes as $player){
            $players->push($player);
        }
    
        // Get all Games's types that the Player did
        $types = DB::table('participations')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->select(
               'games.type as name',
                DB::raw('COUNT(1)')
            )
            ->where('hero_id', '=', $id)
            ->groupBy('games.type')
            ->orderBy('type', 'asc')
            ->get();
            
        return view(
            'heroes.show',
            [
                'hero'    => $hero[0],
                'types'   => $types,
                'maps'    => $maps,
                'enemies' => $enemies,
                'allies'  => $allies,
                'players' => $players,
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