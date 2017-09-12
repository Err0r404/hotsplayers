<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model 
{

    protected $table = 'games';
    public $timestamps = true;

    public function participations()
    {
        return $this->hasMany('App\Participation');
    }

    public function map()
    {
        return $this->belongsTo('App\Map');
    }

}