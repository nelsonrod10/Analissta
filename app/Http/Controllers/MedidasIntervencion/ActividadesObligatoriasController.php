<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\ActividadesObligatoriasSugerida;
use App\ActividadesCalendario;
use App\Presupuesto;

class ActividadesObligatoriasController extends Controller
{
    public function verActividad($id){
        
        
        $actividad = ActividadesObligatoriasSugerida::where('id',$id)
            ->where('medida','obligatoria')
            ->get();
        return view('analissta.Actividades.actividadObligatoria')->with(['actividad'=>$actividad[0]]);
    }
    
    public function eliminarActividad($id){
        ActividadesObligatoriasSugerida::where('id',$id)
            ->where('medida','obligatoria')
            ->delete();
        return view('analissta.Actividades.calendario');
    }
    
    private function getActividad($id){
        $actividad = ActividadesObligatoriasSugerida::where('id',$id)
                ->where('medida','obligatoria')
                ->where('sistema_id',session('sistema')->id)
                ->get();
        return $actividad[0];
    }
    
    public function updateDatosGenerales($data){
        
        ActividadesObligatoriasSugerida::where('id',$data["idActividad"])
                ->where('medida','obligatoria')
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
                ->where('tipo','obligatoria')
                ->where('anio',helpers::getCurrentYear())
                ->delete();
        
        ActividadesObligatoriasSugerida::where('id',$data["idActividad"])
                ->where('medida','obligatoria')
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'frecuencia'     => $data["frecuencia"],
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
            "tabla_origen"           =>'actividades_obligatorias_sugeridas',
            "origen_id"              =>$data["idActividad"],
            "item"                   =>$data["item"],
            "observaciones"          =>$data["observaciones"],
            "valor"             =>$data["valor"],
        ]);
    }
    
    public function cancelarProgramacionActividad($id){
        Presupuesto::where('origen_id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->where('tabla_origen','actividades_obligatorias_sugeridas')
                ->delete();
        ActividadesCalendario::where('sistema_id',session('sistema')->id)
                ->where('actividad_id',$id)
                ->where('tipo','obligatoria')
                ->delete();
        
        ActividadesObligatoriasSugerida::where('id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->where('medida','obligatoria')
                ->update([
                    'cargo'         => null,
                    'evidencias'    => null,
                    'observaciones' => null,
                    'frecuencia'    => 'N/A',
                    'estado'        => '',
                ]);
                
    }
    
    public function finalizarProgramacionActividad($id){
        ActividadesObligatoriasSugerida::where('id',$id)
                ->where('medida','obligatoria')
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'estado'    => 'Programada',
                ]);
    }
    
    public function ejecucionActividad($data){
        ActividadesCalendario::where('id',$data["idJornada"])
                ->where('sistema_id',session('sistema')->id)
                ->where('actividad_id',$data["idActividad"])
                ->where('tipo','obligatoria')
                ->update([
                    'ejecutada' => $data["ejecutada"],
                ]);
        $this->ejecucionTotalActividad($data);
    }
    
    private function ejecucionTotalActividad($data){
        /*total de jornadas programadas/total de jornadas con un si*/
        $totalProgramado = ActividadesCalendario::where('sistema_id',session('sistema')->id)
                ->where('actividad_id',$data["idActividad"])
                ->where('tipo','obligatoria')
                ->get();
        
        $totalEjecutado = ActividadesCalendario::where('sistema_id',session('sistema')->id)
                ->where('actividad_id',$data["idActividad"])
                ->where('tipo','obligatoria')
                ->where('ejecutada','Si')
                ->get();
        $ejecutado = ($totalEjecutado->count()/$totalProgramado->count())*100;
        
        if($ejecutado > 0 && $ejecutado < 100){
            $estado = "En ejecucion";
        }elseif ($ejecutado == 100) {
            $estado = "Ejecutado";
        }else{$estado = "Programada";}

        ActividadesObligatoriasSugerida::where('id',$data["idActividad"])
                ->where('sistema_id',session('sistema')->id)
                ->where('medida','obligatoria')
                ->update([
                    'ejecucionTotal'    => $ejecutado,
                    'estado'            => $estado
                ]);
    }
    
    public function crearActividadObligatoria(){
        
        $data = request()->validate([
            'nombre'        => 'string|required|unique:actividades_obligatorias_sugeridas,nombre',
        ],[
            'nombre.required' => 'Revise el diligenciamiento de todos los campos',
            'nombre.string'   => 'Revise el diligenciamiento de todos los campos',
            'nombre.unique'   => 'Revise el diligenciamiento de todos los campos'
        ]);
        
        $actividad = ActividadesObligatoriasSugerida::create([
            "sistema_id"     =>session('sistema')->id,
            "medida"         =>'obligatoria',
            "nombre"         =>$data["nombre"]
        ]);
        
        return $this->verActividad($actividad->id);
    }
}
