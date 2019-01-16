<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\CapacitacionesValoracione;
use App\CapacitacionesCalendario;
use App\Presupuesto;
class CapacitacionesValoracionController extends Controller
{
    public function verCapacitacion($id){
        
        
        $capacitacion = CapacitacionesValoracione::where('id',$id)
                        ->where('sistema_id',session('sistema')->id)
                        ->get();
        return view('analissta.Capacitaciones.capacitacionValoracion')->with(['capacitacion'=>$capacitacion[0]]);
    }
    
    private function getCapacitacion($id){
        $capacitacion = CapacitacionesValoracione::where('id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->get();
        return $capacitacion[0];
    }
    
    public function updateDatosGenerales($data){
        CapacitacionesValoracione::where('id',$data["idCapacitacion"])
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'cargo'         => $data["cargo"],
                    'evidencias'    => $data["evidencias"],
                    'observaciones' => $data["observaciones"],
                    'temario'       => $data["temario"],
                ]);
        
        
        return $this->getCapacitacion($data["idCapacitacion"]);
    }
    
    public function crearProgramacionCapacitacion($data){
        CapacitacionesCalendario::where('sistema_id',session('sistema')->id)
                ->where('capacitacion_id',$data["idCapacitacion"])
                ->where('tipo','valoracion')
                ->where('anio',helpers::getCurrentYear())
                ->delete();
        
        CapacitacionesValoracione::where('id',$data["idCapacitacion"])
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'evaluable'         => $data["evaluable"],
                    'capacitador'       => $data["capacitador"],
                    'estado'         => '',
                    'ejecucionTotal' => 0   
                ]);
        
        return $this->getCapacitacion($data["idCapacitacion"]);
    }
    
    public function crearJornadaCapacitacion($data){
        CapacitacionesCalendario::create([
            'sistema_id'     => session('sistema')->id,
            'centroTrabajo_id'      => $data["centro"],
            'capacitacion_id'       => $data["idCapacitacion"],
            'responsable'           => $data["responsable"],
            'tipo'                  => 'valoracion',
            'anio'                  => helpers::getCurrentYear(),
            'mes'                   => helpers::meses_de_numero_a_texto($data["mes"]),
            'semana'                => $data["semana"],
            'poblacion_objetivo'    => $data["poblacion"],
            'invitados'             => $data["invitados"],
            'asistentes'            => 0,
            'duracion'              => 0,
        ]);
        
        return $this->getCapacitacion($data["idCapacitacion"]);
    }
    
    public function crearPresupuestoCapacitacion($data){
        Presupuesto::create([
            "sistema_id"      =>session('sistema')->id,
            "tabla_origen"           =>'capacitaciones_valoraciones',
            "origen_id"              =>$data["idCapacitacion"],
            "item"                   =>$data["item"],
            "observaciones"          =>$data["observaciones"],
            "valor"                  =>$data["valor"],
        ]);
    }
    
    public function cancelarProgramacionCapacitacion($id){
        Presupuesto::where('origen_id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->where('tabla_origen','capacitaciones_valoraciones')
                ->delete();
        
        CapacitacionesCalendario::where('sistema_id',session('sistema')->id)
                ->where('capacitacion_id',$id)
                ->where('tipo','valoracion')
                ->delete();
        
        CapacitacionesValoracione::where('id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'cargo'         => null,
                    'evidencias'    => null,
                    'observaciones' => null,
                    'temario'       => null,
                    'capacitador'   => '',
                    'evaluable'     => '',
                    'estado'        => '',
                ]);
    }
    
    public function finalizarProgramacionCapacitacion($id){
        CapacitacionesValoracione::where('id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'estado'    => 'Programada',
                ]);
    }
    
    public function ejecucionCapacitacion($data){
        CapacitacionesCalendario::where('id',$data["idJornada"])
                ->where('sistema_id',session('sistema')->id)
                ->where('capacitacion_id',$data["idCapacitacion"])
                ->where('tipo','valoracion')
                ->update([
                    'ejecutada' => $data["ejecutada"],
                    'asistentes' => $data["asistentes"],
                    'duracion' => $data["duracion"],
                ]);
        $this->ejecucionTotalCapacitacion($data);
    }
    
    private function ejecucionTotalCapacitacion($data){
        /*total de jornadas programadas/total de jornadas con un si*/
        $totalProgramado = CapacitacionesCalendario::where('sistema_id',session('sistema')->id)
                ->where('capacitacion_id',$data["idCapacitacion"])
                ->where('anio',helpers::getCurrentYear())
                ->where('tipo','valoracion')
                ->get();
        
        $totalEjecutado = CapacitacionesCalendario::where('sistema_id',session('sistema')->id)
                ->where('capacitacion_id',$data["idCapacitacion"])
                ->where('anio',helpers::getCurrentYear())
                ->where('tipo','valoracion')
                ->where('ejecutada','Si')
                ->get();
        $ejecutado = ($totalEjecutado->count()/$totalProgramado->count())*100;
        
        if($ejecutado > 0 && $ejecutado < 100){
            $estado = "En ejecucion";
        }elseif ($ejecutado == 100) {
            $estado = "Ejecutado";
        }else{$estado = "Programada";}

        CapacitacionesValoracione::where('id',$data["idCapacitacion"])
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'ejecucionTotal'    => $ejecutado,
                    'estado'            => $estado
                ]);
    }
}
