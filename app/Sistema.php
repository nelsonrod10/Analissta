<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sistema extends Model
{
    protected $guarded = [];
     
    public function empresa(){
       return $this->belongsTo('App\EmpresaCliente','sistema_id');
    }
    
    public function centrosTrabajo(){
       return $this->belongsToMany('App\CentrosTrabajo','sistema_centros_trabajo');
    }
    
    public function procesos(){
        return $this->hasMany('App\Proceso');
    }
    
    public function ActividadesValoracion(){
        return $this->hasMany('App\ActividadesValoracione','sistema_id');
    }
    
    public function ActividadesHallazgos(){
        return $this->hasMany('App\ActividadesHallazgo','sistema_id');
    }
    
    public function peligros(){
        return $this->hasMany('App\Peligro','sistema_id');
    }
    
    public function pgrpFisico(){
        return $this->hasMany('App\PlanesGestion\PgrpFisico','sistema_id');
    }
    
    public function pgrpSeguridad(){
        return $this->hasMany('App\PlanesGestion\PgrpSeguridade','sistema_id');
    }
    
    public function pgrpGeneral(){
        return $this->hasMany('App\PlanesGestion\PgrpGenerale','sistema_id');
    }
    
    public function pveFisico(){
        return $this->hasMany('App\PlanesGestion\PveFisico','sistema_id');
    }
    
    public function pveSeguridad(){
        return $this->hasMany('App\PlanesGestion\PveSeguridade','sistema_id');
    }
    
    public function pveGeneral(){
        return $this->hasMany('App\PlanesGestion\PveGenerale','sistema_id');
    }


    public function CapacitacionesValoracion(){
        return $this->hasMany('App\CapacitacionesValoracione','sistema_id');
    }
    
    public function CapacitacionesHallazgos(){
        return $this->hasMany('App\CapacitacionesHallazgo','sistema_id');
    }
    
    public function InspeccionesValoracion(){
        return $this->hasMany('App\InspeccionesValoracione','sistema_id');
    }
    
    public function Actividades_Obligatorias_Sugeridas(){
        return $this->hasMany('App\ActividadesObligatoriasSugerida','sistema_id');
    }
    
    public function Capacitaciones_Obligatorias_Sugeridas(){
        return $this->hasMany('App\CapacitacionesObligatoriasSugerida','sistema_id');
    }
    
    public function Inspecciones_Obligatorias_Sugeridas(){
        return $this->hasMany('App\InspeccionesObligatoriasSugerida','sistema_id');
    }
    
    public function hallazgos(){
        return $this->hasMany('App\Hallazgos\Hallazgo','sistema_id');
    }
    
    public function accidentes(){
        return $this->hasMany('App\Accidentes\Accidente','sistema_id');
    }
    
    public function ausentismos(){
        return $this->hasMany('App\Ausentismos\Ausentismo','sistema_id');
    }
    
    public function presupuesto(){
        return $this->hasMany('App\Presupuesto','sistema_id');
    }
}
