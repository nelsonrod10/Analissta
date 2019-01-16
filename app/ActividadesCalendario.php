<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActividadesCalendario extends Model
{
    protected $guarded=[];
    
    public function actividadesValoracion(){
        return $this->belongsTo('App\ActividadesValoracione','actividad_id');
    }
    
    public function evidencias(){
        return $this->hasMany('App\Evidencia','origen_id');
    }
    
    public function presupuestoEjecucion(){
        return $this->hasMany('App\PresupuestoEjecucione','calendario_id');
    }
}
