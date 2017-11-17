<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Exception\RuntimeException;

class PopulateTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:table {tableName} {--offset=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate specific table from the HotsApi dump';

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
    public function handle() {
        // Starting time
        $totalTimeStart = microtime(true);

        $allowedTableNames = array("games", "heroes", "maps", "participations", "players");
        $tableName         = $this->argument("tableName");
        $offsetOption      = $this->option("offset");
    
    
        // Disable loggin for performance
        DB::connection('mysql_external')->disableQueryLog();
        DB::connection()->disableQueryLog();
    
        // Connect to HotsApi DB
        $hotsapi = DB::connection('mysql_external');
        $hotsapi->disableQueryLog();
    
        switch ($tableName){
            case "games":
                $this->line("START populate table $tableName");
                
                $sql = "INSERT  INTO `hotsapi`.`custom_games` (`api_id`, `type`, `length`, `date`, `map_id`, `created_at`, `updated_at`)
                        SELECT	`replays`.`id`,
                                `replays`.`game_type`,
                                `replays`.`game_length`,
                                `replays`.`game_date`,
                                `custom_maps`.`id`,
                                NOW(),
                                NOW()
                        FROM 	`hotsapi`.`replays`,
                                `hotsapi`.`custom_maps`
                        WHERE 	`replays`.`game_map` = `custom_maps`.`name`
                        ON DUPLICATE KEY UPDATE `updated_at` = NOW()";
    
                DB::insert($sql);
    
                $this->line("END populate table $tableName");
                break;
            case "heroes":
                $this->line("START populate table $tableName");
    
                $sql = "INSERT  INTO `hotsapi`.`custom_heroes` (`name`, `created_at`, `updated_at`)
                        SELECT 	DISTINCT(`hero`),
                                NOW(),
                                NOW()
                        FROM 	`hotsapi`.`players`
                        ON DUPLICATE KEY UPDATE `updated_at` = NOW()";
    
                DB::insert($sql);
    
                $this->line("END populate table $tableName");
                break;
            case "maps":
                $this->line("START populate table $tableName");
    
                $sql = "INSERT  INTO `hotsapi`.`custom_maps` (`name`, `created_at`, `updated_at`)
                        SELECT 	DISTINCT(`game_map`),
                                NOW(),
                                NOW()
                        FROM 	`hotsapi`.`replays`
                        WHERE	`game_map` IS NOT NULL
                        ON DUPLICATE KEY UPDATE `updated_at` = NOW()";
    
                DB::insert($sql);
                
                $this->line("END populate table $tableName");
                break;
            case "participations":
                $this->line("WARNING this will take several hours");
                sleep(3);
    
                $limit      = 10000;
                $offset     = $offsetOption;
                $maxPlayers = $hotsapi->table('players')->count();
                $maxHeroes  = $hotsapi->table('custom_heroes')->count();
                
                for($heroId = 1; $heroId <= $maxHeroes; $heroId++){
                    do{
                        // Starting time
                        $timeStart = microtime(true);
    
                        $this->line("Running request with \$offset => $offset and hero_id = $heroId");
    
                        $sql = "INSERT  INTO `hotsapi`.`custom_participations` (`hero_id`, `player_id`, `game_id`, `win`, `created_at`, `updated_at`)
                                SELECT 	`custom_heroes`.`id` AS hero_id,
                                        `custom_players`.`id` AS player_id,
                                        `custom_games`.`id` AS game_id,
                                        `players`.`winner`,
                                        NOW() AS created_at,
                                        NOW() AS updated_at
                                FROM 	`hotsapi`.`players`
                                        INNER JOIN `hotsapi`.`custom_heroes`  ON `players`.`hero`      = `custom_heroes`.`name`
                                        INNER JOIN `hotsapi`.`custom_players` ON `players`.`blizz_id`  = `custom_players`.`blizzard_id`
                                        INNER JOIN `hotsapi`.`custom_games`   ON `players`.`replay_id` = `custom_games`.`api_id`
                                WHERE  `custom_heroes`.`id` = $heroId
                                LIMIT 	$limit OFFSET $offset
                                ON DUPLICATE KEY UPDATE `updated_at` = NOW()";
    
                        $res = DB::insert($sql);
                        
                        $offset += $limit;
    
                        // Ending time
                        $timeEnd = microtime(true);
    
                        // Execution time
                        $time = round(($timeEnd - $timeStart),0);
    
                        // Convert seconds to a human readable format
                        $c = new Controller();
                        $time = $c->secondsToHumanReadableString($time);
    
                        $this->info("Insert executed in $time");
    
                        usleep(250000);
    
                    }while($offset < $maxPlayers || DB::getPdo()->lastInsertId() != 0);
                }
                
                break;
            case "players":
                $this->line("WARNING this will take several hours");
                sleep(3);
                
                $this->line("START populate table $tableName");
    
                $limit  = 10000;
                $offset = $offsetOption;
                $max    = $hotsapi->table('players')->count();
    
                do {
                    // Starting time
                    $timeStart = microtime(true);
        
                    $this->line("Running request with \$offset => $offset");
        
                    $sql = "INSERT 	INTO `hotsapi`.`custom_players` (`blizzard_id`, `battletag`, `created_at`, `updated_at`)
                            SELECT 	`blizz_id`,
                                    `battletag`,
                                    NOW(),
                                    NOW()
                            FROM 	`hotsapi`.`players`
                            ORDER BY id ASC
                            LIMIT 	$limit OFFSET $offset
                            ON DUPLICATE KEY UPDATE `updated_at` = NOW()";
        
                    DB::insert($sql);
        
                    $offset += $limit;
        
                    // Ending time
                    $timeEnd = microtime(true);
        
                    // Execution time
                    $time = round(($timeEnd - $timeStart),0);
        
                    // Convert seconds to a human readable format
                    $c = new Controller();
                    $time = $c->secondsToHumanReadableString($time);
        
                    $this->info("Insert executed in $time");
        
                    usleep(250000);
        
                } while ($offset <= $max);

                break;
            default:
                $this->line("Unknown table $tableName");
                $this->line("Table's name must be one of the following : " . implode(", ", $allowedTableNames));
                break;
        }
    
        // Ending time
        $totalTimeEnd = microtime(true);
    
        // Execution time
        $totalTime = round(($totalTimeEnd - $totalTimeStart),0);
    
        // Convert seconds to a human readable format
        $controller = new Controller();
        $totalTime  = $controller->secondsToHumanReadableString($totalTime);
    
        $this->info("Script executed in $totalTime");
    
    }
}
