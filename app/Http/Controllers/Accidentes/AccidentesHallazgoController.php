<?php

namespace App\Http\Controllers\Accidentes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\Accidentes\Accidente;
use App\Hallazgos\Hallazgo;
use App\PeligrosHallazgosAccidente;
use App\Accidentes\AccidentesHallazgo;
use App\CausasBasicasInmediata;
use App\ActividadesHallazgo;
use App\CapacitacionesHallazgo;

class AccidentesHallazgoController extends Controller
{
    public function datosGenerales($idAccidente,$idHallazgo=null){
        $accidente = Accidente::where('sistema_id',  session('sistema')->id)
                ->where('id',$idAccidente)
                ->get();
        $hallazgo = Hallazgo::create([
            'sistema_id'  => session('sistema')->id,
            'centrosTrabajo_id'  => $accidente[0]->centrosTrabajo_id,
            'proceso_id'         => $accidente[0]->proceso_id,
            'origen_id'          => 3,
            'fechaHallazgo'      => $accidente[0]->fechaAccidente,
            'cargoResponsable'   => $accidente[0]->cargoResponsable,
            'descripcion'        => $accidente[0]->descripcion,
            'fechaCierre'        => $accidente[0]->fechaAccidente,
        ]);
        
        $this->crearPeligroHallazgo($hallazgo->id,$accidente[0]->id);
        $this->crearCausasBasicas($hallazgo->id, $accidente[0]->id);
        $this->crearCausasInmediatas($hallazgo->id, $accidente[0]->id);
        $this->crearPivotAccidenteHallazgo($accidente[0]->id,$hallazgo->id);
        
        return redirect()->route('acciones-hallazgo-accidente',['idAccidente'=>$accidente[0]->id]);
        
    }
    
    private function crearPeligroHallazgo($idHallazgo,$idAccidente){
        $peligroAccidente = PeligrosHallazgosAccidente::where('sistema_id', session('sistema')->id)
                ->where('origen_id',$idAccidente)
                ->where('origen_table','Accidentes')
                ->get();
        PeligrosHallazgosAccidente::create([
            'sistema_id'  => session('sistema')->id,
            'origen_id'          => $idHallazgo,
            'origen_table'       => "Hallazgos",
            "clasificacion"      => $peligroAccidente[0]->clasificacion,
            "categoria"          => $peligroAccidente[0]->categoria,
            "subCategoria"       => $peligroAccidente[0]->subCategoria,
            "fuentes"            => $peligroAccidente[0]->fuentes,
            "especificacion"     => $peligroAccidente[0]->especificacion,
            "factorHumano"       => $peligroAccidente[0]->factorHumano
        ]);
        
        return;
    }
    
    private function crearCausasBasicas($idHallazgo, $idAccidente){
        $causasBasicasAccidente = CausasBasicasInmediata::where('sistema_id',session('sistema')->id)
                ->where('origen_id',$idAccidente)
                ->where('origen_table','Accidentes')
                ->where('tipo','Basica')
                ->get(); 
        foreach ($causasBasicasAccidente as $causa) {
            CausasBasicasInmediata::create([
                'sistema_id'  => session('sistema')->id,
                'origen_id'          => $idHallazgo,
                'origen_table'       => "Hallazgos",
                'tipo'               => "Basica",
                'factor'             => $causa->factor,
                'categoria'          => $causa->categoria,
                'descripcion'        => $causa->descripcion,
                'observaciones'      => $causa->observaciones,
            ]);
        }
        return;
    }
    
    private function crearCausasInmediatas($idHallazgo, $idAccidente){
        $causasInmediatasAccidente = CausasBasicasInmediata::where('sistema_id',session('sistema')->id)
                ->where('origen_id',$idAccidente)
                ->where('origen_table','Accidentes')
                ->where('tipo','Inmediata')
                ->get(); 
        foreach ($causasInmediatasAccidente as $causa) {
            CausasBasicasInmediata::create([
                'sistema_id'  => session('sistema')->id,
                'origen_id'          => $idHallazgo,
                'origen_table'       => "Hallazgos",
                'tipo'               => "Inmediata",
                'factor'             => $causa->factor,
                'categoria'          => $causa->categoria,
                'descripcion'        => $causa->descripcion,
                'observaciones'      => $causa->observaciones,
            ]);
        }
        return;
    }
    
    private function crearPivotAccidenteHallazgo($idAccidente,$idHallazgo){
        AccidentesHallazgo::create([
            'sistema_id'  => session('sistema')->id,
            'accidente_id'       => $idAccidente,
            'hallazgo_id'        => $idHallazgo,
        ]);
        
        return;
    }
    
    public function acciones($idAccidente){
        
        
        $accidente = Accidente::where('sistema_id',session('sistema')->id)
                ->where('id',$idAccidente)
                ->get();
        return view('analissta.Accidentes.crearHallazgo.acciones')->with(['accidente'=>$accidente[0]]);
    }
    
    public function actividades($idAccidente){
        
        
        $accidente = Accidente::where('sistema_id',session('sistema')->id)
                ->where('id',$idAccidente)
                ->get();
        return view('analissta.Accidentes.crearHallazgo.actividades')->with(['accidente'=>$accidente[0]]);
    }
    
    public function capacitaciones($idAccidente){
        
        
        $accidente = Accidente::where('sistema_id',session('sistema')->id)
                ->where('id',$idAccidente)
                ->get();
        return view('analissta.Accidentes.crearHallazgo.capacitaciones')->with(['accidente'=>$accidente[0]]);
    }
    
    public function crearAcciones($idAccidente){
        $data = request()->validate([
            'idHallazgo'         => 'string|required',
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
            ->where('hallazgo_id',$data["idHallazgo"])
            ->where('medida','hallazgos')
            ->delete();
        
        CapacitacionesHallazgo::where('sistema_id',  session('sistema')->id)
            ->where('hallazgo_id',$data["idHallazgo"])
            ->where('medida','hallazgos')
            ->delete();
        
        $hallazgo = Hallazgo::find($data["idHallazgo"]);
        $hallazgo->actoCondicion     =  $data["actoCondicion"];
        $hallazgo->tipoAccion        =  $data["tipoAccion"];
        $hallazgo->planAccion        =  $data["tipoPlanAccion"];
        $hallazgo->fechaCierre       =  $data["fechaCierre"];
        $hallazgo->save();
        
        if($data["tipoPlanAccion"] === "Capacitaciones"){
            return redirect()->route("capacitaciones-hallazgo-accidente",['idAccidente'=>$idAccidente]);
        }else{
            return redirect()->route("actividades-hallazgo-accidente",['idAccidente'=>$idAccidente]);
        }
        
    }
    
    public function crearActividades($idAccidente){
        $data = request()->validate([
            'idHallazgo'        => 'string|required',
            'nombre'            => 'string|required',
        ],[
            'nombre.required'      => 'Debe seleccionar por lo menos una opción',
        ]);
        
        ActividadesHallazgo::create([
            'sistema_id'  => session('sistema')->id,
            'hallazgo_id'        => $data["idHallazgo"],
            'medida'             => "hallazgos",
            'nombre'             => $data["nombre"],
        ]);
        return redirect()->route("actividades-hallazgo-accidente",["idAccidente"=>$idAccidente]);
    }
    
    public function crearCapacitaciones($idAccidente){
        $data = request()->validate([
            'idHallazgo'        => 'string|required',
            'nombre'            => 'string|required',
        ],[
            'nombre.required'      => 'Debe seleccionar por lo menos una opción',
        ]);
        
        CapacitacionesHallazgo::create([
            'sistema_id'  => session('sistema')->id,
            'hallazgo_id'        => $data["idHallazgo"],
            'medida'             => "hallazgos",
            'nombre'             => $data["nombre"],
        ]);
        return redirect()->route("capacitaciones-hallazgo-accidente",["idAccidente"=>$idAccidente]);
    }
    
    public function eliminarActividad($idAccidente){
        $data = request()->validate([
            'idHallazgo'    => 'string|required',
            'idActividad'   => 'string|required',
        ]);
        ActividadesHallazgo::where('sistema_id',  session('sistema')->id)
            ->where('id',$data["idActividad"])    
            ->where('hallazgo_id',$data["idHallazgo"])
            ->where('medida','hallazgos')
            ->delete();
        return redirect()->route("actividades-hallazgo-accidente",["idAccidente"=>$idAccidente]);
    }
    
    public function eliminarCapacitacion($idAccidente){
        $data = request()->validate([
            'idHallazgo'    => 'string|required',
            'idCapacitacion'   => 'string|required',
        ]);
        CapacitacionesHallazgo::where('sistema_id',  session('sistema')->id)
            ->where('id',$data["idCapacitacion"])    
            ->where('hallazgo_id',$data["idHallazgo"])
            ->where('medida','hallazgos')
            ->delete();
        return redirect()->route("capacitaciones-hallazgo-accidente",["idAccidente"=>$idAccidente]);
    }
    
    public function cancelarHallazgo($idAccidente,$idHallazgo){
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
        
        AccidentesHallazgo::where('sistema_id',session('sistema')->id)
                ->where('accidente_id',$idAccidente)
                ->where('hallazgo_id',$idHallazgo)
                ->delete();
        
        return redirect()->route('accidente',['id'=>$idAccidente]);
    }
}
