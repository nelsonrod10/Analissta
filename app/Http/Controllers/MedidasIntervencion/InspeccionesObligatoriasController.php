<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\InspeccionesObligatoriasSugerida;
use App\InspeccionesCalendario;
use App\Presupuesto;

class InspeccionesObligatoriasController extends Controller
{
    public function verInspeccion($id){
        
        
        $inspeccion = InspeccionesObligatoriasSugerida::where('id',$id)
            ->where('medida','obligatoria')
            ->get();
        return view('analissta.Inspecciones.inspeccionObligatoria')->with(['inspeccion'=>$inspeccion[0]]);
    }
    
    public function eliminarInspeccion($id){
        InspeccionesObligatoriasSugerida::where('id',$id)
            ->where('medida','obligatoria')
            ->delete();
        
        
        
        
        return view('analissta.Inspecciones.calendario');
    }
    
    private function getInspeccion($id){
        $inspeccion = InspeccionesObligatoriasSugerida::where('id',$id)
                ->where('medida','obligatoria')
                ->where('sistema_id',session('sistema')->id)
                ->get();
        return $inspeccion[0];
    }
    
    public function updateDatosGenerales($data){
        
        InspeccionesObligatoriasSugerida::where('id',$data["idInspeccion"])
                ->where('medida','obligatoria')
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
                ->where('tipo','obligatoria')
                ->where('anio',helpers::getCurrentYear())
                ->delete();
        
        InspeccionesObligatoriasSugerida::where('id',$data["idInspeccion"])
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
            "tabla_origen"           =>'inspecciones_obligatorias_sugeridas',
            "origen_id"              =>$data["idInspeccion"],
            "item"                   =>$data["item"],
            "observaciones"          =>$data["observaciones"],
            "valor"             =>$data["valor"],
        ]);
    }
    
    public function cancelarProgramacionInspeccion($id){
        Presupuesto::where('origen_id',$id)
                ->where('sistema_id',session('sistema')->id)
                ->where('tabla_origen','inspecciones_obligatorias_sugeridas')
                ->delete();
        InspeccionesCalendario::where('sistema_id',session('sistema')->id)
                ->where('inspeccion_id',$id)
                ->where('tipo','obligatoria')
                ->delete();
        
        InspeccionesObligatoriasSugerida::where('id',$id)
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
    
    public function finalizarProgramacionInspeccion($id){
        InspeccionesObligatoriasSugerida::where('id',$id)
                ->where('medida','obligatoria')
                ->where('sistema_id',session('sistema')->id)
                ->update([
                    'estado'    => 'Programada',
                ]);
    }
    
    public function ejecucionInspeccion($data){
        InspeccionesCalendario::where('id',$data["idJornada"])
                ->where('sistema_id',session('sistema')->id)
                ->where('inspeccion_id',$data["idInspeccion"])
                ->where('tipo','obligatoria')
                ->update([
                    'ejecutada' => $data["ejecutada"],
                ]);
        $this->ejecucionTotalInspeccion($data);
    }
    
    private function ejecucionTotalInspeccion($data){
        /*total de jornadas programadas/total de jornadas con un si*/
        $totalProgramado = InspeccionesCalendario::where('sistema_id',session('sistema')->id)
                ->where('inspeccion_id',$data["idInspeccion"])
                ->where('tipo','obligatoria')
                ->get();
        
        $totalEjecutado = InspeccionesCalendario::where('sistema_id',session('sistema')->id)
                ->where('inspeccion_id',$data["idInspeccion"])
                ->where('tipo','obligatoria')
                ->where('ejecutada','Si')
                ->get();
        $ejecutado = ($totalEjecutado->count()/$totalProgramado->count())*100;
        
        if($ejecutado > 0 && $ejecutado < 100){
            $estado = "En ejecucion";
        }elseif ($ejecutado == 100) {
            $estado = "Ejecutado";
        }else{$estado = "Programada";}

        InspeccionesObligatoriasSugerida::where('id',$data["idInspeccion"])
                ->where('sistema_id',session('sistema')->id)
                ->where('medida','obligatoria')
                ->update([
                    'ejecucionTotal'    => $ejecutado,
                    'estado'            => $estado
                ]);
    }
    
    public function crearInspeccionObligatoria(){
        $data = request()->validate([
            'nombre'        => 'string|required|unique:inspecciones_obligatorias_sugeridas,nombre',
        ],[
            'nombre.required' => 'Revise el diligenciamiento de todos los campos',
            'nombre.string'   => 'Revise el diligenciamiento de todos los campos',
            'nombre.unique'   => 'Revise el diligenciamiento de todos los campos'
        ]);
        
        $inspeccion = InspeccionesObligatoriasSugerida::create([
            "sistema_id"      =>session('sistema')->id,
            "medida"                 =>'obligatoria',
            "nombre"                 =>$data["nombre"]
        ]);
        
        return $this->verInspeccion($inspeccion->id);
    }
}
