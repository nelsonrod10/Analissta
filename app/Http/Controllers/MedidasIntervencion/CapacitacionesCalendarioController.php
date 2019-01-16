<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\CapacitacionesCalendario;

class CapacitacionesCalendarioController extends Controller
{
    public function calendarioSemanalCapacitacion($mes, $semana){
        $capacitaciones = CapacitacionesCalendario::where('sistema_id',session('sistema')->id)
            ->where('anio', helpers::getCurrentYear())
            ->where('mes',$mes)
            ->where('semana',$semana)
            ->get();
        
        
        
        
        
        return view('analissta.Capacitaciones.calendario.semanaMes',['capacitaciones'=>$capacitaciones,'mes'=>$mes,'semana'=>$semana]);
    }
    
    public function calendarioMensualCapacitacion($mes){
        $capacitaciones = CapacitacionesCalendario::where('sistema_id',session('sistema')->id)
            ->where('anio', helpers::getCurrentYear())
            ->where('mes',$mes)
            ->get();
        
        
        
        
        return view('analissta.Capacitaciones.calendario.totalMes',['capacitaciones'=>$capacitaciones,'mes'=>$mes]);
    }
}
