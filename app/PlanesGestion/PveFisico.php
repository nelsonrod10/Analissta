<?php

namespace App\PlanesGestion;

use Illuminate\Database\Eloquent\Model;

class PveFisico extends Model
{
    protected $guarded=[];
    
    public function lineaBase(){
        return $this->hasMany('App\PlanesGestion\PveFisicoLineaBase','pve_fisicos_id');
    }
    
    public function metas(){
        return $this->hasMany('App\PlanesGestion\PveFisicoMeta','pve_fisicos_id');
    }
}
