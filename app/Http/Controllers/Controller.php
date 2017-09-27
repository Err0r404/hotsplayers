<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * Convert seconds to a human readable string
     *
     * @param int $seconds
     *
     * @return string
     * @internal param int $seconds
     *
     */
    function secondsToHumanReadableString(int $seconds){
        $m = floor(($seconds%3600)/60);
        $h = floor(($seconds%86400)/3600);
        $d = floor(($seconds)/86400);
        
        $result = "";
        
        if($d > 0)
            $result.= "$d days";
        
        if($result != "")
            $result .= ", ";
        
        if($h > 0)
            $result.= "$h hours";
        
        if($result != "")
            $result .= ", ";
        
        if($m > 0)
            $result.= "$m minutes";
        
        return $result;
    }
    
    /**
     * Convert large numbers to a human readable format : 1200 => 1.2K
     *
     * @param        $num
     * @param int    $places
     * @param string $type
     *
     * @return string
     */
    function numbertoHumanReadableFormat($num, $places = 1, $type = 'metric'){
        if ($type == 'metric') {
            $k = 'K'; $m = 'M';
        }
        else {
            $k = ' thousand'; $m = ' million';
        }
        
        if ($num < 1000) {
            $num_format = number_format($num);
        }
        else if ($num < 1000000) {
            $num_format = rtrim(rtrim(number_format($num / 1000, $places), 0), '.') . $k;
        }
        else {
            $num_format = rtrim(rtrim(number_format($num / 1000000, $places), 0), '.') . $m;
        }
        
        return $num_format;
    }
}
