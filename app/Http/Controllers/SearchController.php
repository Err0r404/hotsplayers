<?php

namespace App\Http\Controllers;

use App\Player;
use Illuminate\Http\Request;

class SearchController extends Controller{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $players = Player::search($request->input('search'))->get();
        
        return view('search.index', ['search' => $request->input('search'), 'players' => $players]);
    }
}
