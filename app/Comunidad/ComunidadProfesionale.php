<?php

namespace App\Comunidad;

use Illuminate\Database\Eloquent\Model;

class ComunidadProfesionale extends Model
{
    protected $guarded=[];
    
    public function Especialidades(){
        return $this->hasMany('App\Comunidad\ComunidadEspecialidadesProfesionale','comProf_id');
    }
    
    public function OtrasEspecialidades(){
        return $this->hasMany('App\Comunidad\ComunidadEspecialidadesProfesionale','comProf_id');
    }
}
