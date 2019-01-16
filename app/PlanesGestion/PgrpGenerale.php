<?php

namespace App\PlanesGestion;

use Illuminate\Database\Eloquent\Model;

class PgrpGenerale extends Model
{
    protected $guarded=[];
    
    public function lineaBase(){
        return $this->hasMany('App\PlanesGestion\PgrpGeneralLineaBase','pgrp_generales_id');
    }
    
    public function metas(){
        return $this->hasMany('App\PlanesGestion\PgrpGeneralMeta','pgrp_generales_id');
    }
}
