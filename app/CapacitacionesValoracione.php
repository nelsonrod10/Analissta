<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CapacitacionesValoracione extends Model
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
    
    public function calendarioCapacitaciones(){
        return $this->hasMany('App\CapacitacionesCalendario','actividad_id');
    }
}
