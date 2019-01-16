<?php

namespace App\PlanesGestion;

use Illuminate\Database\Eloquent\Model;

class PgrpGeneralMeta extends Model
{
    protected $guarded=[];
    
    public function pgrp(){
        return $this->belongsTo('App\PlanesGestion\PgrpGenerale','pgrp_generales_id');
    }
}
