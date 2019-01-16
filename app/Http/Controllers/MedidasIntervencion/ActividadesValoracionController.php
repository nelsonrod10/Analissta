<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\ActividadesValoracione;
use App\ActividadesCalendario;
use App\Presupuesto;

class ActividadesValoracionController extends Controller
{
    public function verActividad($id){
        $actividad = ActividadesValoracione::where('id',$id)
                        ->where('sistema_id',session('sistema')->id)
                        ->get();
        return view('analissta.Actividades.actividadValoracion')->with(['actividad'=>$actividad[0]]);
    }
    
    public function eliminarActividad($id){
        ActividadesValoracione::where('id',$id)
            ->where('sistema_id',session('sistema')->id)    
            ->delete();
        return view('analissta.Actividades.calendario');
    }
    
    private function getActividad($id){
        $actividad = ActividadesValoracione::where('id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->get();
        
        return $actividad[0];
    }
    
    public function updateDatosGenerales($data){
        
        ActividadesValoracione::where('id',$data["idActividad"])
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'cargo'         => $data["cargo"],
                    'evidencias'    => $data["evidencias"],
                    'observaciones'    => $data["observaciones"],
                ]);
        return $this->getActividad($data["idActividad"]);
    }
    
    public function crearProgramacionActividad($data){
        $newArr = array_chunk($data["dataCentros"], 4);
        ActividadesCalendario::where('sistema_id',session('sistema')->id)
                ->where('actividad_id',$data["idActividad"])
                ->where('tipo','valoracion')
                ->where('anio',helpers::getCurrentYear())
                ->delete();
        
        ActividadesValoracione::where('id',$data["idActividad"])
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
                    ActividadesCalendario::create([
                       "sistema_id"      =>session('sistema')->id,
                       "centroTrabajo_id"       =>$newArr[$i][0],
                       "actividad_id"           =>$data["idActividad"],
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
    
    public function crearPresupuestoActividad($data){
        Presupuesto::create([
            "sistema_id"      =>session('sistema')->id,
            "tabla_origen"           =>'actividades_valoraciones',
            "origen_id"              =>$data["idActividad"],
            "item"                   =>$data["item"],
            "observaciones"          =>$data["observaciones"],
            "valor"             =>$data["valor"],
        ]);
    }
    
    public function cancelarProgramacionActividad($id){
        Presupuesto::where('origen_id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->where('tabla_origen','actividades_valoraciones')
                ->delete();
        ActividadesCalendario::where('sistema_id',session('sistema')->id)
                ->where('actividad_id',$id)
                ->where('tipo','valoracion')
                ->delete();
        
        ActividadesValoracione::where('id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'cargo'         => null,
                    'evidencias'    => null,
                    'observaciones' => null,
                    'frecuencia'    => 'N/A',
                    'estado'        => '',
                ]);
    }
    
    public function finalizarProgramacionActividad($id){
        ActividadesValoracione::where('id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'estado'    => 'Programada',
                ]);
    }
    
    public function ejecucionActividad($data){
        ActividadesCalendario::where('id',$data["idJornada"])
                ->where('sistema_id',session('sistema')->id)
                ->where('actividad_id',$data["idActividad"])
                ->where('tipo','valoracion')
                ->update([
                    'ejecutada' => $data["ejecutada"],
                ]);
        $this->ejecucionTotalActividad($data);
    }
    
    private function ejecucionTotalActividad($data){
        /*total de jornadas programadas/total de jornadas con un si*/
        $totalProgramado = ActividadesCalendario::where('sistema_id',session('sistema')->id)
                ->where('actividad_id',$data["idActividad"])
                ->where('tipo','valoracion')
                ->get();
        
        $totalEjecutado = ActividadesCalendario::where('sistema_id',session('sistema')->id)
                ->where('actividad_id',$data["idActividad"])
                ->where('tipo','valoracion')
                ->where('ejecutada','Si')
                ->get();
        $ejecutado = ($totalEjecutado->count()/$totalProgramado->count())*100;
        
        if($ejecutado > 0 && $ejecutado < 100){
            $estado = "En ejecucion";
        }elseif ($ejecutado == 100) {
            $estado = "Ejecutado";
        }else{$estado = "Programada";}
        
        ActividadesValoracione::where('id',$data["idActividad"])
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'ejecucionTotal'    => $ejecutado,
                    'estado'            => $estado
                ]);
    }
}
