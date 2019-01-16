<?php

namespace App\PlanesGestion;

use Illuminate\Database\Eloquent\Model;

class PgrpSeguridade extends Model
{
    protected $guarded=[];
    
    public function lineaBase(){
        return $this->hasMany('App\PlanesGestion\PgrpSeguridadLineaBase','pgrp_seguridades_id');
    }
    
    public function metas(){
        return $this->hasMany('App\PlanesGestion\PgrpSeguridadMeta','pgrp_seguridades_id');
    }
}
