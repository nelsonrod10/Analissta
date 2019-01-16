<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CapacitacionesObligatoriasSugerida extends Model
{
    protected $guarded=[];
    
    public function sistema(){
        return $this->belongsTo('App\Sistema','sistema_id');
    }
}
