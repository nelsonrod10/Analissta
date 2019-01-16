<?php

namespace App\PlanesGestion;

use Illuminate\Database\Eloquent\Model;

class PgrpSeguridadLineaBase extends Model
{
    protected $guarded=[];
    
    public function pgrp(){
        return $this->belongsTo('App\PlanesGestion\PgrpSeguridade','pgrp_seguridades_id');
    }
}
