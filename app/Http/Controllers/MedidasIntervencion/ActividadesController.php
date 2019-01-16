<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\ActividadesObligatoriasSugerida;
use App\ActividadesValoracione;
use App\ActividadesHallazgo;
use App\Http\Controllers\MedidasIntervencion\ActividadesObligatoriasController;
use App\Http\Controllers\MedidasIntervencion\ActividadesSugeridasController;
use App\Http\Controllers\MedidasIntervencion\ActividadesValoracionController;
use App\Http\Controllers\MedidasIntervencion\ActividadesHallazgosController;

class ActividadesController extends Controller
{
    private function getObjetoActividad($id,$tipo){
        
        switch ($tipo) {
            case ($tipo === 'obligatoria' || $tipo === 'sugerida'):
                
                $actividad = ActividadesObligatoriasSugerida::where('id',$id)
                        ->where('sistema_id',session('sistema')->id)
                        ->where('medida',$tipo)
                        ->get();
                break;
            case 'valoracion':
                $actividad = ActividadesValoracione::where('id',$id)
                        ->where('sistema_id',session('sistema')->id)
                        ->get();
                
                break;
            case 'hallazgo':
                $actividad = ActividadesHallazgo::where('id',$id)
                        ->where('sistema_id',session('sistema')->id)
                        ->get();
                
                break;
        }
        return $actividad;
    }
    
    public function iniciarProgramarActividad($id,$tipo){
        /*$tipo => obligatoria, sugerida,valoracion, hallazgo*/
        $actividad = $this->getObjetoActividad($id,$tipo);
        
        return view('analissta.Actividades.Programacion.datosGenerales')->with(['actividad'=>$actividad[0],'tipoActividad'=>$tipo]);
    }
    
    public function programacionActividad($id,$tipo){
        /*$tipo => obligatoria, sugerida,valoracion, hallazgo*/
        
        
        $actividad = $this->getObjetoActividad($id,$tipo);
        return view('analissta.Actividades.Programacion.programacion')->with(['actividad'=>$actividad[0],'tipoActividad'=>$tipo]);
    }
    
    public function presupuestoActividad($id,$tipo){
        /*$tipo => obligatoria, sugerida,valoracion, hallazgo*/
        $actividad = $this->getObjetoActividad($id,$tipo);
        return view('analissta.Actividades.Programacion.presupuesto')->with(['actividad'=>$actividad[0],'tipoActividad'=>$tipo]);
    }
    
    public function datosGeneralesActividad(){
        /*$tipo => obligatoria, sugerida,valoracion, hallazgo*/
        $data = request()->validate([
            'tipo'          => 'required',
            'idActividad'   => 'required',
            'cargo'         => 'string|required',
            'evidencias'    => 'string|required',
            'observaciones' => 'string|required',
        ],[
            'tipo.required' => 'Revise el diligenciamiento de todos los campos',
            'idActividad.required' => 'Revise el diligenciamiento de todos los campos',
            'cargo.required' => 'Debe indicar el cargo responsable de la actividad',
            'evidencias.required' => 'Debe indicar que evidencias se van manejar',
            'observaciones.required' => 'Señale las observaciones que se deben tener en cuenta',
        ]);
        
        switch ($data["tipo"]) {
            case 'obligatoria':
                $objActividad = new ActividadesObligatoriasController();
                $actividad= $objActividad->updateDatosGenerales($data);
                break;
            case 'sugerida':
                $objActividad = new ActividadesSugeridasController();
                $actividad= $objActividad->updateDatosGenerales($data);
                break;
            case 'valoracion':
                $objActividad = new ActividadesValoracionController();
                $actividad= $objActividad->updateDatosGenerales($data);
                break;
            case 'hallazgo':
                $objActividad = new ActividadesHallazgosController();
                $actividad= $objActividad->updateDatosGenerales($data);
                break;
            //falta meter la opcion de hallazgos
        }
        
        return redirect()->route('ver-programacion-actividad',['id'=>$data["idActividad"],'tipo'=>$data["tipo"]]);
    }
    
    public function guardarProgramacionActividad(){
        $data = request()->validate([
            'tipo'          => 'required',
            'idActividad'   => 'required',
            'frecuencia'    => 'string|required',
            'dataCentros'   => 'array|required',
        ],[
            'tipo.required'         => 'Revise el diligenciamiento de todos los campos',
            'idActividad.required'  => 'Revise el diligenciamiento de todos los campos',
            'frecuencia.required'   => 'Debe seleccionar una frecuencia de ejecución',
            'dataCentros.required'  => 'Debe seleccionar por lo menos un centro de trabajo',
        ]);
        switch ($data["tipo"]) {
            case 'obligatoria':
                $objActividad = new ActividadesObligatoriasController();
                $actividad= $objActividad->crearProgramacionActividad($data);
                break;
            case 'sugerida':
                $objActividad = new ActividadesSugeridasController();
                $actividad= $objActividad->crearProgramacionActividad($data);
                break;
            case 'valoracion':
                $objActividad = new ActividadesValoracionController();
                $actividad= $objActividad->crearProgramacionActividad($data);
                break;
            case 'hallazgo':
                $objActividad = new ActividadesHallazgosController();
                $actividad= $objActividad->crearProgramacionActividad($data);
                break;
            //falta meter la opcion de hallazgos
        }
        
        return redirect()->route('programacion-presupuesto-actividad',['id'=>$data["idActividad"],'tipo'=>$data["tipo"]]);
        
    }
    
    public function guardarPresupuestoActividad(){
        $data = request()->validate([
            'tipo'              => 'required',
            'idActividad'       => 'required',
            'origen'       => 'required',
            'item'              => 'string|required',
            'observaciones'     => 'string|required',
            'valor'             => 'numeric|required',
        ],[
            'tipo.required'           => 'Revise el diligenciamiento de todos los campos',
            'idActividad.required'    => 'Revise el diligenciamiento de todos los campos',
            'item.required'           => 'Debe describir el item del presupuesto',
            'observaciones.required'  => 'Debe describir las observaciones sobre el presupuesto',
            'valor.required'          => 'Debe ingresar el valor',
        ]);
        
        switch ($data["tipo"]) {
            case 'obligatoria':
                $objActividad = new ActividadesObligatoriasController();
                $actividad= $objActividad->crearPresupuestoActividad($data);
                break;
            case 'sugerida':
                $objActividad = new ActividadesSugeridasController();
                $actividad= $objActividad->crearPresupuestoActividad($data);
                break;
            case 'valoracion':
                $objActividad = new ActividadesValoracionController();
                $actividad= $objActividad->crearPresupuestoActividad($data);
                break;
            case 'hallazgo':
                $objActividad = new ActividadesHallazgosController();
                $actividad= $objActividad->crearPresupuestoActividad($data);
                break;
        }
        
        return redirect()->route($data["origen"],['id'=>$data["idActividad"],'tipo'=>$data["tipo"]]);
    }
    
    public function finalizarProgramarActividad($id,$tipo){
        switch ($tipo) {
            case 'obligatoria':
                $objActividad = new ActividadesObligatoriasController();
                $actividad= $objActividad->finalizarProgramacionActividad($id);
                break;
            case 'sugerida':
                $objActividad = new ActividadesSugeridasController();
                $actividad= $objActividad->finalizarProgramacionActividad($id);
                break;
            case 'valoracion':
                $objActividad = new ActividadesValoracionController();
                $actividad= $objActividad->finalizarProgramacionActividad($id);
                break;
            case 'hallazgo':
                $objActividad = new ActividadesHallazgosController();
                $actividad= $objActividad->finalizarProgramacionActividad($id);
                break;
        }
        return redirect()->route("actividad-$tipo",['id'=>$id]);
    }
    
    public function cancelarProgramacionActividad($id,$tipo){
        /*Aca va el codigo para borrar todos los datos*/
        switch ($tipo) {
            case 'obligatoria':
                $objActividad = new ActividadesObligatoriasController();
                $actividad= $objActividad->cancelarProgramacionActividad($id);
                break;
            case 'sugerida':
                $objActividad = new ActividadesSugeridasController();
                $actividad= $objActividad->cancelarProgramacionActividad($id);
                break;
            case 'valoracion':
                $objActividad = new ActividadesValoracionController();
                $actividad= $objActividad->cancelarProgramacionActividad($id);
                break;
            //falta meter la opcion de hallazgos
        }
        
        return redirect()->route("actividad-$tipo",['id'=>$id]);
    }
    
    
    public function verEjecucionActividad($id,$tipo){
        $actividad = $this->getObjetoActividad($id,$tipo);
        return view('analissta.Actividades.ejecucion')->with(['actividad'=>$actividad[0],'tipoActividad'=>$tipo]);
    }
    
    public function guardarEjecucionActividad(){
        $data = request()->validate([
            'tipo'              => 'required',
            'idActividad'       => 'required',
            'idJornada'         => 'required',
            'ejecutada'         => 'required',
        ],[
            'tipo.required'           => 'Revise el diligenciamiento de todos los campos',
            'idActividad.required'    => 'Revise el diligenciamiento de todos los campos',
            'idJornada.required'      => 'Revise el diligenciamiento de todos los campos',
            'ejecutada.required'      => 'Revise el diligenciamiento de todos los campos',
        ]);
        switch ($data["tipo"]) {
            case 'obligatoria':
                $objActividad = new ActividadesObligatoriasController();
                $actividad= $objActividad->ejecucionActividad($data);
                break;
            case 'sugerida':
                $objActividad = new ActividadesSugeridasController();
                $actividad= $objActividad->ejecucionActividad($data);
                break;
            case 'valoracion':
                $objActividad = new ActividadesValoracionController();
                $actividad= $objActividad->ejecucionActividad($data);
                break;
            case 'hallazgo':
                $objActividad = new ActividadesHallazgosController();
                $actividad= $objActividad->ejecucionActividad($data);
                break;
        }
        
        return redirect()->route("ejecucion-actividad",['id'=>$data["idActividad"],'tipo'=>$data["tipo"]]);
    }
}
