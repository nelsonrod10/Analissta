<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmpresaAsesore extends Model
{
    /**
     * Obtener los asesores de una empresa asesora
     */
    public function asesores(){
        return $this->hasMany('App\Asesore','empresaAsesor_id');
    }
   
}
