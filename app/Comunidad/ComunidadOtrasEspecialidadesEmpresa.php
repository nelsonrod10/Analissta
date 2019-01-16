<?php

namespace App\Comunidad;

use Illuminate\Database\Eloquent\Model;

class ComunidadOtrasEspecialidadesEmpresa extends Model
{
    protected $guarded=[];
    
    public function Empresa(){
        return $this->belongsTo('App\Comunidad\ComunidadEmpresa','comEmp_id');
    }
}
