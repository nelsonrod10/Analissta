<?php

namespace App\Hallazgos;

use Illuminate\Database\Eloquent\Model;

class Hallazgo extends Model
{
    protected $guarded=[];
    
    /*public function empresa(){
        return $this->belongsTo('App\EmpresaCliente');
    }*/
    
    public function sistema(){
        return $this->belongsTo('App\Sistema');
    }
    
    public function proceso(){
        return $this->belongsTo('App\Proceso');
    }
    
    public function centroTrabajo(){
        return $this->belongsTo('App\CentrosTrabajo','centrosTrabajo_id');
    }
    
    public function actividades(){
        return $this->hasMany('App\ActividadesHallazgo');
    }
    
    public function capacitaciones(){
        return $this->hasMany('App\CapacitacionesHallazgo');
    }
    
    public function inspeccionObligatoriaSugerida(){
        return $this->belongsTo('App\InspeccionesObligatoriasSugerida','inspeccion_id');
    }
    
    public function inspeccionValoracion(){
        return $this->belongsTo('App\InspeccionesValoracione','inspeccion_id');
    }
    
    public function evidencias(){
        return $this->hasMany('App\Evidencia','origen_id');
    }
    
}
