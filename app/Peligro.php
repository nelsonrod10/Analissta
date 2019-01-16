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
        return $this->hasMany('App\ActividadesValoracione','peligro_id');
    }
    
    public function capacitacionesValoracion(){
        return $this->hasMany('App\CapacitacionesValoracione','peligro_id');
    }
    
    public function inspeccionesValoracion(){
        return $this->hasMany('App\InspeccionesValoracione','peligro_id');
    }
    
    
}
