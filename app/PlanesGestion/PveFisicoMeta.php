<?php

namespace App\PlanesGestion;

use Illuminate\Database\Eloquent\Model;

class PveFisicoMeta extends Model
{
    protected $guarded=[];
    
    public function pve(){
        return $this->belongsTo('App\PlanesGestion\PveFisico','pve_fisicos_id');
    }
}
