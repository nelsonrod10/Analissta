<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CapacitacionesCalendario extends Model
{
    protected $guarded=[];
    
    public function evidencias(){
        return $this->hasMany('App\Evidencia','origen_id');
    }
    
    public function presupuestoEjecucion(){
        return $this->hasMany('App\PresupuestoEjecucione','calendario_id');
    }
}
