<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Peligro extends Model
{
    protected $guarded=[];
    
    public function empresa(){
        return $this->belongsTo('App\EmpresaCliente');
    }
    
    public function sistema(){
        return $this->belongsTo('App\Sistema');
    }
    
    public function actividad(){
        return $this->belongsTo('App\Actividade');
    }
    
    public function cortoPlazo(){
        return $this->hasOne('App\CortoPlazo','peligro_id');
    }
    
    public function largoPlazo(){
        return $this->hasOne('App\LargoPlazo','peligro_id');
    }
    
    public function revaloracionCortoPlazo(){
        return $this->hasMany('App\CortoPlazo','peligro_id');
    }
    
    public function revaloracionLargoPlazo(){
        return $this->hasMany('App\LargoPlazo','peligro_id');
    }
    
    public function actividadesValoracion(){
        return $this->belongsToMany('App\ActividadesValoracione','actividades_valoracione_peligro','peligro_id','actividad_id');
    }
    
    public function capacitacionesValoracion(){
        return $this->belongsToMany('App\CapacitacionesValoracione','capacitaciones_valoracione_peligro','peligro_id','capacitacion_id');
    }
    
    public function inspeccionesValoracion(){
        return $this->belongsToMany('App\InspeccionesValoracione','inspecciones_valoracione_peligro','peligro_id','inspeccion_id');
    }
    
    
}
