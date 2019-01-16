<?php

namespace App\PlanesGestion;

use Illuminate\Database\Eloquent\Model;

class PveSeguridadMeta extends Model
{
    protected $guarded=[];
    
    public function pve(){
        return $this->belongsTo('App\PlanesGestion\PveSeguridade','pve_seguridades_id');
    }
}
