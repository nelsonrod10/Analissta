<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CentrosTrabajo extends Model
{
    protected $guarded = [];
    
    public function sistemaGestion(){
       return $this->belongsToMany('App\Sistema','sistema_centros_trabajo');
    }
    
    public function empresaCentro(){
        return $this->belongsTo('App\EmpresaCliente','empresaCliente_id');
    }
    
    public function empleadosCentro(){
        return $this->hasMany('App\Empleado','centrosTrabajos_id');
    }
    
    public function hallazgos(){
        return $this->hasMany('App\Hallazgos\Hallazgo','centrosTrabajo_id');
    }
    
    public function ausentismos(){
        return $this->hasMany('App\Ausentismos\Ausentismo','centrosTrabajo_id');
    }
    
    public function accidentes(){
        return $this->hasMany('App\Accidentes\Accidente','centrosTrabajo_id');
    }
    
}
