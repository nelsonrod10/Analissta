<?php

namespace App\Comunidad;

use Illuminate\Database\Eloquent\Model;

class ComunidadEmpresa extends Model
{
    protected $guarded=[];
    
    public function Especialidades(){
        return $this->hasMany('App\Comunidad\ComunidadEspecialidadesEmpresa','comEmp_id');
    }
    
    public function OtrasEspecialidades(){
        return $this->hasMany('App\Comunidad\ComunidadOtrasEspecialidadesEmpresa','comEmp_id');
    }
}
