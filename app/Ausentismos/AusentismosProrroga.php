<?php

namespace App\Ausentismos;

use Illuminate\Database\Eloquent\Model;

class AusentismosProrroga extends Model
{
    protected $guarded=[];
    
    public function ausentismo(){
        return $this->belongsTo('App\Ausentismo\Ausentismo','ausentismo_id');
    }
}
