<?php

namespace App\PlanesGestion;

use Illuminate\Database\Eloquent\Model;

class PgrpFisicoMeta extends Model
{
    protected $guarded=[];
    
    public function pgrp(){
        return $this->belongsTo('App\PlanesGestion\PgrpFisico','pgrp_fisicos_id');
    }
}
