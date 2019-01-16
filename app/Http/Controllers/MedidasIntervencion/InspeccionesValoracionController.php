<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\InspeccionesValoracione;
use App\InspeccionesCalendario;
use App\Presupuesto;

class InspeccionesValoracionController extends Controller
{
    public function verInspeccion($id){
        
        
        $inspeccion = InspeccionesValoracione::where('id',$id)
                        ->where('sistema_id',session('sistema')->id)
                        ->get();
        return view('analissta.Inspecciones.inspeccionValoracion')->with(['inspeccion'=>$inspeccion[0]]);
    }
    
    public function eliminarInspeccion($id){
        InspeccionesValoracione::where('id',$id)
            ->where('sistema_id',session('sistema')->id)    
            ->delete();
        
        
        
        return view('analissta.Inspecciones.calendario');
    }
    
    private function getInspeccion($id){
        $inspeccion = InspeccionesValoracione::where('id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->get();
        return $inspeccion[0];
    }
    
    public function updateDatosGenerales($data){
        
        InspeccionesValoracione::where('id',$data["idInspeccion"])
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'cargo'         => $data["cargo"],
                    'evidencias'    => $data["evidencias"],
                    'observaciones'    => $data["observaciones"],
                ]);
        
        return $this->getInspeccion($data["idInspeccion"]);
    }
    
    public function crearProgramacionInspeccion($data){
        $newArr = array_chunk($data["dataCentros"], 4);
        InspeccionesCalendario::where('sistema_id',session('sistema')->id)
                ->where('inspeccion_id',$data["idInspeccion"])
                ->where('tipo','valoracion')
                ->where('anio',helpers::getCurrentYear())
                ->delete();
        
        InspeccionesValoracione::where('id',$data["idInspeccion"])
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'frecuencia'    => $data["frecuencia"],
                    'estado'         => '',
                    'ejecucionTotal' => 0
                ]);
        
        for($i=0;$i<count($newArr);$i++){
            $textMesInicio = helpers::meses_de_numero_a_texto($newArr[$i][2]);
            $arrJornadas = helpers::calcularJornadasFrecuencia($data["frecuencia"], $newArr[$i][2], $newArr[$i][3]);
            foreach($arrJornadas as $mes=>$semanas){
                $arrSemanas = explode(",", $semanas);
                foreach ($arrSemanas as $semana){
                    InspeccionesCalendario::create([
                       "sistema_id"      =>session('sistema')->id,
                       "centroTrabajo_id"       =>$newArr[$i][0],
                       "inspeccion_id"           =>$data["idInspeccion"],
                       "tipo"                   =>$data["tipo"],
                       "anio"                   =>helpers::getCurrentYear(),
                       "mes_inicio"             =>$textMesInicio,
                       "semana_inicio"          =>$newArr[$i][3], 
                       "mes"                    =>$mes,
                       "semana"                 =>$semana,
                       "responsable"            =>$newArr[$i][1]
                    ]);
                    
                }
            }
        }
        return;
    }
    
    public function crearPresupuestoInspeccion($data){
        Presupuesto::create([
            "sistema_id"      =>session('sistema')->id,
            "tabla_origen"           =>'inspecciones_valoraciones',
            "origen_id"              =>$data["idInspeccion"],
            "item"                   =>$data["item"],
            "observaciones"          =>$data["observaciones"],
            "valor"             =>$data["valor"],
        ]);
    }
    
    public function cancelarProgramacionInspeccion($id){
        Presupuesto::where('origen_id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->where('tabla_origen','inspecciones_valoraciones')
                ->delete();
        InspeccionesCalendario::where('sistema_id',session('sistema')->id)
                ->where('inspeccion_id',$id)
                ->where('tipo','valoracion')
                ->delete();
        
        InspeccionesValoracione::where('id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'cargo'         => null,
                    'evidencias'    => null,
                    'observaciones' => null,
                    'frecuencia'    => 'N/A',
                    'estado'        => '',
                ]);
    }
    
    public function finalizarProgramacionInspeccion($id){
        InspeccionesValoracione::where('id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'estado'    => 'Programada',
                ]);
    }
    
    public function ejecucionInspeccion($data){
        InspeccionesCalendario::where('id',$data["idJornada"])
                ->where('sistema_id',session('sistema')->id)
                ->where('inspeccion_id',$data["idInspeccion"])
                ->where('tipo','valoracion')
                ->update([
                    'ejecutada' => $data["ejecutada"],
                ]);
        $this->ejecucionTotalInspeccion($data);
    }
    
    private function ejecucionTotalInspeccion($data){
        /*total de jornadas programadas/total de jornadas con un si*/
        $totalProgramado = InspeccionesCalendario::where('sistema_id',session('sistema')->id)
                ->where('inspeccion_id',$data["idInspeccion"])
                ->where('tipo','valoracion')
                ->get();
        
        $totalEjecutado = InspeccionesCalendario::where('sistema_id',session('sistema')->id)
                ->where('inspeccion_id',$data["idInspeccion"])
                ->where('tipo','valoracion')
                ->where('ejecutada','Si')
                ->get();
        $ejecutado = ($totalEjecutado->count()/$totalProgramado->count())*100;
        
        if($ejecutado > 0 && $ejecutado < 100){
            $estado = "En ejecucion";
        }elseif ($ejecutado == 100) {
            $estado = "Ejecutado";
        }else{$estado = "Programada";}
        
        InspeccionesValoracione::where('id',$data["idInspeccion"])
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'ejecucionTotal'    => $ejecutado,
                    'estado'            => $estado
                ]);
    }
}
