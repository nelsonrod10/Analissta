<?php

namespace App\Http\Controllers\Hallazgos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\Hallazgos\Hallazgo;
use App\Hallazgos\HallazgosCierre;
use App\EmpresaCliente;
use App\ActividadesHallazgo;
use App\CapacitacionesHallazgo;
class CierreHallazgosController extends Controller
{
    public function cierreHallazgo($id){
        date_default_timezone_set('America/Bogota');
        $objFechaActual = helpers::getDateNow();
        $fechaActual = $objFechaActual->format("Y-m-d");
    
        $data = request()->validate([
            'eficaz'         => 'string|required',
            'optimizar'      => 'string',
            'nuevaFecha'     => 'date',
            'observaciones'  => 'string|required',
            'evidencias'     => 'string|required',
        ],[
            'eficaz.required'        => 'Seleccione si el cierre fue eficaz',
            'observaciones.required' => 'Observaciones sobre el cierre',
            'evidencias.required'    => 'Evidencias del cierre del hallazgo',
            
            'eficaz.string'        => 'Revise el formato de los datos 1',
            'optimizar.string'     => 'Revise el formato de los datos 2',
            'nuevaFecha.date'      => 'Revise el formato de los datos 3',
            'observaciones.string' => 'Revise el formato de los datos 4',
            'evidencias.string'    => 'Revise el formato de los datos 5',
        ]);
        
        $cierre = HallazgosCierre::create([
            'sistema_id'     =>  session('sistema')->id,
            'hallazgo_id'           =>  $id,
            'fechaCierrePropuesta'  =>  $data["nuevaFecha"],
            'fechaReapertura'       =>  $fechaActual,
            'eficaz'                =>  $data["eficaz"],
            'observaciones'         =>  $data["observaciones"],
            'evidencias'            =>  $data["evidencias"],
            'optimizar'             =>  $data["optimizar"],
        ]);
        /*Definir a donde enviar si eficaz es igual a SI o NO, 
         * si es NO definir si va a capacitaciones o a actividades*/
        if($data["eficaz"] == 'Si'){
            return redirect()->route('hallazgo',['id'=>$id]);
        }else{
            if($data["optimizar"] === "Capacitaciones"){
                return redirect()->route("capacitaciones-cierre-hallazgo",["id"=>$id,"idCierre"=>$cierre->id]);
            }else{
                return redirect()->route("actividades-cierre-hallazgo",["id"=>$id,"idCierre"=>$cierre->id]);
            }
        }
        
        
    }
    
    public function actividades($id,$idCierre){
        
        
        
        return view('analissta.Hallazgos.cierreHallazgo.actividades')->with(['idHallazgo'=>$id,'idCierre'=>$idCierre]);
    }
    
    public function capacitaciones($id,$idCierre){
        
        
        
        return view('analissta.Hallazgos.cierreHallazgo.capacitaciones')->with(['idHallazgo'=>$id,'idCierre'=>$idCierre]);
    }
    
    public function crearActividades($id,$idCierre){
        $data = request()->validate([
            'nombre'            => 'string|required',
        ],[
            'nombre.required'      => 'Debe seleccionar por lo menos una opción',
        ]);
        
        ActividadesHallazgo::create([
            'sistema_id'  => session('sistema')->id,
            'hallazgo_id'        => $id,
            'medida'             => "hallazgos",
            'reapertura'         => "Si",
            'reapertura_id'      => $idCierre,
            'nombre'             => $data["nombre"],
        ]);
        return redirect()->route("actividades-cierre-hallazgo",["id"=>$id,"idCierre"=>$idCierre]);
    }
    
    public function crearCapacitaciones($id,$idCierre){
        $data = request()->validate([
            'nombre'            => 'string|required',
        ],[
            'nombre.required'      => 'Debe seleccionar por lo menos una opción',
        ]);
        
        CapacitacionesHallazgo::create([
            'sistema_id'  => session('sistema')->id,
            'hallazgo_id'        => $id,
            'medida'             => "hallazgos",
            'reapertura'         => "Si",
            'reapertura_id'      => $idCierre,
            'nombre'             => $data["nombre"],
        ]);
        return redirect()->route("capacitaciones-cierre-hallazgo",["id"=>$id,"idCierre"=>$idCierre]);
    }
    
    public function eliminarActividad($idHallazgo,$idCierre,$idActividad){
        ActividadesHallazgo::where('sistema_id',  session('sistema')->id)
            ->where('id',$idActividad)    
            ->where('hallazgo_id',$idHallazgo)
            ->where('medida','hallazgos')
            ->where('reapertura_id',$idCierre)    
            ->delete();
        return redirect()->route("actividades-cierre-hallazgo",["id"=>$idHallazgo,"idCierre"=>$idCierre]);
    }
    
    public function eliminarCapacitacion($idHallazgo,$idCierre,$idCapacitacion){
        CapacitacionesHallazgo::where('sistema_id',  session('sistema')->id)
            ->where('id',$idCapacitacion)    
            ->where('hallazgo_id',$idHallazgo)
            ->where('medida','hallazgos')
            ->where('reapertura_id',$idCierre)    
            ->delete();
        return redirect()->route("capacitaciones-cierre-hallazgo",["id"=>$idHallazgo,"idCierre"=>$idCierre]);
    }
    
    public function cancelarCierre($idHallazgo,$idCierre){
        HallazgosCierre::where('id',$idCierre)
                ->where('sistema_id',session('sistema')->id)
                ->where('hallazgo_id',$idHallazgo)
                ->delete();
        ActividadesHallazgo::where('sistema_id',session('sistema')->id)
                ->where('hallazgo_id',$idHallazgo)
                ->where('reapertura_id',$idHallazgo)
                ->delete();
        CapacitacionesHallazgo::where('sistema_id',session('sistema')->id)
                ->where('hallazgo_id',$idHallazgo)
                ->where('reapertura_id',$idHallazgo)
                ->delete();
        
        return redirect()->route('hallazgo',['id'=>$idHallazgo]);
    }
    
    public function finalizarCierre($id){
        Hallazgo::where('sistema_id', session('sistema')->id)
                ->where('id',$id)
                ->update([
                    'cerrado' => 'No'
                ]);
        
        return redirect()->route('hallazgo',['id'=>$id]);
                
    }
}
