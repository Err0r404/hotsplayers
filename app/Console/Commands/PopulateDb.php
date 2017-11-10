<?php

namespace App\Console\Commands;

use App\Map;
use App\Game;
use App\Hero;
use App\Player;
use App\Participation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate database from a HotsApi SQL dump';

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
        // Enable mass assignment
        Map::unguard();
        Game::unguard();
        Player::unguard();
        Hero::unguard();
        Participation::unguard();
    
        // Get the last Game from DB to paginate the API
        $lastGame = DB::table('games')->latest('api_id')->first();
    
        // If DB is emtpy start at 0
        $lastGameId = (sizeof($lastGame) == 0) ? 0 : $lastGame->api_id;
        
        $this->line("Starting at $lastGameId");
    
        // Disable loggin for performance
        DB::connection('mysql_external')->disableQueryLog();
        DB::connection()->disableQueryLog();
    
        // Connect to HotsApi DB
        $hotsapi = DB::connection('mysql_external');
        $hotsapi->disableQueryLog();
        
        $totalReplays = $hotsapi->table('replays')->where('id', '>', $lastGameId)->count();
        $chunkSize    = 25000;
        $nbLoop       = ceil($totalReplays/$chunkSize);
        $iterator     = 1;
        
        // Loop trough all replays by chunk
        $hotsapi->table('replays')->where('id', '>', $lastGameId)->orderBy('id')->chunk($chunkSize, function($replays) use($hotsapi, &$iterator, $nbLoop){
            // Show progress
            $this->line("Chunk $iterator/$nbLoop");
            
            // Loop trough all replays in the current chunk
            foreach($replays as $replay){
                // Wrappers
                $apiMapName    = $replay->game_map;
                $apiId         = $replay->id;
                $apiGameType   = $replay->game_type;
                $apiGameLength = $replay->game_length;
                $apiGameDate   = $replay->game_date;
                
                if($apiMapName != null){
                    // Get or create Map
                    $map = Map::firstOrCreate(
                        array('name' => $apiMapName)
                    );
    
                    // Update the column updated_at
                    //$map->touch();
    
                    // Get or create Game
                    $game = Game::firstOrCreate(
                        ['api_id' => $apiId],
                        ['type' => $apiGameType, 'length' => $apiGameLength, 'date' => $apiGameDate, 'map_id' => $map->id]
                    );
    
                    // Update the column updated_at
                    //$game->touch();
    
                    // Loop trough players from the replay
                    $apiPlayers = $hotsapi->table('players')->where('replay_id', '=', $apiId)->get();
                    foreach($apiPlayers as $apiPlayer){
                        // Wrappers
                        $apiBattletag  = $apiPlayer->battletag;
                        $apiBlizzardId = $apiPlayer->blizz_id;
                        $apiHeroName   = $apiPlayer->hero;
                        $apiWin        = $apiPlayer->winner;
        
                        // Get or create Player
                        $player = Player::firstOrCreate(
                            ['blizzard_id' => $apiBlizzardId],
                            ['battletag' => $apiBattletag]
                        );
        
                        // Update the column updated_at
                        //$player->touch();
        
                        // Get or create Hero
                        $hero = Hero::firstOrCreate(
                            ['name' => $apiHeroName]
                        );
        
                        // Update the column updated_at
                        //$hero->touch();
        
                        // Get or create Participation
                        $participation = Participation::firstOrCreate(
                            ['hero_id' => $hero->id, 'player_id' => $player->id, 'game_id' => $game->id],
                            ['win' => $apiWin]
                        );
        
                        // Update the column updated_at
                        //$participation->touch();
                    }
    
                    // Basic log to show progress
                    $this->output->write(".");
                }
            }
            
            // Force break line
            $this->line("");
            
            $iterator++;
        });
    
        // Disable mass assignment
        Map::reguard();
        Game::reguard();
        Player::reguard();
        Hero::reguard();
        Participation::reguard();
    }
}
