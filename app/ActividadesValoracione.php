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
        return $this->belongsToMany('App\Peligro','actividades_valoracione_peligro','actividad_id','peligro_id');
    }
    
    public function calendarioActividades(){
        return $this->hasMany('App\ActividadesCalendario','actividad_id');
    }
    
}
