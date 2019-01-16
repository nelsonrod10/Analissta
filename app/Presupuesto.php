<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $guarded=[];
    
    public function sistema(){
        return $this->belongsTo('App\Sistema','sistema_id');
    }
    
    public function itemsEjecuciones(){
        return $this->hasMany('App\PresupuestoEjecucione');
    }
   
}
