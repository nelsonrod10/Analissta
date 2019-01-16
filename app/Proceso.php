<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    protected $guarded=[];
    
    public function sistema(){
        $this->belongsTo('App\Sistema','empresaCliente_id');
    }
    
    public function actividades(){
        $this->hasMany('App\Actividade','proceso_id');
    }
    
    public function hallazgos(){
        $this->hasMany('App\Hallazgos\Hallazgo','proceso_id');
    }
    
    public function accidentes(){
        $this->hasMany('App\Accidentes\Accidente','proceso_id');
    }
    
}
