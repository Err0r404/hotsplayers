<?php

namespace App\Console\Commands;

use App\Hero;
use App\Map;
use App\Player;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ComputeWinrates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compute:winrates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compute total number of games and total number of victories for each Players, Heroes and Maps';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Start");
    
        // Starting time
        $timestart = microtime(true);
    
        // Enable mass assignment
        Hero::unguard();
        Map::unguard();
        Player::unguard();
        
        // Get all Heroes
        $heroes = Hero::all();
        $this->info("Heroes : ".count($heroes));
    
        // Compute winrates for each Hero
        foreach($heroes as $hero){
            // Get the stats for a Hero
            $stats = DB::table('participations')
                ->join('heroes', 'participations.hero_id', '=', 'heroes.id')
                ->select(
                    DB::raw('COUNT(1) AS total_games'),
                    DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win')
                )
                ->where('participations.hero_id', $hero->id)
                ->groupBy('hero_id')
                ->get();
    
            // Update the Hero with stats
            $hero->update([
                'games'     => $stats[0]->total_games,
                'victories' => $stats[0]->total_win,
            ]);
            
            $this->output->write(".");
        }
        $this->output->write(" ", true);
        
        // Get all Maps
        $maps = Map::all();
        $this->info("Maps : ".count($maps));
    
        // Compute winrates for each Hero
        foreach($maps as $map){
            // Get the stats for a Hero
            $stats = DB::table('participations')
                ->join('games', 'participations.game_id', '=', 'games.id')
                ->join('maps', 'games.map_id', '=', 'maps.id')
                ->select(
                    DB::raw('COUNT(1) AS total_games')
                )
                ->where('maps.id', $map->id)
                ->groupBy('maps.id')
                ->get();
    
            // Update the Hero with stats
            $map->update([
                'games'     => $stats[0]->total_games,
            ]);
            
            $this->output->write(".");
        }
        $this->output->write(" ", true);
        
        // Get all Players
        $totalPlayers = DB::table('players')->count();
        $this->info("Players : ".count($totalPlayers));
    
        // Due to the huge amount of players we have to "paginate"
        $maxChunk = 10000;
        Player::chunk($maxChunk, function($players){
            // Compute winrates for each Hero
            foreach($players as $player){
                // Get the stats for a Hero
                $stats = DB::table('participations')
                    ->select(
                        DB::raw('COUNT(1) AS total_games'),
                        DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win')
                    )
                    ->where('player_id', '=', $player->id)
                    ->groupBy('player_id')
                    ->get();
        
                // Update the Hero with stats
                $player->update([
                    'games'     => $stats[0]->total_games,
                    'victories' => $stats[0]->total_win,
                ]);
        
                $this->output->write(".");
            }
        });
    
        $this->output->write(" ", true);
    
        // Disable mass assignment
        Hero::reguard();
        Map::reguard();
        Player::reguard();
        
        // Ending time
        $timeend = microtime(true);
    
        // Execution time
        $time = round(($timeend - $timestart),0);
    
        // Convert seconds to a human readable format
        if($time > 60){
            $c = new Controller();
            $time = $c->secondsToHumanReadableString($time);
        }
        else{
            $time = (string)$time."seconds";
        }
    
        $this->info("Script executed in $time");
    
        $this->info("End");
    }
}
