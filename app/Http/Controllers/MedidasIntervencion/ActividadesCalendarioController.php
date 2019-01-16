<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\ActividadesCalendario;

class ActividadesCalendarioController extends Controller
{
    public function calendarioSemanalActividad($mes, $semana){
        $actividades = ActividadesCalendario::where('sistema_id',session('sistema')->id)
            ->where('anio', helpers::getCurrentYear())
            ->where('mes',$mes)
            ->where('semana',$semana)
            ->get();
        return view('analissta.Actividades.calendario.semanaMes',['actividades'=>$actividades,'mes'=>$mes,'semana'=>$semana]);
    }
    
    
    public function calendarioMensualActividad($mes){
        $actividades = ActividadesCalendario::where('sistema_id',session('sistema')->id)
            ->where('anio', helpers::getCurrentYear())
            ->where('mes',$mes)
            ->get();
        return view('analissta.Actividades.calendario.totalMes',['actividades'=>$actividades,'mes'=>$mes]);
    }
}
