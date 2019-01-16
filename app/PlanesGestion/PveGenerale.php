<?php

namespace App\PlanesGestion;

use Illuminate\Database\Eloquent\Model;

class PveGenerale extends Model
{
    protected $guarded=[];
    
    public function lineaBase(){
        return $this->hasMany('App\PlanesGestion\PveGeneralLineaBase','pve_generales_id');
    }
    
    public function metas(){
        return $this->hasMany('App\PlanesGestion\PveGeneralMeta','pve_generales_id');
    }
}
