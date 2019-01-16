<?php

namespace App\Accidentes;

use Illuminate\Database\Eloquent\Model;

class AccidentesCosto extends Model
{
    protected $guarded = [];
    
    public function accidente(){
        return $this->belongsTo('App\Accidentes\Accidente','accidente_id');
    }
}
