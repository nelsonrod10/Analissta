<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\InspeccionesObligatoriasSugerida;
use App\InspeccionesValoracione;
use App\Http\Controllers\MedidasIntervencion\InspeccionesObligatoriasController;
use App\Http\Controllers\MedidasIntervencion\InspeccionesSugeridasController;
use App\Http\Controllers\MedidasIntervencion\InspeccionesValoracionController;

class InspeccionesController extends Controller
{
    private function getObjetoInspeccion($id,$tipo){
        
        switch ($tipo) {
            case ($tipo === 'obligatoria' || $tipo === 'sugerida'):
                $inspeccion = InspeccionesObligatoriasSugerida::where('id',$id)
                        ->where('sistema_id',session('sistema')->id)
                        ->where('medida',$tipo)
                        ->get();
                break;
            case 'valoracion':
                $inspeccion = InspeccionesValoracione::where('id',$id)
                        ->where('sistema_id',session('sistema')->id)
                        ->get();
                
                break;
            //falta meter la opcion de hallazgos
        }
        return $inspeccion;
    }
    
    public function iniciarProgramarInspeccion($id,$tipo){
        /*$tipo => obligatoria, sugerida,valoracion, hallazgo*/
        
        
        $inspeccion = $this->getObjetoInspeccion($id,$tipo);
        
        return view('analissta.Inspecciones.Programacion.datosGenerales')->with(['inspeccion'=>$inspeccion[0],'tipoInspeccion'=>$tipo]);
    }
    
    public function programacionInspeccion($id,$tipo){
        /*$tipo => obligatoria, sugerida,valoracion, hallazgo*/
        
        
        $inspeccion = $this->getObjetoInspeccion($id,$tipo);
        return view('analissta.Inspecciones.Programacion.programacion')->with(['inspeccion'=>$inspeccion[0],'tipoInspeccion'=>$tipo]);
    }
    
    public function presupuestoInspeccion($id,$tipo){
        /*$tipo => obligatoria, sugerida,valoracion, hallazgo*/
        
        
        $inspeccion = $this->getObjetoInspeccion($id,$tipo);
        return view('analissta.Inspecciones.Programacion.presupuesto')->with(['inspeccion'=>$inspeccion[0],'tipoInspeccion'=>$tipo]);
    }
    
    public function datosGeneralesInspeccion(){
        /*$tipo => obligatoria, sugerida,valoracion, hallazgo*/
        $data = request()->validate([
            'tipo'          => 'required',
            'idInspeccion'   => 'required',
            'cargo'         => 'string|required',
            'evidencias'    => 'string|required',
            'observaciones' => 'string|required',
        ],[
            'tipo.required' => 'Revise el diligenciamiento de todos los campos',
            'idInspeccion.required' => 'Revise el diligenciamiento de todos los campos',
            'cargo.required' => 'Debe indicar el cargo responsable de la inspeccion',
            'evidencias.required' => 'Debe indicar que evidencias se van manejar',
            'observaciones.required' => 'Señale las observaciones que se deben tener en cuenta',
        ]);
        
        switch ($data["tipo"]) {
            case 'obligatoria':
                $objInspeccion = new InspeccionesObligatoriasController();
                $inspeccion= $objInspeccion->updateDatosGenerales($data);
                break;
            case 'sugerida':
                $objInspeccion = new InspeccionesSugeridasController();
                $inspeccion= $objInspeccion->updateDatosGenerales($data);
                break;
            case 'valoracion':
                $objInspeccion = new InspeccionesValoracionController();
                $inspeccion= $objInspeccion->updateDatosGenerales($data);
                break;
            //falta meter la opcion de hallazgos
        }
        
        return redirect()->route('ver-programacion-inspeccion',['id'=>$data["idInspeccion"],'tipo'=>$data["tipo"]]);
    }
    
    public function guardarProgramacionInspeccion(){
        $data = request()->validate([
            'tipo'          => 'required',
            'idInspeccion'   => 'required',
            'frecuencia'    => 'string|required',
            'dataCentros'   => 'array|required',
        ],[
            'tipo.required'         => 'Revise el diligenciamiento de todos los campos',
            'idInspeccion.required'  => 'Revise el diligenciamiento de todos los campos',
            'frecuencia.required'   => 'Debe seleccionar una frecuencia de ejecución',
            'dataCentros.required'  => 'Debe seleccionar por lo menos un centro de trabajo',
        ]);
        switch ($data["tipo"]) {
            case 'obligatoria':
                $objInspeccion = new InspeccionesObligatoriasController();
                $inspeccion= $objInspeccion->crearProgramacionInspeccion($data);
                break;
            case 'sugerida':
                $objInspeccion = new InspeccionesSugeridasController();
                $inspeccion= $objInspeccion->crearProgramacionInspeccion($data);
                break;
            case 'valoracion':
                $objInspeccion = new InspeccionesValoracionController();
                $inspeccion= $objInspeccion->crearProgramacionInspeccion($data);
                break;
            //falta meter la opcion de hallazgos
        }
        
        return redirect()->route('programacion-presupuesto-inspeccion',['id'=>$data["idInspeccion"],'tipo'=>$data["tipo"]]);
        
    }
    
    public function guardarPresupuestoInspeccion(){
        $data = request()->validate([
            'tipo'              => 'required',
            'idInspeccion'       => 'required',
            'origen'       => 'required',
            'item'              => 'string|required',
            'observaciones'     => 'string|required',
            'valor'             => 'numeric|required',
        ],[
            'tipo.required'           => 'Revise el diligenciamiento de todos los campos',
            'idInspeccion.required'    => 'Revise el diligenciamiento de todos los campos',
            'item.required'           => 'Debe describir el item del presupuesto',
            'observaciones.required'  => 'Debe describir las observaciones sobre el presupuesto',
            'valor.required'          => 'Debe ingresar el valor',
        ]);
        switch ($data["tipo"]) {
            case 'obligatoria':
                $objInspeccion = new InspeccionesObligatoriasController();
                $inspeccion= $objInspeccion->crearPresupuestoInspeccion($data);
                break;
            case 'sugerida':
                $objInspeccion = new InspeccionesSugeridasController();
                $inspeccion= $objInspeccion->crearPresupuestoInspeccion($data);
                break;
            case 'valoracion':
                $objInspeccion = new InspeccionesValoracionController();
                $inspeccion= $objInspeccion->crearPresupuestoInspeccion($data);
                break;
            //falta meter la opcion de hallazgos
        }
        
        return redirect()->route($data["origen"],['id'=>$data["idInspeccion"],'tipo'=>$data["tipo"]]);
    }
    
    public function finalizarProgramarInspeccion($id,$tipo){
        switch ($tipo) {
            case 'obligatoria':
                $objInspeccion = new InspeccionesObligatoriasController();
                $inspeccion= $objInspeccion->finalizarProgramacionInspeccion($id);
                break;
            case 'sugerida':
                $objInspeccion = new InspeccionesSugeridasController();
                $inspeccion= $objInspeccion->finalizarProgramacionInspeccion($id);
                break;
            case 'valoracion':
                $objInspeccion = new InspeccionesValoracionController();
                $inspeccion= $objInspeccion->finalizarProgramacionInspeccion($id);
                break;
            //falta meter la opcion de hallazgos
        }
        return redirect()->route("inspeccion-$tipo",['id'=>$id]);
    }
    
    public function cancelarProgramacionInspeccion($id,$tipo){
        /*Aca va el codigo para borrar todos los datos*/
        switch ($tipo) {
            case 'obligatoria':
                $objInspeccion = new InspeccionesObligatoriasController();
                $inspeccion= $objInspeccion->cancelarProgramacionInspeccion($id);
                break;
            case 'sugerida':
                $objInspeccion = new InspeccionesSugeridasController();
                $inspeccion= $objInspeccion->cancelarProgramacionInspeccion($id);
                break;
            case 'valoracion':
                $objInspeccion = new InspeccionesValoracionController();
                $inspeccion= $objInspeccion->cancelarProgramacionInspeccion($id);
                break;
            //falta meter la opcion de hallazgos
        }
        
        return redirect()->route("inspeccion-$tipo",['id'=>$id]);
    }
    
    
    public function verEjecucionInspeccion($id,$tipo){
        
        
        $inspeccion = $this->getObjetoInspeccion($id,$tipo);
        return view('analissta.Inspecciones.ejecucion')->with(['inspeccion'=>$inspeccion[0],'tipoInspeccion'=>$tipo]);
    }
    
    public function verHallazgosInspeccion($id,$tipo){
        
        
        $inspeccion = $this->getObjetoInspeccion($id,$tipo);
        return view('analissta.Inspecciones.hallazgos')->with(['inspeccion'=>$inspeccion[0],'tipoInspeccion'=>$tipo]);
    }
    
    public function guardarEjecucionInspeccion(){
        $data = request()->validate([
            'tipo'              => 'required',
            'idInspeccion'       => 'required',
            'idJornada'         => 'required',
            'ejecutada'         => 'required',
        ],[
            'tipo.required'           => 'Revise el diligenciamiento de todos los campos',
            'idInspeccion.required'    => 'Revise el diligenciamiento de todos los campos',
            'idJornada.required'      => 'Revise el diligenciamiento de todos los campos',
            'ejecutada.required'      => 'Revise el diligenciamiento de todos los campos',
        ]);
        switch ($data["tipo"]) {
            case 'obligatoria':
                $objInspeccion = new InspeccionesObligatoriasController();
                $inspeccion= $objInspeccion->ejecucionInspeccion($data);
                break;
            case 'sugerida':
                $objInspeccion = new InspeccionesSugeridasController();
                $inspeccion= $objInspeccion->ejecucionInspeccion($data);
                break;
            case 'valoracion':
                $objInspeccion = new InspeccionesValoracionController();
                $inspeccion= $objInspeccion->ejecucionInspeccion($data);
                break;
            //falta meter la opcion de hallazgos
        }
        
        return redirect()->route("ejecucion-inspeccion",['id'=>$data["idInspeccion"],'tipo'=>$data["tipo"]]);
    }
}
