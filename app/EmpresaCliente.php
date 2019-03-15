<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\helpers;

class EmpresaCliente extends Model
{
    protected $guarded = [];
    
    public function sistemaGestion(){
        return $this->hasMany('App\Sistema','empresaCliente_id');
    }
    
    public function centrosTrabajo(){
        return $this->hasMany('App\CentrosTrabajo','empresaCliente_id');
    }
    
    public function usuarios(){
        return $this->hasMany('App\Usuario','empresaCliente_id');
    }
    
    public function resultadosAnualesSistema(){
        return $this->hasMany('App\ResultadosAnualesSistema','empresaCliente_id');
    }
    
    public function resultadosMensualesSistema(){
        return $this->hasMany('App\ResultadosMensualesSistema','empresaCliente_id');
    }
    
    public function empleados(){
        return $this->hasMany('App\Empleado','empresaCliente_id');
    }
    
    public function empleadosSinFechaDeIngreso(){
        return $this->hasMany('App\Empleado','empresaCliente_id')->where("fecha_ingreso",null);
    }
    
    public function evaluacionesMedicasAnioActual(){
        return $this->hasMany('App\Empleados\EvaluacionesMedica','empresaCliente_id')->where('anio_sugerido',helpers::getCurrentYear())->orderBy('mes_sugerido');
    }
    
}
