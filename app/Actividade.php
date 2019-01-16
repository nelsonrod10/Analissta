<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actividade extends Model
{
    protected $guarded=[];
    
    public function empresa(){
        return $this->belongsTo('App\EmpresaCliente','empresaCliente_id');
    }
    public function sistema(){
        return $this->belongsTo('App\Sistema');
    }
    
    public function proceso(){
        return $this->belongsTo('App\Proceso');
    }
    
    public function peligros(){
        return $this->hasMany('App\Peligro','actividad_id');
    }
}
