<?php

namespace App\PlanesGestion;

use Illuminate\Database\Eloquent\Model;

class PgrpFisico extends Model
{
    protected $guarded=[];
    
    public function lineaBase(){
        return $this->hasMany('App\PlanesGestion\PgrpFisicoLineaBase','pgrp_fisicos_id');
    }
    
    public function metas(){
        return $this->hasMany('App\PlanesGestion\PgrpFisicoMeta','pgrp_fisicos_id');
    }
}
