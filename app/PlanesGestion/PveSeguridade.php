<?php

namespace App\PlanesGestion;

use Illuminate\Database\Eloquent\Model;

class PveSeguridade extends Model
{
    protected $guarded=[];
    
    public function lineaBase(){
        return $this->hasMany('App\PlanesGestion\PveSeguridadLineaBase','pve_seguridades_id');
    }
    
    public function metas(){
        return $this->hasMany('App\PlanesGestion\PveSeguridadMeta','pve_seguridades_id');
    }
}
