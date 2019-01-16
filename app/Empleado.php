<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $guarded = [];
    
    public function centrosTrabajo(){
        return $this->belongsTo('App\CentrosTrabajo','centrosTrabajos_id');
    }
    
    public function empresa(){
        return $this->belongsTo('App\EmpresaCliente','empresaCliente_id');
    }
    
    public function usuarios(){
        return $this->hasMany('App\Usuario','empleados_id');
    }
    
    public function accidentes(){
        return $this->hasMany('App\Accidentes\Accidente','accidentado_id','identificacion');
    }
}
