<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RawSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'raw:sql';

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
        // Disable loggin for performance
        DB::connection('mysql_external')->disableQueryLog();
        DB::connection()->disableQueryLog();
        
        $hotsapi = DB::connection('mysql_external');
        $hotsapi->disableQueryLog();
    
        $limit  = 10000;
        $offset = 1100000;
        //$max    = 30300000;
        $max    = $hotsapi->table('players')->count();
    
        // Starting time
        $totaltimestart = microtime(true);
        
        do {
            // Starting time
            $timestart = microtime(true);
    
            $this->line("Running request with \$offset => $offset");
    
            $sql = "INSERT 	INTO `hotsapi`.`custom_players` (`blizzard_id`, `battletag`, `created_at`, `updated_at`)
                    SELECT 	`blizz_id`, `battletag`, NOW(), NOW()
                    FROM 	`hotsapi`.`players`
                    ORDER BY id ASC
                    LIMIT 	$limit OFFSET $offset
                    ON DUPLICATE KEY UPDATE `updated_at` = NOW()";
            
            DB::insert($sql);
    
            $offset += $limit;
    
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
                $time = (string)$time."seconds<<";
            }
    
            $this->info("Insert executed in $time");
    
            usleep(500000);
    
        } while ($offset <= $max);
    
        // Ending time
        $totaltimeend = microtime(true);
    
        // Execution time
        $totaltime = round(($totaltimeend - $totaltimestart),0);
    
        // Convert seconds to a human readable format
        if($totaltime > 60){
            $c = new Controller();
            $totaltime = $c->secondsToHumanReadableString($totaltime);
        }
        else{
            $totaltime = (string)$totaltime."seconds<<";
        }
    
        $this->info("Script executed in $totaltime");
    
    }
}
