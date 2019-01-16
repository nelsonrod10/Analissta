<?php

namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\Proceso;
use App\Actividade;
use App\ActividadesCalendario;
use App\CapacitacionesCalendario;
use App\InspeccionesCalendario;
use App\CortoPlazo;
use App\LargoPlazo;

use App\PlanesGestion\PgrpFisico;
use App\PlanesGestion\PgrpGenerale;
use App\PlanesGestion\PgrpSeguridade;

use App\PlanesGestion\PveFisico;
use App\PlanesGestion\PveGenerale;
use App\PlanesGestion\PveSeguridade;

class ActividadeController extends Controller
{
    public function getFrmCrearActividad($idProceso){
        $proceso = Proceso::find($idProceso);
        return view('analissta.ProcesosActividades.frmCrearActividad')->with(['proceso'=>$proceso]);
    }
    
    private function validarDatos(){
        $data = request()->validate([
            'nombre'      => 'string|required', 
            'descripcion' => 'string|required',
            'equipos'     => 'string|required',
            'rutina'      => 'string|required',
            'interna'     => 'string',
            'externa'     => 'string',
        ],[
            'nombre.required' => 'Debe ingresar el nombre de la actividad',
            'nombre.string' => 'Nombre de actividad no valido',
            'descripcion.required' => 'Debe ingresar la descripción de la actividad',
            'descripcion.string' => 'Texto de la descripción no valido',
            'equipos.required' => 'Debe ingresar los equipos y herramientas de la actividad',
            'equipos.string' => 'Texto de herramientas y equipos no valido',
        ]);
        
        return $data;
    }
    
    public function crearActividad($idProceso){
        $data = $this->validarDatos();
        $dataInterna = isset($data['interna'])? $data['interna']:"";
        $dataExterna = isset($data['externa'])? $data['externa']:"";
        Actividade::create([
           'sistema_id'         => session('sistema')->id,
           'proceso_id'         => $idProceso,
           'nombre'             => $data['nombre'], 
           'descripcion'        => $data['descripcion'], 
           'equipos'            => $data['equipos'],  
           'rutinaria'          => $data['rutina'], 
           'interna'            => $dataInterna, 
           'externa'            => $dataExterna
        ]);
        
        return redirect()->route('procesos-actividades',['sistema'=>session('sistema')]);
    }
    
    public function getFrmActualizarActividad($nombreProceso,$idActividad){
        $actividad = Actividade::find((int)$idActividad);
        return view('analissta.ProcesosActividades.frmUpdateActividad')->with(['proceso'=>$nombreProceso,'actividad'=>$actividad]);
    }
    
    public function actualizarActividad($idActividad){
        $data = $this->validarDatos();
        $dataInterna = isset($data['interna'])? $data['interna']:"";
        $dataExterna = isset($data['externa'])? $data['externa']:"";
        
        $actividad = Actividade::find($idActividad);
        $actividad->nombre             = $data['nombre'];
        $actividad->descripcion        = $data['descripcion']; 
        $actividad->equipos            = $data['equipos'];  
        $actividad->rutinaria          = $data['rutina']; 
        $actividad->interna            = $dataInterna; 
        $actividad->externa            = $dataExterna;
        $actividad->save();           
        return redirect()->route('ver-actividad-proceso',['id'=>$idActividad]);
    }
    
    public function verActividad($id){
        $actividad = Actividade::find($id);
        return view('analissta.ProcesosActividades.actividadProceso')->with(['actividad'=>$actividad]);
    }
    
    public function eliminarActividad($id){
        $actividad = Actividade::find($id);
        $this->eliminarCalendarioActividades($actividad);
        $this->eliminarCalendarioCapacitaciones($actividad);
        $this->eliminarCalendarioInspecciones($actividad);
        $this->verificarEliminarPGRP($actividad);
        $this->verificarEliminarPVE($actividad);
        $actividad->delete();
        return back();
    }
    
    private function eliminarCalendarioActividades($actividadProceso){
        foreach ($actividadProceso->peligros as $peligro){
            foreach($peligro->actividadesValoracion as $actividad){
                $calendario = ActividadesCalendario::where('actividad_id',$actividad->id)
                        ->where('sistema_id', session('sistema')->id)
                        ->where('tipo','valoracion')
                        ->delete();
                //echo "<p>Actividad ".$calendario."</p>";
            }
                
        }    
    }
    
    private function eliminarCalendarioCapacitaciones($actividadProceso){
        foreach ($actividadProceso->peligros as $peligro){
            foreach($peligro->capacitacionesValoracion as $capacitacion){
                $calendario = CapacitacionesCalendario::where('capacitacion_id',$capacitacion->id)
                        ->where('sistema_id', session('sistema')->id)
                        ->where('tipo','valoracion')
                        ->delete();
                //echo "<p>Capacitacion ".$calendario."</p>";
            }
                
        }    
    }
    
    private function eliminarCalendarioInspecciones($actividadProceso){
        foreach ($actividadProceso->peligros as $peligro){
            foreach($peligro->inspeccionesValoracion as $inspeccion){
                $calendario = InspeccionesCalendario::where('inspeccion_id',$inspeccion->id)
                        ->where('sistema_id', session('sistema')->id)
                        ->where('tipo','valoracion')
                        ->delete();
                //echo "<p> Inspeccion ".$calendario."</p>";
            }
                
        }    
    }
    
    private function verificarEliminarPGRP($actividad){
        foreach ($actividad->peligros as $peligro){
            if($peligro->cortoPlazo){
                $pgrp = CortoPlazo::where('pgrp',"Si")
                ->where('pgrp_id', $peligro->cortoPlazo->pgrp_id)
                ->where('pgrp_table',$peligro->cortoPlazo->pgrp_table)
                ->get();
                if(count($pgrp) == 1){$this->eliminarPGRP($pgrp);}
                if(count($pgrp)>0){$pgrp[0]->delete();}
                
            }
        }
    }
    
    private function eliminarPGRP($pgrp){
        switch ($pgrp[0]->pgrp_table) {
            case "pgrp_fisicos":
                $pgrpBuscado=PgrpFisico::find($pgrp[0]->pgrp_id);
            break;
            case "pgrp_generales":
                $pgrpBuscado=PgrpGenerale::find($pgrp[0]->pgrp_id);
            break;
            case "pgrp_seguridades":
                $pgrpBuscado=PgrpSeguridade::find($pgrp[0]->pgrp_id);
            break;
        }
        if($pgrpBuscado){
            $pgrpBuscado->delete();
        }
    }
    
    private function verificarEliminarPVE($actividad){
        foreach ($actividad->peligros as $peligro){
            if($peligro->largoPlazo){
                $pve = LargoPlazo::where('pve',"Si")
                ->where('pve_id', $peligro->largoPlazo->pve_id)
                ->where('pve_table',$peligro->largoPlazo->pve_table)
                ->get();
                if(count($pve) == 1){$this->eliminarPVE($pve);}
                if(count($pve)>0){$pve[0]->delete();}
                
            }
        }
    }
    
    private function eliminarPVE($pve){
        switch ($pve[0]->pve_table) {
            case "pve_fisicos":
                $pveBuscado=PveFisico::find($pve[0]->pve_id);
            break;
            case "pve_generales":
                $pveBuscado=PveGenerale::find($pve[0]->pve_id);
            break;
            case "pve_seguridades":
                $pveBuscado=PveSeguridade::find($pve[0]->pve_id);
            break;
        }
        if($pveBuscado){
            $pveBuscado->delete();
        }
    }
    
    public function detallesPeligro($idActividad,$idPeligro){
        $actividad = Actividade::find($idActividad);
        return view('analissta.ProcesosActividades.peligroActividad')->with(['actividad'=>$actividad,'idPeligro'=>$idPeligro]);
    }
}
