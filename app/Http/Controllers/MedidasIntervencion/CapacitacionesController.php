<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\Http\Controllers\MedidasIntervencion\CapacitacionesObligatoriasController;
use App\Http\Controllers\MedidasIntervencion\CapacitacionesSugeridasController;
use App\Http\Controllers\MedidasIntervencion\CapacitacionesValoracionController;
use App\EmpresaCliente;
use App\CapacitacionesObligatoriasSugerida;
use App\CapacitacionesValoracione;
use App\CapacitacionesHallazgo;

class CapacitacionesController extends Controller
{
    private function getObjetoCapacitacion($id,$tipo){
        switch ($tipo) {
            case ($tipo === 'obligatoria' || $tipo === 'sugerida'):
                
                $capacitacion = CapacitacionesObligatoriasSugerida::where('id',$id)
                        ->where('sistema_id',session('sistema')->id)
                        ->where('medida',$tipo)
                        ->get();
                break;
            case 'valoracion':
                $capacitacion = CapacitacionesValoracione::where('id',$id)
                        ->where('sistema_id',session('sistema')->id)
                        ->get();
                
                break;
            case 'hallazgo':
                $capacitacion = CapacitacionesHallazgo::where('id',$id)
                        ->where('sistema_id',session('sistema')->id)
                        ->get();
                break;
        }
        return $capacitacion;
    }
    
    public function iniciarProgramarCapacitacion($id,$tipo){
        /*$tipo => obligatoria, sugerida,valoracion, hallazgo*/
        
        
        $capacitacion = $this->getObjetoCapacitacion($id,$tipo);
        
        return view('analissta.Capacitaciones.Programacion.datosGenerales')->with(['capacitacion'=>$capacitacion[0],'tipoCapacitacion'=>$tipo]);
    }
    
    public function programacionCapacitacion($id,$tipo){
        /*$tipo => obligatoria, sugerida,valoracion, hallazgo*/
        
        
        $capacitacion = $this->getObjetoCapacitacion($id,$tipo);
        return view('analissta.Capacitaciones.Programacion.programacion')->with(['capacitacion'=>$capacitacion[0],'tipoCapacitacion'=>$tipo]);
    }
    
    public function jornadasCapacitacion($id,$tipo,$arrDataCentros){
        /*$tipo => obligatoria, sugerida,valoracion, hallazgo*/
        
        
        $capacitacion = $this->getObjetoCapacitacion($id,$tipo);
        return view('analissta.Capacitaciones.Programacion.jornadas')->with(['capacitacion'=>$capacitacion[0],'tipoCapacitacion'=>$tipo,'arrDataCentros'=>$arrDataCentros]);
    }
    
    public function presupuestoCapacitacion($id,$tipo,$arrDataCentros){
        /*$tipo => obligatoria, sugerida,valoracion, hallazgo*/
        
        
        $capacitacion = $this->getObjetoCapacitacion($id,$tipo);
        return view('analissta.Capacitaciones.Programacion.presupuesto')->with(['capacitacion'=>$capacitacion[0],'tipoCapacitacion'=>$tipo,'arrDataCentros'=>$arrDataCentros]);
    }
    
    public function verEjecucionCapacitacion($id,$tipo){
        
        
        $capacitacion = $this->getObjetoCapacitacion($id,$tipo);
        return view('analissta.Capacitaciones.ejecucion')->with(['capacitacion'=>$capacitacion[0],'tipoCapacitacion'=>$tipo]);
    }
    
    public function datosGeneralesCapacitacion(){
        /*$tipo => obligatoria, sugerida,valoracion, hallazgo*/
        $data = request()->validate([
            'tipo'          => 'required',
            'idCapacitacion'   => 'required',
            'cargo'         => 'string|required',
            'evidencias'    => 'string|required',
            'observaciones' => 'string|required',
            'temario'       => 'string|required',
        ],[
            'tipo.required' => 'Revise el diligenciamiento de todos los campos',
            'idActividad.required' => 'Revise el diligenciamiento de todos los campos',
            'cargo.required' => 'Debe indicar el cargo responsable de la capacitacion',
            'evidencias.required' => 'Debe indicar que evidencias se van manejar',
            'observaciones.required' => 'Señale las observaciones que se deben tener en cuenta',
            'temario.required' => 'Haga un resumen del contenido temático de la capacitación',
        ]);
        
        switch ($data["tipo"]) {
            case 'obligatoria':
                $objCapacitacion = new CapacitacionesObligatoriasController();
                $capacitacion= $objCapacitacion->updateDatosGenerales($data);
                break;
            case 'sugerida':
                $objCapacitacion = new CapacitacionesSugeridasController();
                $capacitacion= $objCapacitacion->updateDatosGenerales($data);
                break;
            case 'valoracion':
                $objCapacitacion = new CapacitacionesValoracionController();
                $capacitacion= $objCapacitacion->updateDatosGenerales($data);
                break;
            case 'hallazgo':
                $objCapacitacion = new CapacitacionesHallazgosController();
                $capacitacion= $objCapacitacion->updateDatosGenerales($data);
                break;
        }
        
        return redirect()->route('ver-programacion-capacitacion',['id'=>$data["idCapacitacion"],'tipo'=>$data["tipo"]]);
    }
    
    
    public function guardarProgramacionCapacitacion(){
        $data = request()->validate([
            'tipo'          => 'required',
            'idCapacitacion'=> 'required',
            'evaluable'     => 'string|required',
            'capacitador'   => 'string|required',
            'dataCentros'   => 'array|required',
        ],[
            'tipo.required'         => 'Revise el diligenciamiento de todos los campos',
            'idCapacitacion.required'  => 'Revise el diligenciamiento de todos los campos',
            'evaluable.required'    => 'Debe indicar si la capacitación es evaluable',
            'capacitador.required'    => 'Seleccione un tipo de capacitador',
            'dataCentros.required'  => 'Debe seleccionar por lo menos un centro de trabajo',
        ]);
        switch ($data["tipo"]) {
            case 'obligatoria':
                $objCapacitacion = new CapacitacionesObligatoriasController();
                $capacitacion= $objCapacitacion->crearProgramacionCapacitacion($data);
                break;
            case 'sugerida':
                $objCapacitacion = new CapacitacionesSugeridasController();
                $capacitacion= $objCapacitacion->crearProgramacionCapacitacion($data);
                break;
            case 'valoracion':
                $objCapacitacion = new CapacitacionesValoracionController();
                $capacitacion= $objCapacitacion->crearProgramacionCapacitacion($data);
                break;
            case 'hallazgo':
                $objCapacitacion = new CapacitacionesHallazgosController();
                $capacitacion= $objCapacitacion->crearProgramacionCapacitacion($data);
                break;
        }
        
        return redirect()->route('programacion-jornadas-capacitacion',['id'=>$data["idCapacitacion"],'tipo'=>$data["tipo"],'arrDataCentros'=>  implode(",", $data['dataCentros'])]);
    }
    
    public function guardarJornadasCapacitacion(){
        $data = request()->validate([
            'tipo'          => 'required',
            'idCapacitacion'=> 'required',
            'centro'        => 'required',
            'responsable'   => 'required',
            'poblacion'     => 'required',
            'arrDataCentros'=> 'required',
            'mes'           => 'string|required',
            'semana'        => 'string|required',
            'invitados'     => 'numeric|required',
        ],[
            'tipo.required'         => 'Revise el diligenciamiento de todos los campos 1',
            'idCapacitacion.required'  => 'Revise el diligenciamiento de todos los campos 2',
            'centro.required'         => 'Revise el diligenciamiento de todos los campos 3',
            'responsable.required'  => 'Revise el diligenciamiento de todos los campos 4',
            'poblacion.required'         => 'Revise el diligenciamiento de todos los campos 5',
            'arrDataCentros.required'  => 'Revise el diligenciamiento de todos los campos 6',
            'mes.required'          => 'Seleccione un mes',
            'semana.required'    => 'Seleccione una semana',
            'invitados.required'  => 'Indique cuantas personas se van a invitar a esta jornada',
        ]);
        switch ($data["tipo"]) {
            case 'obligatoria':
                $objCapacitacion = new CapacitacionesObligatoriasController();
                $capacitacion= $objCapacitacion->crearJornadaCapacitacion($data);
                break;
            case 'sugerida':
                $objCapacitacion = new CapacitacionesSugeridasController();
                $capacitacion= $objCapacitacion->crearJornadaCapacitacion($data);
                break;
            case 'valoracion':
                $objCapacitacion = new CapacitacionesValoracionController();
                $capacitacion= $objCapacitacion->crearJornadaCapacitacion($data);
                break;
            case 'hallazgo':
                $objCapacitacion = new CapacitacionesHallazgosController();
                $capacitacion= $objCapacitacion->crearJornadaCapacitacion($data);
                break;
        }
        
        return redirect()->route('programacion-jornadas-capacitacion',['id'=>$data["idCapacitacion"],'tipo'=>$data["tipo"],'arrDataCentros'=> $data['arrDataCentros']]);
    }
    
    
    public function guardarPresupuestoCapacitacion(){
        $data = request()->validate([
            'tipo'              => 'required',
            'idCapacitacion'    => 'required',
            'origen'            => 'required',
            'item'              => 'string|required',
            'observaciones'     => 'string|required',
            'valor'             => 'numeric|required',
            'arrDataCentros'    => 'required'
        ],[
            'tipo.required'           => 'Revise el diligenciamiento de todos los campos',
            'idCapacitacion.required'    => 'Revise el diligenciamiento de todos los campos',
            'item.required'           => 'Debe describir el item del presupuesto',
            'observaciones.required'  => 'Debe describir las observaciones sobre el presupuesto',
            'valor.required'          => 'Debe ingresar el valor',
        ]);
        
        
        switch ($data["tipo"]) {
            case 'obligatoria':
                $objCapacitacion = new CapacitacionesObligatoriasController();
                $capacitacion= $objCapacitacion->crearPresupuestoCapacitacion($data);
                break;
            case 'sugerida':
                $objCapacitacion = new CapacitacionesSugeridasController();
                $capacitacion= $objCapacitacion->crearPresupuestoCapacitacion($data);
                break;
            case 'valoracion':
                $objCapacitacion = new CapacitacionesValoracionController();
                $capacitacion= $objCapacitacion->crearPresupuestoCapacitacion($data);
                break;
            case 'hallazgo':
                $objCapacitacion = new CapacitacionesHallazgosController();
                $capacitacion= $objCapacitacion->crearPresupuestoCapacitacion($data);
                break;
        }
        
        return redirect()->route($data["origen"],['id'=>$data["idCapacitacion"],'tipo'=>$data["tipo"],'arrDataCentros'=>$data["arrDataCentros"]]);
    }
    
    public function finalizarProgramarCapacitacion($id,$tipo){
        switch ($tipo) {
            case 'obligatoria':
                $objCapacitacion = new CapacitacionesObligatoriasController();
                $capacitacion= $objCapacitacion->finalizarProgramacionCapacitacion($id);
                break;
            case 'sugerida':
                $objCapacitacion = new CapacitacionesSugeridasController();
                $capacitacion= $objCapacitacion->finalizarProgramacionCapacitacion($id);
                break;
            case 'valoracion':
                $objCapacitacion = new CapacitacionesValoracionController();
                $capacitacion= $objCapacitacion->finalizarProgramacionCapacitacion($id);
                break;
            case 'hallazgo':
                $objCapacitacion = new CapacitacionesHallazgosController();
                $capacitacion= $objCapacitacion->finalizarProgramacionCapacitacion($id);
                break;
        }
        return redirect()->route("capacitacion-$tipo",['id'=>$id]);
    }
    
       
    public function cancelarProgramacionCapacitacion($id,$tipo){
        /*Aca va el codigo para borrar todos los datos*/
        switch ($tipo) {
            case 'obligatoria':
                $objCapacitacion = new CapacitacionesObligatoriasController();
                $capacitacion= $objCapacitacion->cancelarProgramacionCapacitacion($id);
                break;
            case 'sugerida':
                $objCapacitacion = new CapacitacionesSugeridasController();
                $capacitacion= $objCapacitacion->cancelarProgramacionCapacitacion($id);
                break;
            case 'valoracion':
                $objCapacitacion = new CapacitacionesValoracionController();
                $capacitacion= $objCapacitacion->cancelarProgramacionCapacitacion($id);
                break;
            //falta meter la opcion de hallazgos
        }
        
        return redirect()->route("capacitacion-$tipo",['id'=>$id]);
    }
    
    public function guardarEjecucionCapacitacion(){
        $data = request()->validate([
            'tipo'              => 'required',
            'idCapacitacion'    => 'required',
            'idJornada'         => 'required',
            'asistentes'        => 'numeric|min:1|required',
            'duracion'          => 'numeric|min:0.5|max:24|required',
            'ejecutada'         => 'required',
        ],[
            'tipo.required'           => 'Revise el diligenciamiento de todos los campos',
            'idCapacitacion.required'    => 'Revise el diligenciamiento de todos los campos',
            'idJornada.required'      => 'Revise el diligenciamiento de todos los campos',
            'asistentes.required'      => 'Revise el diligenciamiento de todos los campos',
            'duracion.required'      => 'Revise el diligenciamiento de todos los campos',
            'ejecutada.required'      => 'Revise el diligenciamiento de todos los campos',
            'asistentes.min'      => 'Debe asistir como mínimo una persona',
            'duracion.min'      => 'La capacitación debe durar como mínimo 0.5 horas',
            'duracion.max'      => 'La capacitación debe durar máximo 24 horas',
        ]);
        
        switch ($data["tipo"]) {
            case 'obligatoria':
                $objCapacitacion = new CapacitacionesObligatoriasController();
                $capacitacion= $objCapacitacion->ejecucionCapacitacion($data);
                break;
            case 'sugerida':
                $objCapacitacion = new CapacitacionesSugeridasController();
                $capacitacion= $objCapacitacion->ejecucionCapacitacion($data);
                break;
            case 'valoracion':
                $objCapacitacion = new CapacitacionesValoracionController();
                $capacitacion= $objCapacitacion->ejecucionCapacitacion($data);
                break;
            case 'hallazgo':
                $objCapacitacion = new CapacitacionesHallazgosController();
                $capacitacion= $objCapacitacion->ejecucionCapacitacion($data);
                break;
        }
        
        return redirect()->route("ejecucion-capacitacion",['id'=>$data["idCapacitacion"],'tipo'=>$data["tipo"]]);
    }
    
}

