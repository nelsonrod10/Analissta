<?php

namespace App\Accidentes;

use Illuminate\Database\Eloquent\Model;
class Accidente extends Model
{
    protected $guarded = [];
    
    /*public function empresa(){
        return $this->belongsTo('App\EmpresaCliente');
    }*/
    
    public function sistema(){
        return $this->belongsTo('App\Sistema');
    }
    
    public function centroTrabajo(){
        return $this->belongsTo('App\CentrosTrabajo','centrosTrabajo_id');
    }
    
    public function proceso(){
        return $this->belongsTo('App\Proceso','proceso_id');
    }
    
    public function accidentado(){
        return $this->hasOne('App\Empleado','identificacion','accidentado_id');
    }
    
    public function costos(){
        return $this->hasOne('App\Accidentes\AccidentesCosto','accidente_id');
    }
    
    public function ausentismo(){
        return $this->hasOne('App\Accidentes\AccidentesAusentismo','accidente_id');
    }
    
    public function hallazgo(){
        return $this->hasOne('App\Accidentes\AccidentesHallazgo','accidente_id');
    }
    
    public function evidencias(){
        return $this->hasMany('App\Evidencia','origen_id');
    }
    
}
