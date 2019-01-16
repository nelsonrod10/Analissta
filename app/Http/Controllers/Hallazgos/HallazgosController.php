<?php

namespace App\Http\Controllers\Hallazgos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\Hallazgos\Hallazgo;
use App\PeligrosHallazgosAccidente;
use App\CausasBasicasInmediata;
use App\ActividadesHallazgo;
use App\CapacitacionesHallazgo;

class HallazgosController extends Controller
{
    public function principalHallazgos(){
        
        return view('analissta.Hallazgos.vistaGeneral');
    }
    
    public function mostrarHallazgo($id){
        
        
        
        return view('analissta.Hallazgos.verHallazgo')->with(['idHallazgo'=>$id]);
    }


    public function datosGenerales($id=null){
        
        
        
        return view('analissta.Hallazgos.crearHallazgo.datosGenerales')->with(['idHallazgo'=>$id]);
    }
    
    public function peligroAsociado($id){
        
        
        
        return view('analissta.Hallazgos.crearHallazgo.peligroAsociado')->with(['idHallazgo'=>$id]);
    }
    
    public function causasInmediatas($id){
        
        
        
        return view('analissta.Hallazgos.crearHallazgo.causasInmediatas')->with(['idHallazgo'=>$id]);
    }
    
    public function causasBasicas($id){
        
        
        
        return view('analissta.Hallazgos.crearHallazgo.causasBasicas')->with(['idHallazgo'=>$id]);
    }
    
    public function acciones($id){
        
        
        
        return view('analissta.Hallazgos.crearHallazgo.acciones')->with(['idHallazgo'=>$id]);
    }
    
    public function actividades($id){
        
        
        
        return view('analissta.Hallazgos.crearHallazgo.actividades')->with(['idHallazgo'=>$id]);
    }
    
    public function capacitaciones($id){
        
        
        
        return view('analissta.Hallazgos.crearHallazgo.capacitaciones')->with(['idHallazgo'=>$id]);
    }
    
    public function editarDatosHallazgo($id){
        $data = request()->validate([
            'descripcion'    => 'string|required',
            'responsable'    => 'string|required',
        ],[
            'descripcion.required'     => 'Debe seleccionar un origen',
            'responsable.required'     => 'Debe seleccionar un centro de trabajo',
        ]);
        
        $hallazgo = Hallazgo::find($id);
        $hallazgo->cargoResponsable      =  $data["responsable"];
        $hallazgo->descripcion           =  $data["descripcion"];
        $hallazgo->save();
        
        return redirect()->route('hallazgo',['id'=>$id]);
    }
    
    public function crearDatosGenerales($id=null){
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
            $idHallazgo = $this->createDatosGenerales($data);
        }else{
            $idHallazgo = $this->updateDatosGenerales($id,$data);
        }
        
        return redirect()->route("peligro-asociado-hallazgo",["id"=>$idHallazgo]);
    }
    
    private function createDatosGenerales($data){
        $hallazgo = Hallazgo::create([
            'sistema_id'  => session('sistema')->id,
            'centrosTrabajo_id'  => $data["centro"],
            'proceso_id'         => $data["proceso"],
            'origen_id'          => $data["origen"],
            'fechaHallazgo'      => $data["fecha"],
            'cargoResponsable'   => $data["responsable"],
            'descripcion'        => $data["descripcion"],
            'fechaCierre'        => $data["fecha"],
        ]);
        
        PeligrosHallazgosAccidente::create([
            'sistema_id'  => session('sistema')->id,
            'origen_id'          => $hallazgo->id,
            'origen_table'       => "Hallazgos",
            'factorHumano'       => "",
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
    
    public function crearPeligroAsociado($id){
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
            ->where('origen_id',$id)
            ->where('origen_table','Hallazgos')
            ->update([
                "clasificacion"         =>  $data["clasificacion"],
                "categoria"             =>  $data["descripcion"],
                "subCategoria"          => isset($data["subdescripcion"])?$data["subdescripcion"]:0,
                "fuentes"               =>  implode(",", $data["fuentes"]),
                "especificacion"        =>  $data["especificacion"],
                "factorHumano"          => isset($data["factorH"])?$data["factorH"]:"N/A"
            ]);
        return redirect()->route("causas-inmediatas-hallazgo",['id'=>$id]);
    }
    
    public function crearCausasInmediatas($id){
        $data = request()->validate([
            'factor'            => 'integer|min:1|max:10|required',
            'categoria'         => 'integer|required|min:1',
            'arrmedidas'        => 'required|array',
            'detalles'          => 'string|nullable',
        ],[
            'arrmedidas.required'      => 'Debe seleccionar por lo menos una opción',
        ]);
        
        CausasBasicasInmediata::where('sistema_id',session('sistema')->id)
                ->where('origen_id',$id)
                ->where('origen_table','Hallazgos')
                ->where('tipo','Inmediata')
                ->where('factor',$data["factor"])
                ->where('categoria',$data["categoria"])
                ->delete();
        
        CausasBasicasInmediata::create([
            'sistema_id'  => session('sistema')->id,
            'origen_id'          => $id,
            'origen_table'       => "Hallazgos",
            'tipo'               => "Inmediata",
            'factor'             => $data["factor"],
            'categoria'          => $data["categoria"],
            'descripcion'        => implode(",",$data["arrmedidas"]),
            'observaciones'      => $data["detalles"],
        ]);
        return redirect()->route("causas-inmediatas-hallazgo",['id'=>$id]);
    }
    
    public function crearCausasBasicas($id){
        $data = request()->validate([
            'factor'            => 'integer|min:1|max:10|required',
            'categoria'         => 'integer|required|min:1',
            'arrmedidas'        => 'required|array',
            'detalles'          => 'string|nullable',
        ],[
            'arrmedidas.required'      => 'Debe seleccionar por lo menos una opción',
        ]);
        
        CausasBasicasInmediata::where('sistema_id',session('sistema')->id)
                ->where('origen_id',$id)
                ->where('origen_table','Hallazgos')
                ->where('tipo','Basica')
                ->where('factor',$data["factor"])
                ->where('categoria',$data["categoria"])
                ->delete();
        
        CausasBasicasInmediata::create([
            'sistema_id'  => session('sistema')->id,
            'origen_id'          => $id,
            'origen_table'       => "Hallazgos",
            'tipo'               => "Basica",
            'factor'             => $data["factor"],
            'categoria'          => $data["categoria"],
            'descripcion'        => implode(",",$data["arrmedidas"]),
            'observaciones'      => $data["detalles"],
        ]);
        return redirect()->route("causas-basicas-hallazgo",['id'=>$id]);
    }
    
    public function crearAcciones($id){
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
            ->where('hallazgo_id',$id)
            ->where('medida','hallazgos')
            ->delete();
        
        CapacitacionesHallazgo::where('sistema_id',  session('sistema')->id)
            ->where('hallazgo_id',$id)
            ->where('medida','hallazgos')
            ->delete();
        
        $hallazgo = Hallazgo::find($id);
        $hallazgo->actoCondicion     =  $data["actoCondicion"];
        $hallazgo->tipoAccion        =  $data["tipoAccion"];
        $hallazgo->planAccion        =  $data["tipoPlanAccion"];
        $hallazgo->fechaCierre       =  $data["fechaCierre"];
        $hallazgo->save();
        
        if($data["tipoPlanAccion"] === "Capacitaciones"){
            return redirect()->route("capacitaciones-hallazgo",["id"=>$id]);
        }else{
            return redirect()->route("actividades-hallazgo",["id"=>$id]);
        }
        
    }
    
    public function crearActividades($id){
        $data = request()->validate([
            'nombre'            => 'string|required',
        ],[
            'nombre.required'      => 'Debe seleccionar por lo menos una opción',
        ]);
        
        ActividadesHallazgo::create([
            'sistema_id'  => session('sistema')->id,
            'hallazgo_id'        => $id,
            'medida'             => "hallazgos",
            'nombre'             => $data["nombre"],
        ]);
        return redirect()->route("actividades-hallazgo",["id"=>$id]);
    }
    
    public function crearCapacitaciones($id){
        $data = request()->validate([
            'nombre'            => 'string|required',
        ],[
            'nombre.required'      => 'Debe seleccionar por lo menos una opción',
        ]);
        
        CapacitacionesHallazgo::create([
            'sistema_id'  => session('sistema')->id,
            'hallazgo_id'        => $id,
            'medida'             => "hallazgos",
            'nombre'             => $data["nombre"],
        ]);
        return redirect()->route("capacitaciones-hallazgo",["id"=>$id]);
    }
    
    public function eliminarActividad($idHallazgo,$idActividad){
        ActividadesHallazgo::where('sistema_id',  session('sistema')->id)
            ->where('id',$idActividad)    
            ->where('hallazgo_id',$idHallazgo)
            ->where('medida','hallazgos')
            ->delete();
        return redirect()->route("actividades-hallazgo",["id"=>$idHallazgo]);
    }
    
    public function eliminarCapacitacion($idHallazgo,$idCapacitacion){
        CapacitacionesHallazgo::where('sistema_id',  session('sistema')->id)
            ->where('id',$idCapacitacion)    
            ->where('hallazgo_id',$idHallazgo)
            ->where('medida','hallazgos')
            ->delete();
        return redirect()->route("capacitaciones-hallazgo",["id"=>$idHallazgo]);
    }
    
    public function eliminarCausaInmediata($idHallazgo, $idCausa){
        CausasBasicasInmediata::where('id',$idCausa)
                ->where('sistema_id',session('sistema')->id)
                ->where('origen_id',$idHallazgo)
                ->where('origen_table','Hallazgos')
                ->where('tipo','Inmediata')
                ->delete();
        return redirect()->route("causas-inmediatas-hallazgo",['id'=>$idHallazgo]);
    }
    
    public function eliminarCausaBasica($idHallazgo, $idCausa){
        CausasBasicasInmediata::where('id',$idCausa)
                ->where('sistema_id',session('sistema')->id)
                ->where('origen_id',$idHallazgo)
                ->where('origen_table','Hallazgos')
                ->where('tipo','Basica')
                ->delete();
        return redirect()->route("causas-basicas-hallazgo",['id'=>$idHallazgo]);
    }
    
    public function cancelarHallazgo($id=null){
        if($id !== null){
            PeligrosHallazgosAccidente::where('sistema_id',session('sistema')->id)
                ->where('origen_id',$id)
                ->where('origen_table','Hallazgos')
                ->delete();
        
            CausasBasicasInmediata::where('sistema_id',session('sistema')->id)
                    ->where('origen_id',$id)
                    ->where('origen_table','Hallazgos')
                    ->delete();

            Hallazgo::where('sistema_id',session('sistema')->id)
                    ->where('id',$id)
                    ->delete();
        }
        
        
        return redirect()->route("hallazgos");
    }
    
    public static function verificarCierreHallazgo($idHallazgo){
        $totActividades = ActividadesHallazgo::where('sistema_id',  session('sistema')->id)
                ->where('hallazgo_id',$idHallazgo)
                ->get();
        
        $totActividadesEjecutadas = ActividadesHallazgo::where('sistema_id',  session('sistema')->id)
                ->where('hallazgo_id',$idHallazgo)
                ->where('ejecucionTotal',100)
                ->get();
        
        $totCapacitaciones = CapacitacionesHallazgo::where('sistema_id',  session('sistema')->id)
                ->where('hallazgo_id',$idHallazgo)
                ->get();
        
        $totCapacitacionesEjecutadas = CapacitacionesHallazgo::where('sistema_id',  session('sistema')->id)
                ->where('hallazgo_id',$idHallazgo)
                ->where('ejecucionTotal',100)
                ->get();
        
        if((count($totActividades) === count($totActividadesEjecutadas)) && (count($totCapacitaciones) === count($totCapacitacionesEjecutadas))){
            $cerrado = 'Si';
        }else{
            $cerrado = 'No';
        }
        
        Hallazgo::where('id',$idHallazgo)
                    ->where('sistema_id',  session('sistema')->id)
                    ->update([
                        'cerrado' => $cerrado
                    ]);
    }
}
