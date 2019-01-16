<?php

namespace App\Http\Controllers\PlanesGestion;

use Illuminate\Http\Request;
use App\Http\Controllers\helpers;
use App\Http\Controllers\Controller;
use App\PlanesGestion\PgrpFisico;
use App\PlanesGestion\PgrpSeguridade;
use App\PlanesGestion\PgrpGenerale;
use App\EmpresaCliente;
use App\Http\Controllers\PlanesGestion\PgrpFisicoController;
use App\Http\Controllers\PlanesGestion\PgrpSeguridadController;
use App\Http\Controllers\PlanesGestion\PgrpGeneralController;

class PgrpController extends Controller
{
    public function crearPGRP($peligro){
        $pgrp_id = 0;
        switch ((int)$peligro->clasificacion) {
            case 1: //pgrpFisico
                $pgrp_id = $this->crearPgrpFisico($peligro);
            break;
            case 6: //pgrpSeguridad
                $pgrp_id = $this->crearPgrpSeguridad($peligro);
            break;
            default: //pgrpGeneral
                $pgrp_id = $this->crearPgrpGeneral($peligro);
            break;
        }
        return $pgrp_id;
    }
    
    private function crearPgrpFisico($peligro){
        $existePgrpFisico = PgrpFisico::where('sistema_id',session('sistema')->id)
                ->where('categoria',$peligro->categoria)->get();
        if(count($existePgrpFisico) > 0){
            return $existePgrpFisico[0]->id;
        }
        
        $newPgrp = PgrpFisico::create([
            'anio' => helpers::getCurrentYear(),
            'sistema_id' => session('sistema')->id,
            'categoria' => $peligro->categoria
        ]);
        return $newPgrp->id;
    }
    
    private function crearPgrpSeguridad($peligro){
        $existePgrpSeguridad = PgrpSeguridade::where('sistema_id',session('sistema')->id)
                ->where('categoria',$peligro->categoria)->get();
        if(count($existePgrpSeguridad) > 0){
            return $existePgrpSeguridad[0]->id;
        }
        
        $newPgrp = PgrpSeguridade::create([
            'anio' => helpers::getCurrentYear(),
            'sistema_id' => session('sistema')->id,
            'categoria' => $peligro->categoria
        ]);
        return $newPgrp->id;
    }
    
    private function crearPgrpGeneral($peligro){
        $existePgrpGeneral = PgrpGenerale::where('sistema_id',session('sistema')->id)
                ->where('clasificacion',$peligro->clasificacion)->get();
        if(count($existePgrpGeneral) > 0){
            return $existePgrpGeneral[0]->id;
        }
        
        $newPgrp = PgrpGenerale::create([
            'anio' => helpers::getCurrentYear(),
            'sistema_id' => session('sistema')->id,
            'clasificacion' => $peligro->clasificacion
        ]);
        
        return $newPgrp->id;
    }
    
    public function eliminarPgrpFisicos($id){
        PgrpFisico::find($id)->delete();
        return;
    }
    
    public function eliminarPgrpSeguridades($id){
        PgrpSeguridade::find($id)->delete();
        return;
    }
    
    public function eliminarPgrpGenerales($id){
        PgrpGenerale::find($id)->delete();
        return;
    }
    
    public function principalPgrp(){
        return view('analissta.Pgrp.vistaGeneral');
    }
    
    public function mostrarPgrp($tipo,$id){
        switch ($tipo) {
            case "general":
                $pgrp = PgrpGenerale::find($id);
                $clasificacion = $pgrp->clasificacion;
                break;
            case "seguridad":
                $pgrp = PgrpSeguridade::find($id);
                $clasificacion = 6;
                break;
            default:
                $pgrp = PgrpFisico::find($id);
                $clasificacion = 1;
                break;
        }
        
        return view('analissta.Pgrp.verPgrp')->with(['pgrp'=>$pgrp,'clasificacion'=>$clasificacion]);
    }
    
    public function editarPgrp($clasificacion,$id){
        switch ($clasificacion) {
            case 1:
                $pgrp = PgrpFisico::find($id);
                break;
            case 6:
                $pgrp = PgrpSeguridade::find($id);
                break;
            default:
                $pgrp = PgrpGenerale::find($id);
                break;
        }
        
        
        
        return view('analissta.Pgrp.editarPgrp')->with(['pgrp'=>$pgrp,'clasificacion'=>$clasificacion]);
    }
    
    
    public function datosPgrp(){
        $data = request()->validate([
            'idPgrp'          => 'string|required',
            'clasificacion'   => 'string|required',
        ],[
            'idPgrp.required'         => 'Diligencie todos los campos',
            'clasificacion.required'  => 'Diligencie todos los campos',
        ]);
        
        switch ($data['clasificacion']) {
            case 1:
                $tipo="fisico";
                $controller = new PgrpFisicoController();
                $controller->guardarData();
                break;
            case 6:
                $tipo="seguridad";
                $controller = new PgrpSeguridadController();
                $controller->guardarData();
                break;
            default:
                $tipo="general";
                $controller = new PgrpGeneralController();
                $controller->guardarData();
                break;
        }
        return redirect()->route('pgrp',['tipo'=>$tipo,'id'=>$data['idPgrp']]); 
    }
}
