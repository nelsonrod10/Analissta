<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspeccionesValoracione extends Model
{
    protected $guarded=[];
    
    public function empresa(){
        return $this->belongsTo('App\EmpresaCliente','empresaCliente_id');
    }
    
    public function sistema(){
        return $this->belongsTo('App\Sistema');
    }
    
    public function peligro(){
        return $this->belongsToMany('App\Peligro');
    }
    
    public function calendarioInspecciones(){
        return $this->hasMany('App\InspeccionesCalendario','actividad_id');
    }
    
    public function hallazgos(){
        return $this->hasMany('App\Hallazgos\Hallazgo','origen_externo_id');
    }
}
