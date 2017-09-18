<?php

namespace App\Http\Controllers;

use App\Player;
use Illuminate\Http\Request;

class SearchController extends Controller{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @internal param Request $request
     */
    public function index(Request $request){
    
        $query = $request->input('query');
        $players = Player::search($query)->paginate(5);

        return view('search.index', ['search' => $query, 'players' => $players]);
    }
}
