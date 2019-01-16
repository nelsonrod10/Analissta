<?php

namespace App\Http\Controllers\Hallazgos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\Hallazgos\Hallazgo;
use App\InspeccionesObligatoriasSugerida;
use App\InspeccionesValoracione;
use App\PeligrosHallazgosAccidente;
use App\CausasBasicasInmediata;
use App\ActividadesHallazgo;
use App\CapacitacionesHallazgo;

class HallazgosInspeccionesController extends Controller
{
    public function datosGenerales($idInspeccion,$tipoInspeccion,$idHallazgo=null){
        $inspeccion = $this->getDataInspeccion($idInspeccion,$tipoInspeccion);
        
        
        return view('analissta.Hallazgos.crearHallazgoInspeccion.datosGenerales')->with(['idHallazgo'=>$idHallazgo,'inspeccion'=>$inspeccion]);
    }
    
    public function peligroAsociado($idInspeccion,$tipoInspeccion,$idHallazgo){
        $inspeccion = $this->getDataInspeccion($idInspeccion,$tipoInspeccion);
        
        
        
        return view('analissta.Hallazgos.crearHallazgoInspeccion.peligroAsociado')->with(['idHallazgo'=>$idHallazgo,'inspeccion'=>$inspeccion]);
    }
    
    public function causasInmediatas($idInspeccion,$tipoInspeccion,$idHallazgo){
        $inspeccion = $this->getDataInspeccion($idInspeccion,$tipoInspeccion);
        
        
        
        return view('analissta.Hallazgos.crearHallazgoInspeccion.causasInmediatas')->with(['idHallazgo'=>$idHallazgo,'inspeccion'=>$inspeccion]);
    }
    
    public function causasBasicas($idInspeccion,$tipoInspeccion,$idHallazgo){
        $inspeccion = $this->getDataInspeccion($idInspeccion,$tipoInspeccion);
        
        
        
        return view('analissta.Hallazgos.crearHallazgoInspeccion.causasBasicas')->with(['idHallazgo'=>$idHallazgo,'inspeccion'=>$inspeccion]);
    }
    
    public function acciones($idInspeccion,$tipoInspeccion,$idHallazgo){
        $inspeccion = $this->getDataInspeccion($idInspeccion,$tipoInspeccion);
        
        
        
        return view('analissta.Hallazgos.crearHallazgoInspeccion.acciones')->with(['idHallazgo'=>$idHallazgo,'inspeccion'=>$inspeccion]);
    }
    
    public function actividades($idInspeccion,$tipoInspeccion,$idHallazgo){
        $inspeccion = $this->getDataInspeccion($idInspeccion,$tipoInspeccion);
        
        
        
        return view('analissta.Hallazgos.crearHallazgoInspeccion.actividades')->with(['idHallazgo'=>$idHallazgo,'inspeccion'=>$inspeccion]);
    }
    
    public function capacitaciones($idInspeccion,$tipoInspeccion,$idHallazgo){
        $inspeccion = $this->getDataInspeccion($idInspeccion,$tipoInspeccion);
        
        
        
        return view('analissta.Hallazgos.crearHallazgoInspeccion.capacitaciones')->with(['idHallazgo'=>$idHallazgo,'inspeccion'=>$inspeccion]);
    }
    
    private function getDataInspeccion($id,$tipo){
        if($tipo === "sugerida" || $tipo === "obligatoria"){
            return InspeccionesObligatoriasSugerida::find($id);
        }
        
        return InspeccionesValoracione::find($id);
    }
    
    public function crearDatosGenerales($idInspeccion,$tipoInspeccion,$id=null){
        if($tipoInspeccion === "sugerida" || $tipoInspeccion === "obligatoria"){
            $tipo=$tipoInspeccion;
        }else{$tipo = "valoracion";}
        $data = request()->validate([
            'origen'       => 'string|required',
            'centro'       => 'integer|required',
            'proceso'      => 'string|required',
            'fecha'        => 'date|required',
            'responsable'  => 'string|required',
            'descripcion'  => 'string|required',
        ],[
            'origen.required'     => 'Debe seleccionar un origen',
            'centro.required'     => 'Debe seleccionar un centro de trabajo',
            'proceso.required'    => 'Debe seleccionar un Proceso',
            'fecha.required'      => 'Debe ingresar una fecha valida',
            'responsable.required' => 'Debe ingresar un responsable',
            'descripcion.required' => 'Debe realizar un descripción del hallazgo',
            
            'origen.string'     => 'Revise el formato de los datos',
            'centro.string'     => 'Revise el formato de los datos',
            'proceso.string'    => 'Revise el formato de los datos',
            'fecha.date'      => 'Revise el formato de los datos',
            'responsable.string' => 'Revise el formato de los datos',
            'descripcion.string' => 'Revise el formato de los datos',
        ]);
        if($id === null){
            $idHallazgo = $this->createDatosGenerales($data,$idInspeccion,$tipo);
        }else{
            $idHallazgo = $this->updateDatosGenerales($id,$data);
        }
        if($tipoInspeccion === "sugerida" || $tipoInspeccion === "obligatoria"){
            return redirect()->route("peligro-asociado-hallazgo-inspeccion",["idInspeccion"=>$idInspeccion,"tipoInspeccion"=>$tipoInspeccion,"idHallazgo"=>$idHallazgo]);
        }else{
            return redirect()->route("causas-inmediatas-hallazgo-inspeccion",["idInspeccion"=>$idInspeccion,"tipoInspeccion"=>$tipoInspeccion,"idHallazgo"=>$idHallazgo]);
        }
        
    }
    
    private function createDatosGenerales($data,$idInspeccion,$tipoInspeccion){
        $inspeccion = $this->getDataInspeccion($idInspeccion,$tipoInspeccion);
        $hallazgo = Hallazgo::create([
            'sistema_id'  => session('sistema')->id,
            'centrosTrabajo_id'  => $data["centro"],
            'proceso_id'         => $data["proceso"],
            'origen_id'          => $data["origen"],
            'fechaHallazgo'      => $data["fecha"],
            'cargoResponsable'   => $data["responsable"],
            'descripcion'        => $data["descripcion"],
            'fechaCierre'        => $data["fecha"],
            'origen_externo_id'      => $idInspeccion,
            'origen_externo_tipo'    => $tipoInspeccion,
        ]);
        
        PeligrosHallazgosAccidente::create([
            'sistema_id'  => session('sistema')->id,
            'origen_id'          => $hallazgo->id,
            'origen_table'       => "Hallazgos",
            "clasificacion"      => isset($inspeccion->peligro)?$inspeccion->peligro->clasificacion:0,
            "categoria"          => isset($inspeccion->peligro)?$inspeccion->peligro->categoria:0,
            "subCategoria"       => isset($inspeccion->peligro)?$inspeccion->peligro->subCategoria:0,
            "fuentes"            => isset($inspeccion->peligro)?$inspeccion->peligro->fuentes:"",
            "especificacion"     => isset($inspeccion->peligro)?$inspeccion->peligro->especificacion:"",
            "factorHumano"       => isset($inspeccion->peligro)?$inspeccion->peligro->factorHumano:"N/A"
        ]);
        
        return $hallazgo->id;
    }
    
    private function updateDatosGenerales($idHallazgo,$data){
        $hallazgo = Hallazgo::find($idHallazgo);
        $hallazgo->centrosTrabajo_id     =  $data["centro"];
        $hallazgo->proceso_id            =  $data["proceso"];
        $hallazgo->origen_id             =  $data["origen"];
        $hallazgo->fechaHallazgo         =  $data["fecha"];
        $hallazgo->cargoResponsable      =  $data["responsable"];
        $hallazgo->descripcion           =  $data["descripcion"];
        $hallazgo->save();
        return $idHallazgo;
    }
    
    public function crearPeligroAsociado($idInspeccion,$tipoInspeccion,$idHallazgo){
        $data = request()->validate([
            'clasificacion'     => 'integer|min:1|max:10|required',
            'descripcion'       => 'integer|required|min:1',
            'subdescripcion'    => 'integer',
            'fuentes'           => 'array',
            'especificacion'    => 'string|required',
            'factorH'           => 'string|nullable',
        ],[
            'clasificacion.required'   => 'Debe seleccionar una clasificación',
            'descripcion.required'     => 'Debe seleccionar una descripción',
            'especifiacion.required'   => 'Detalle las fuentes que generan el peligro',
            'clasificacion.min'        => 'Error al seleccionar la clasificación',
            'clasificacion.max'        => 'Error al seleccionar la clasificación',
            'descripcion.min'          => 'Error al seleccionar la descripción',
        ]);
        
        PeligrosHallazgosAccidente::where('sistema_id',session('sistema')->id)
            ->where('origen_id',$idHallazgo)
            ->where('origen_table','Hallazgos')
            ->update([
                "clasificacion"         =>  $data["clasificacion"],
                "categoria"             =>  $data["descripcion"],
                "subCategoria"          => isset($data["subdescripcion"])?$data["subdescripcion"]:0,
                "fuentes"               =>  implode(",", $data["fuentes"]),
                "especificacion"        =>  $data["especificacion"],
                "factorHumano"          => isset($data["factorH"])?$data["factorH"]:"N/A"
            ]);
        return redirect()->route("causas-inmediatas-hallazgo-inspeccion",["idInspeccion"=>$idInspeccion,"tipoInspeccion"=>$tipoInspeccion,"idHallazgo"=>$idHallazgo]);
    }
    
    public function crearCausasInmediatas($idInspeccion,$tipoInspeccion,$idHallazgo){
        $data = request()->validate([
            'factor'            => 'integer|min:1|max:10|required',
            'categoria'         => 'integer|required|min:1',
            'arrmedidas'        => 'required|array',
            'detalles'          => 'string|nullable',
        ],[
            'arrmedidas.required'      => 'Debe seleccionar por lo menos una opción',
        ]);
        
        CausasBasicasInmediata::where('sistema_id',session('sistema')->id)
                ->where('origen_id',$idHallazgo)
                ->where('origen_table','Hallazgos')
                ->where('tipo','Inmediata')
                ->where('factor',$data["factor"])
                ->where('categoria',$data["categoria"])
                ->delete();
        
        CausasBasicasInmediata::create([
            'sistema_id'  => session('sistema')->id,
            'origen_id'          => $idHallazgo,
            'origen_table'       => "Hallazgos",
            'tipo'               => "Inmediata",
            'factor'             => $data["factor"],
            'categoria'          => $data["categoria"],
            'descripcion'        => implode(",",$data["arrmedidas"]),
            'observaciones'      => $data["detalles"],
        ]);
        return redirect()->route("causas-inmediatas-hallazgo-inspeccion",["idInspeccion"=>$idInspeccion,"tipoInspeccion"=>$tipoInspeccion,"idHallazgo"=>$idHallazgo]);
    }
    
    public function crearCausasBasicas($idInspeccion,$tipoInspeccion,$idHallazgo){
        $data = request()->validate([
            'factor'            => 'integer|min:1|max:10|required',
            'categoria'         => 'integer|required|min:1',
            'arrmedidas'        => 'required|array',
            'detalles'          => 'string|nullable',
        ],[
            'arrmedidas.required'      => 'Debe seleccionar por lo menos una opción',
        ]);
        
        CausasBasicasInmediata::where('sistema_id',session('sistema')->id)
                ->where('origen_id',$idHallazgo)
                ->where('origen_table','Hallazgos')
                ->where('tipo','Basica')
                ->where('factor',$data["factor"])
                ->where('categoria',$data["categoria"])
                ->delete();
        
        CausasBasicasInmediata::create([
            'sistema_id'  => session('sistema')->id,
            'origen_id'          => $idHallazgo,
            'origen_table'       => "Hallazgos",
            'tipo'               => "Basica",
            'factor'             => $data["factor"],
            'categoria'          => $data["categoria"],
            'descripcion'        => implode(",",$data["arrmedidas"]),
            'observaciones'      => $data["detalles"],
        ]);
        return redirect()->route("causas-basicas-hallazgo-inspeccion",["idInspeccion"=>$idInspeccion,"tipoInspeccion"=>$tipoInspeccion,"idHallazgo"=>$idHallazgo]);
    }
    
    public function crearAcciones($idInspeccion,$tipoInspeccion,$idHallazgo){
        $data = request()->validate([
            'actoCondicion'      => 'string|required',
            'tipoAccion'         => 'string|required',
            'tipoPlanAccion'     => 'string|required',
            'fechaCierre'        => 'date|required',
        ],[
            'actoCondicion.required'    => 'Seleccione una opción para Acto/Condición',
            'tipoAccion.required'       => 'Seleccione una opción para Tipo Acción',
            'tipoPlanAccion.required'   => 'Seleccione un Plan de Acción',
            'fechaCierre.required'      => 'Ingrese una fecha de cierre',
        ]);
        
        ActividadesHallazgo::where('sistema_id',  session('sistema')->id)
            ->where('hallazgo_id',$idHallazgo)
            ->where('medida','hallazgos')
            ->delete();
        
        CapacitacionesHallazgo::where('sistema_id',  session('sistema')->id)
            ->where('hallazgo_id',$idHallazgo)
            ->where('medida','hallazgos')
            ->delete();
        
        $hallazgo = Hallazgo::find($idHallazgo);
        $hallazgo->actoCondicion     =  $data["actoCondicion"];
        $hallazgo->tipoAccion        =  $data["tipoAccion"];
        $hallazgo->planAccion        =  $data["tipoPlanAccion"];
        $hallazgo->fechaCierre       =  $data["fechaCierre"];
        $hallazgo->save();
        
        if($data["tipoPlanAccion"] === "Capacitaciones"){
            return redirect()->route("capacitaciones-hallazgo-inspeccion",["idInspeccion"=>$idInspeccion,"tipoInspeccion"=>$tipoInspeccion,"idHallazgo"=>$idHallazgo]);
        }else{
            return redirect()->route("actividades-hallazgo-inspeccion",["idInspeccion"=>$idInspeccion,"tipoInspeccion"=>$tipoInspeccion,"idHallazgo"=>$idHallazgo]);
        }
        
    }
    
    public function crearActividades($idInspeccion,$tipoInspeccion,$idHallazgo){
        $data = request()->validate([
            'nombre'            => 'string|required',
        ],[
            'nombre.required'      => 'Debe seleccionar por lo menos una opción',
        ]);
        
        ActividadesHallazgo::create([
            'sistema_id'  => session('sistema')->id,
            'hallazgo_id'        => $idHallazgo,
            'medida'             => "hallazgos",
            'nombre'             => $data["nombre"],
        ]);
        return redirect()->route("actividades-hallazgo-inspeccion",["idInspeccion"=>$idInspeccion,"tipoInspeccion"=>$tipoInspeccion,"idHallazgo"=>$idHallazgo]);
    }
    
    public function crearCapacitaciones($idInspeccion,$tipoInspeccion,$idHallazgo){
        $data = request()->validate([
            'nombre'            => 'string|required',
        ],[
            'nombre.required'      => 'Debe seleccionar por lo menos una opción',
        ]);
        
        CapacitacionesHallazgo::create([
            'sistema_id'  => session('sistema')->id,
            'hallazgo_id'        => $idHallazgo,
            'medida'             => "hallazgos",
            'nombre'             => $data["nombre"],
        ]);
        return redirect()->route("capacitaciones-hallazgo-inspeccion",["idInspeccion"=>$idInspeccion,"tipoInspeccion"=>$tipoInspeccion,"idHallazgo"=>$idHallazgo]);
    }
    
    public function eliminarActividad($idInspeccion,$tipoInspeccion,$idHallazgo,$idActividad){
        ActividadesHallazgo::where('sistema_id',  session('sistema')->id)
            ->where('id',$idActividad)    
            ->where('hallazgo_id',$idHallazgo)
            ->where('medida','hallazgos')
            ->delete();
        return redirect()->route("actividades-hallazgo-inspeccion",["idInspeccion"=>$idInspeccion,"tipoInspeccion"=>$tipoInspeccion,"idHallazgo"=>$idHallazgo]);
    }
    
    public function eliminarCapacitacion($idInspeccion,$tipoInspeccion,$idHallazgo,$idCapacitacion){
        CapacitacionesHallazgo::where('sistema_id',  session('sistema')->id)
            ->where('id',$idCapacitacion)    
            ->where('hallazgo_id',$idHallazgo)
            ->where('medida','hallazgos')
            ->delete();
        return redirect()->route("capacitaciones-hallazgo-inspeccion",["idInspeccion"=>$idInspeccion,"tipoInspeccion"=>$tipoInspeccion,"idHallazgo"=>$idHallazgo]);
    }
    
    public function eliminarCausaInmediata($idInspeccion,$tipoInspeccion,$idHallazgo, $idCausa){
        CausasBasicasInmediata::where('id',$idCausa)
                ->where('sistema_id',session('sistema')->id)
                ->where('origen_id',$idHallazgo)
                ->where('origen_table','Hallazgos')
                ->where('tipo','Inmediata')
                ->delete();
        return redirect()->route("causas-inmediatas-hallazgo-inspeccion",["idInspeccion"=>$idInspeccion,"tipoInspeccion"=>$tipoInspeccion,"idHallazgo"=>$idHallazgo]);
    }
    
    public function eliminarCausaBasica($idInspeccion,$tipoInspeccion,$idHallazgo, $idCausa){
        CausasBasicasInmediata::where('id',$idCausa)
                ->where('sistema_id',session('sistema')->id)
                ->where('origen_id',$idHallazgo)
                ->where('origen_table','Hallazgos')
                ->where('tipo','Basica')
                ->delete();
        return redirect()->route("causas-basicas-hallazgo-inspeccion",["idInspeccion"=>$idInspeccion,"tipoInspeccion"=>$tipoInspeccion,"idHallazgo"=>$idHallazgo]);
    }
    
    public function cancelarHallazgo($idInspeccion,$tipoInspeccion,$idHallazgo=null){
        if($idHallazgo !== null){
            PeligrosHallazgosAccidente::where('sistema_id',session('sistema')->id)
                ->where('origen_id',$idHallazgo)
                ->where('origen_table','Hallazgos')
                ->delete();
        
            CausasBasicasInmediata::where('sistema_id',session('sistema')->id)
                    ->where('origen_id',$idHallazgo)
                    ->where('origen_table','Hallazgos')
                    ->delete();

            Hallazgo::where('sistema_id',session('sistema')->id)
                    ->where('id',$idHallazgo)
                    ->delete();
        }
        
        if($tipoInspeccion == "obligatoria" || $tipoInspeccion == "sugerida"){
            $tipo = $tipoInspeccion;
        }else{
            $tipo = "valoracion";
        }
        
        return redirect()->route("ejecucion-inspeccion",["id"=>$idInspeccion,"tipo"=>$tipo]);
    }
    
    
}
