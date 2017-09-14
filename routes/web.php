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

// Homepage
Route::get('/', function () {
    return view('home');
});

// Players
Route::get('/players', 'PlayerController@index');
Route::get('/players/{id}', 'PlayerController@show');

// Heroes
Route::get('/heroes', 'HeroController@index');
Route::get('/heroes/{id}', 'HeroController@show');


// Generated by laravelsd.com
Route::resource('player', 'PlayerController');
Route::resource('hero', 'HeroController');
Route::resource('map', 'MapController');
Route::resource('game', 'GameController');
Route::resource('participation', 'ParticipationController');
