<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Player extends Model 
{
    use Searchable;

    protected $table = 'players';
    public $timestamps = true;
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participations(){
        return $this->hasMany('App\Participation');
    }
}