<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use Httpful\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ScrapJsonApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrapjson:api {--startAtPage=1} {--stopAtPage=69000}';

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
        /**
         * I recommend you set limit from X000 to X999 so file are filled in correct order
         * Like 001 to 999, 1000 to 1999, etc.
         */
        
        $this->info("Start");
    
        //$handle1 = fopen("storage/app/public/.gitignore", "r");
        //$handle1 = fopen("storage/app/json/0-tmp.json", "r");
        //die();
    
    
        // Starting time
        $startTime = microtime(true);

        // Command's argument
        $from = $this->option('startAtPage');
        $to = $this->option('stopAtPage');
        
        // Min and max values
        $from = ($from < 1) ? 1 : $from;
        $to = ($to > 69000) ? 69000 : $to;
        
        // Vars
        $baseUri = "http://hotsapi.net/api/v1/replays/paged";

        // API send max 100 replays so maybe you want to make several call at once
        for($i = $from; $i <= $to; $i++){

            // Get data from API starting from the last Game registered in DB
            $params   = "?page=" . $i;
            $retry    = 0;
    
            // Call API
            do{
                $response = Request::get($baseUri . $params)->send();
                
                // Retry if call fails
                if($response->code != '200'){
                    $this->info("Retry");
                    $retry++;
                    //usleep(5000000);
                    sleep(5);
                }
                
            }while($response->code != 200 && $retry <= 5);
            
            // If API returned code 200
            if($response->code == '200'){
                $this->info("URI : $baseUri$params (".($i)."/$to)");

                // Create a json file (1 file for 1000 pages approx.)
                $jsonContent = $response->raw_body;
                $fileName = (int)($i / 1000);
    
                // NOT memory friendly when create large file
                //Storage::append("json/$fileName.json", $jsonContent);
                
                // Memory friendly when create large file
                file_put_contents("storage/app/json/$fileName.json", $jsonContent, FILE_APPEND | LOCK_EX);
            }
            else{
                $this->error("API didn't response correctly");
                $this->error("URI : $baseUri$params");
                $this->error("Response Code : #".$response->code);
                die();
            }
            
            // Clean up a bit to free memory ?
            $response = null;
            $jsonContent = null;
            unset($response);
            unset($jsonContent);
    
            // Prevent 30 requests/1 minute limit
            sleep(3);
        }
    
        // Ending time
        $timeend = microtime(true);

        // Execution time
        $time = round(($timeend - $startTime),0);
        //$time = number_format($time,3);
        
        // Convert seconds to a human readable format
        $c = new Controller();
        $time = $c->secondsToHumanReadableString($time);
        
        $this->info("Script executed in $time");

        $this->info("Done");
    }
}
