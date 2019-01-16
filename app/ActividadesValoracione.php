<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActividadesValoracione extends Model
{
    protected $guarded=[];
    
    public function empresa(){
        return $this->belongsTo('App\EmpresaCliente','empresaCliente_id');
    }
    public function sistema(){
        return $this->belongsTo('App\Sistema');
    }
    
    public function peligro(){
        return $this->belongsTo('App\Peligro');
    }
    
    public function calendarioActividades(){
        return $this->hasMany('App\ActividadesCalendario','actividad_id');
    }
    
}
