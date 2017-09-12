<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hero extends Model 
{

    protected $table = 'heroes';
    public $timestamps = true;

    public function participations()
    {
        return $this->hasMany('App\Participation');
    }

}