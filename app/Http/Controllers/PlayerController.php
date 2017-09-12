<?php

namespace App\Http\Controllers;

use App\Player;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller{
    
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){
    
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
        //     , (SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100 AS percent_win
        // FROM    `participations`, `players`
        // WHERE 	`participations`.`player_id` = `players`.`id`
        // GROUP BY `player_id`
        // ORDER BY total_games DESC
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