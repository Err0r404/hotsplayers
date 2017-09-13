<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/players', 'PlayerController@index');
Route::get('/players/{id}', 'PlayerController@show');

Route::resource('player', 'PlayerController');
Route::resource('hero', 'HeroController');
Route::resource('map', 'MapController');
Route::resource('game', 'GameController');
Route::resource('participation', 'ParticipationController');
