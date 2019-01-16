<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\InspeccionesCalendario;

class InspeccionesCalendarioController extends Controller
{
    public function calendarioSemanalInspeccion($mes, $semana){
        $inspecciones = InspeccionesCalendario::where('sistema_id',session('sistema')->id)
            ->where('anio', helpers::getCurrentYear())
            ->where('mes',$mes)
            ->where('semana',$semana)
            ->get();
        
        
        
        
        
        return view('analissta.Inspecciones.calendario.semanaMes',['inspecciones'=>$inspecciones,'mes'=>$mes,'semana'=>$semana]);
    }
    
    public function calendarioMensualInspeccion($mes){
        $inspecciones = InspeccionesCalendario::where('sistema_id',session('sistema')->id)
            ->where('anio', helpers::getCurrentYear())
            ->where('mes',$mes)
            ->get();
        
        
        
        
        return view('analissta.Inspecciones.calendario.totalMes',['inspecciones'=>$inspecciones,'mes'=>$mes]);
    }
}
