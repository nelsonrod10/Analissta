<?php

namespace App\PlanesGestion;

use Illuminate\Database\Eloquent\Model;

class PveGeneralLineaBase extends Model
{
    protected $guarded=[];
    
    public function pve(){
        return $this->belongsTo('App\PlanesGestion\PveGenerale','pve_generales_id');
    }
}
