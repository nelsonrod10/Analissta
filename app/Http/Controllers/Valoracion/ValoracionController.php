<?php

namespace App\Http\Controllers\Valoracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Valoracion\PeligroController;
use App\Http\Controllers\MedidasIntervencion\MedidasIntervencionController;
use App\Http\Controllers\Valoracion\CortoPlazoController;
use App\Http\Controllers\Valoracion\LargoPlazoController;
use App\Http\Controllers\Valoracion\RequisitosLegalesController;
use App\EmpresaCliente;
use App\Actividade;
use App\Peligro;
use App\Http\Controllers\helpers;
use App\CortoPlazo;
use App\LargoPlazo;

class ValoracionController extends Controller
{
    public function cargarIdentificacionPeligro($idActividad){
        $actividad = Actividade::find($idActividad);
        return view('analissta.Valoracion.identificacionPeligro')->with(['actividad'=>$actividad]);
    }
    
    public function cargarEfectosPeligro($idActividad){
        $actividad = Actividade::find($idActividad);
        return view('analissta.Valoracion.efectosPeligro')->with(['actividad'=>$actividad]);
    }
    
    public function cargarControlesPeligro($idActividad){
        $actividad = Actividade::find($idActividad);
        return view('analissta.Valoracion.controlesPeligro')->with(['actividad'=>$actividad]);
    }
    
    public function cargarValoracionPeligro($idActividad){
        $actividad = Actividade::find($idActividad);
        return view('analissta.Valoracion.valoracionPeligro')->with(['actividad'=>$actividad]);
    }
    
    public function cargarCriteriosPeligro($idActividad){
        $actividad = Actividade::find($idActividad);
        return view('analissta.Valoracion.criteriosPeligro')->with(['actividad'=>$actividad]);
    }
    
    public function cargarMedidasIntervencionPeligro($idActividad){
        $actividad = Actividade::find($idActividad);
        return view('analissta.Valoracion.medidasIntervencion')->with(['actividad'=>$actividad]);
    }
    
    public function cargarConfigurarMedidaIntervencion($idActividad,$conteo) {
        $actividad = Actividade::find($idActividad);
        return view('analissta.Valoracion.programarMedidaIntervencion')->with(['actividad'=>$actividad,'conteo'=>$conteo]);
    }
    
    public function finalizarValoracion($idActividad) {
        PeligroController::unsetSessionVariables();
        MedidasIntervencionController::unsetSessionVariables();
        return redirect()->route('ver-actividad-proceso',['id'=>$idActividad]);
    }
    
    public function cancelarValoracion($idActividad) {
        //$medidasIntervencion = new MedidasIntervencionController();
        //$medidasIntervencion->eliminarTodasMedidasIntervencion();
        $cortoPlazo = new CortoPlazoController();
        $largoPlazo = new LargoPlazoController();
        $cortoPlazo->eliminarPGRP();
        $largoPlazo->eliminarPVE();
        RequisitosLegalesController::eliminar_requisitos_legales();
        $peligro = Peligro::find(session('idPeligro'));
        if(isset($peligro->id)){
            $peligro->delete();
        }
        
        PeligroController::unsetSessionVariables();
        MedidasIntervencionController::unsetSessionVariables();
        
        return redirect()->route('ver-actividad-proceso',['id'=>$idActividad]);
    }
    
}
