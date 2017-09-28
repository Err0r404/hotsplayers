<?php

namespace App\Console\Commands;

use App\Game;
use App\Hero;
use App\Http\Controllers\Controller;
use App\Map;
use App\Participation;
use App\Player;
use Httpful\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ScrapApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrap:api {loop=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrap the HOTS API to get data';

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
        //$lastGame = DB::table('games')->latest('api_id')->first();
        //print_r($lastGame);
        //
        //return false;

        $this->info("Start");

        // Starting time
        $timestart = microtime(true);

        // Command's argument
        $loop = $this->argument('loop');

        // Vars
        $baseUri = "http://hotsapi.net/api/v1";

        // API send max 100 replays so maybe you want to make several call at once
        for($i = 0; $i < $loop; $i++){
            // Get the last Game from DB to paginate the API
            $lastGame = DB::table('games')->latest('api_id')->first();

            // If DB is emtpy start at 0
            $lastGameId = (sizeof($lastGame) == 0) ? 0 : $lastGame->api_id;

            // Get data from API starting from the last Game registered in DB
            $uri      = $baseUri . "/replays";
            $params   = "?min_id=" . ($lastGameId+1);
            $retry    = 0;
            
            do{
                $response = Request::get($uri . $params)->send();
                
                if($response->code == '200'){
                    $retry++;
                    sleep(1);
                }
                
            }while($response->code != 200 && $retry < 5);

            // If API returned code 200
            if($response->code == '200'){
                $this->info("Parsing URI : $uri$params (".($i+1)."/$loop)");

                // Wrapper
                $apiReplays = $response->body;

                // Loop trough all replays
                foreach ($apiReplays as $replay){
                    // Wrappers
                    $apiId         = $replay->id;
                    $apiGameType   = $replay->game_type;
                    $apiGameDate   = $replay->game_date;
                    $apiGameLength = $replay->game_length;
                    $apiMapName    = $replay->game_map;
                    
                    if($apiMapName != null){
                        // Get additional data about the replay
                        $additionalUri  = $uri . "/" . $apiId;
                        $addtionalRetry = 0;
                        
                        do{
                            $additionalResponse = Request::get($additionalUri)->send();
                            
                            if($additionalResponse->code != '200'){
                                $addtionalRetry++;
                                sleep(1);
                            }
                            
                        }while($additionalResponse->code != '200' && $addtionalRetry < 5);
    
                        // If API returned code 200
                        if($additionalResponse->code == 200){
                            // Wrapper
                            $apiPlayers = $additionalResponse->body->players;
        
                            // Enable mass assignment
                            Map::unguard();
                            Game::unguard();
                            Player::unguard();
                            Hero::unguard();
                            Participation::unguard();
        
                            // Get or create Map
                            $map = Map::firstOrCreate(
                                array('name' => $apiMapName)
                            );
        
                            // Update the column updated_at
                            $map->touch();
        
                            //$this->info("Map : $map");
        
                            // Get or create Game
                            $game = Game::firstOrCreate(
                                ['api_id' => $apiId],
                                ['type' => $apiGameType, 'length' => $apiGameLength, 'date' => $apiGameDate, 'map_id' => $map->id]
                            );
        
                            // Update the column updated_at
                            $game->touch();
        
                            //$this->info("Game : $game");
        
                            // Loop trough all players in the replay
                            foreach ($apiPlayers as $apiPlayer) {
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
                                $player->touch();
            
                                //$this->info("Player : $player");
            
                                // Get or create Hero
                                $hero = Hero::firstOrCreate(
                                    ['name' => $apiHeroName]
                                );
            
                                // Update the column updated_at
                                $hero->touch();
            
                                //$this->info("Hero : $hero");
            
                                // Get or create Participation
                                $participation = Participation::firstOrCreate(
                                    ['hero_id' => $hero->id, 'player_id' => $player->id, 'game_id' => $game->id],
                                    ['win' => $apiWin]
                                );
            
                                // Update the column updated_at
                                $participation->touch();
            
                                //$this->info("Participation : $participation");
                            }
        
                            // Basic log to show progress
                            $this->output->write(".");
        
                            // Disable mass assignment
                            Map::reguard();
                            Game::reguard();
                            Player::reguard();
                            Hero::reguard();
                            Participation::reguard();
                        }
                        else{
                            $this->error("API didn't response correctly");
                            $this->error("URI : $additionalUri");
                            $this->error("Response Code : #".$additionalResponse->code);
                        }
                    }
                }

                // Force line break
                $this->output->write(" ", true);
            }
            else{
                $this->error("API didn't response correctly");
                $this->error("URI : $uri");
                $this->error("Response Code : #".$response->code);
            }
        }
    
        // Ending time
        $timeend = microtime(true);

        // Execution time
        $time = round(($timeend - $timestart),0);
        //$time = number_format($time,3);
        
        // Convert seconds to a human readable format
        $c = new Controller();
        $time = $c->secondsToHumanReadableString($time);
        
        $this->info("Script executed in $time");

        $this->info("Done");
    }
}
