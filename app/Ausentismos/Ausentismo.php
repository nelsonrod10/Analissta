<?php

namespace App\Ausentismos;

use Illuminate\Database\Eloquent\Model;

class Ausentismo extends Model
{
    protected $guarded=[];
    
    /*public function empresa(){
        return $this->belongsTo('App\EmpresaCliente');
    }*/
    
    public function sistema(){
        return $this->belongsTo('App\Sistema');
    }
    
    public function centroTrabajo(){
        return $this->belongsTo('App\CentrosTrabajo','centrosTrabajo_id');
    }
    
    public function ausentado(){
        return $this->hasOne('App\Empleado','identificacion','ausentado_id');
    }
    
    public function calculos() {
        return $this->hasMany('App\Ausentismos\AusentismosCalculo','ausentismo_id');
    }
    
    public function prorrogas() {
        return $this->hasMany('App\Ausentismos\AusentismosProrroga','ausentismo_id');
    }
    public function evidencias(){
        return $this->hasMany('App\Evidencia','origen_id');
    }
}
