<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\CapacitacionesObligatoriasSugerida;
use App\CapacitacionesCalendario;
use App\Presupuesto;

class CapacitacionesObligatoriasController extends Controller
{
    public function verCapacitacion($id){
        
        
        $capacitacion = CapacitacionesObligatoriasSugerida::where('id',$id)
            ->where('medida','obligatoria')
            ->get();
        return view('analissta.Capacitaciones.capacitacionObligatoria')->with(['capacitacion'=>$capacitacion[0]]);
    }
    
    public function eliminarCapacitacion($id){
        CapacitacionesObligatoriasSugerida::where('id',$id)
            ->where('medida','obligatoria')
            ->delete();
        
        
        
        
        return view('analissta.Capacitaciones.calendario');
    }
    
    private function getCapacitacion($id){
        $capacitacion = CapacitacionesObligatoriasSugerida::where('id',$id)
                ->where('medida','obligatoria')
                ->where('sistema_id',session('sistema')->id)
                ->get();
        return $capacitacion[0];
    }
    
    public function updateDatosGenerales($data){
        CapacitacionesObligatoriasSugerida::where('id',$data["idCapacitacion"])
                ->where('medida','obligatoria')
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
                ->where('tipo','obligatoria')
                ->where('anio',helpers::getCurrentYear())
                ->delete();
        
        CapacitacionesObligatoriasSugerida::where('id',$data["idCapacitacion"])
                ->where('medida','obligatoria')
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'evaluable'         => $data["evaluable"],
                    'capacitador'    => $data["capacitador"],
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
            'tipo'                  => 'obligatoria',
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
            "tabla_origen"           =>'capacitaciones_obligatorias_sugeridas',
            "origen_id"              =>$data["idCapacitacion"],
            "item"                   =>$data["item"],
            "observaciones"          =>$data["observaciones"],
            "valor"                  =>$data["valor"],
        ]);
    }
    
    public function cancelarProgramacionCapacitacion($id){
        Presupuesto::where('origen_id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->where('tabla_origen','capacitaciones_obligatorias_sugeridas')
                ->delete();
        
        CapacitacionesCalendario::where('sistema_id',session('sistema')->id)
                ->where('capacitacion_id',$id)
                ->where('tipo','obligatoria')
                ->delete();
        
        CapacitacionesObligatoriasSugerida::where('id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->where('medida','obligatoria')
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
        CapacitacionesObligatoriasSugerida::where('id',$id)
                ->where('medida','obligatoria')
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'estado'    => 'Programada',
                ]);
    }
    
    public function ejecucionCapacitacion($data){
        CapacitacionesCalendario::where('id',$data["idJornada"])
                ->where('sistema_id',session('sistema')->id)
                ->where('capacitacion_id',$data["idCapacitacion"])
                ->where('tipo','obligatoria')
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
                ->where('tipo','obligatoria')
                ->get();
        
        $totalEjecutado = CapacitacionesCalendario::where('sistema_id',session('sistema')->id)
                ->where('capacitacion_id',$data["idCapacitacion"])
                ->where('anio',helpers::getCurrentYear())
                ->where('tipo','obligatoria')
                ->where('ejecutada','Si')
                ->get();
        $ejecutado = ($totalEjecutado->count()/$totalProgramado->count())*100;
        
        if($ejecutado > 0 && $ejecutado < 100){
            $estado = "En ejecucion";
        }elseif ($ejecutado == 100) {
            $estado = "Ejecutado";
        }else{$estado = "Programada";}

        CapacitacionesObligatoriasSugerida::where('id',$data["idCapacitacion"])
                ->where('sistema_id',session('sistema')->id)
                ->where('medida','obligatoria')
                ->update([
                    'ejecucionTotal'    => $ejecutado,
                    'estado'            => $estado
                ]);
    }
    
    public function crearCapacitacionObligatoria(){
        $data = request()->validate([
            'nombre'        => 'string|required|unique:capacitaciones_obligatorias_sugeridas,nombre',
        ],[
            'nombre.required' => 'Revise el diligenciamiento de todos los campos',
            'nombre.string'   => 'Revise el diligenciamiento de todos los campos',
            'nombre.unique'   => 'Revise el diligenciamiento de todos los campos'
        ]);
        
        $capacitacion = CapacitacionesObligatoriasSugerida::create([
            "sistema_id"      =>session('sistema')->id,
            "medida"                 =>'obligatoria',
            "nombre"                 =>$data["nombre"]
        ]);
        
        return $this->verCapacitacion($capacitacion->id);
    }
    
    
}





