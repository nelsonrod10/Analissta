<?php

namespace App\Empleados;

use Illuminate\Database\Eloquent\Model;

class EvaluacionesMedica extends Model
{
    protected $guarded=[];
    
    public function empleado(){
        return $this->belongsTo('App\Empleado');
    }
}
