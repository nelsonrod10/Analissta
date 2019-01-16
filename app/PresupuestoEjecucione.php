<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresupuestoEjecucione extends Model
{
    protected $guarded=[];
    
    public function sistema(){
        return $this->belongsTo('App\Sistema','sistema_id');
    }
    
    public function presupuestoGeneral(){
        return $this->belongsTo('App\Presupuesto','presupuesto_id');
    }
    
}
