<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspeccionesObligatoriasSugerida extends Model
{
    protected $guarded=[];
    
    public function sistema(){
        return $this->belongsTo('App\Sistema','sistema_id');
    }
    
    public function hallazgos(){
        return $this->hasMany('App\Hallazgos\Hallazgo','origen_externo_id');
    }
}
