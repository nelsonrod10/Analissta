<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asesore extends Model
{
    /**
     * obtener el dato de al empresa del asesor
     */
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function empresaAsesor(){
        return $this->belongsTo('App\EmpresaAsesore','empresaAsesor_id');
    }
    
    public function empresasCliente(){
        return $this->hasMany('App\EmpresaCliente','asesor_id');
    }
}
