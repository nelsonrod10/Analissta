<?php

namespace App\Http\Controllers\Accidentes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\Accidentes\Accidente;
use App\PeligrosHallazgosAccidente;
use App\CausasBasicasInmediata;
use App\Accidentes\AccidentesCosto;
use App\Accidentes\AccidentesAfectacione;

class AccidentesController extends Controller
{
    public function principalAccidentes(){
        return view('analissta.Accidentes.vistaGeneral');
    }
    
    public function mostrarAccidente($id){
        
        
        $accidente = Accidente::find($id);
        return view('analissta.Accidentes.verAccidente')->with(['accidente'=>$accidente]);
    }


    public function datosGenerales($id=null){
        
        
        
        return view('analissta.Accidentes.crearAccidente.datosGenerales')->with(['idAccidente'=>$id]);
    }
    
    public function datosAccidente($id){
        
        
        
        return view('analissta.Accidentes.crearAccidente.datosAccidente')->with(['idAccidente'=>$id]);
    }
    
    public function afectacionCuerpo($id){
        
        
        
        return view('analissta.Accidentes.crearAccidente.afectacionCuerpo')->with(['idAccidente'=>$id]);
    }
    
    public function afectacionLesion($id){
        
        
        
        return view('analissta.Accidentes.crearAccidente.afectacionLesion')->with(['idAccidente'=>$id]);
    }
    
    public function afectacionAgente($id){
        
        
        
        return view('analissta.Accidentes.crearAccidente.afectacionAgentes')->with(['idAccidente'=>$id]);
    }
    
    public function afectacionFuente($id){
        
        
        
        return view('analissta.Accidentes.crearAccidente.afectacionFuentes')->with(['idAccidente'=>$id]);
    }
    
    public function peligroAsociado($id){
        
        
        return view('analissta.Accidentes.crearAccidente.peligroAsociado')->with(['idAccidente'=>$id]);
    }
    
    public function causasInmediatas($id){
        
        
        
        return view('analissta.Accidentes.crearAccidente.causasInmediatas')->with(['idAccidente'=>$id]);
    }
    
    public function causasBasicas($id){
        
        
        
        return view('analissta.Accidentes.crearAccidente.causasBasicas')->with(['idAccidente'=>$id]);
    }
    
    public function costos($id){
        
        
        
        return view('analissta.Accidentes.crearAccidente.costos')->with(['idAccidente'=>$id]);
    }
    
    public function crearDatosGenerales($id=null){
        $data = request()->validate([
            'cargo'        => 'string|required',
            'centro'       => 'integer|required',
            'proceso'      => 'string|required',
            'fecha'        => 'date|required',
            'hora'         => 'string|required',
            'clasificacion'=> 'string|required',
            'lugar'        => 'string|required',
            'descripcion'  => 'string|required',
        ],[
            'cargo.required'       => 'Debe seleccionar un origen',
            'centro.required'      => 'Debe seleccionar un centro de trabajo',
            'proceso.required'     => 'Debe seleccionar un Proceso',
            'fecha.required'       => 'Debe ingresar una fecha valida',
            'hora.required'        => 'Debe ingresar una hora valida',
            'clasificacion.required' => 'Seleccione una clasificacion',
            'lugar.required'       => 'Debe escribir el lugar exacto donde ocurrio el accidente',
            'descripcion.required' => 'Debe realizar un descripción del accidente',
            
            'cargo.string'     => 'Revise el formato de los datos',
            'centro.string'     => 'Revise el formato de los datos',
            'proceso.string'    => 'Revise el formato de los datos',
            'fecha.date'      => 'Revise el formato de los datos',
            'hora.string'      => 'Revise el formato de los datos',
            'clasificacion.string' => 'Revise el formato de los datos',
            'lugar.string' => 'Revise el formato de los datos',
            'descripcion.string' => 'Revise el formato de los datos',
        ]);
        if($id === null){
            $idAccidente = $this->createDatosGenerales($data);
        }else{
            $idAccidente = $this->updateDatosGenerales($id,$data);
        }
        
        return redirect()->route("datos-accidente",["id"=>$idAccidente]);
    }
    
    private function createDatosGenerales($data){
        $accidente = Accidente::create([
            'sistema_id'  => session('sistema')->id,
            'centrosTrabajo_id'  => $data["centro"],
            'proceso_id'         => $data["proceso"],
            'fechaAccidente'     => $data["fecha"],
            'horaAccidente'      => $data["hora"],
            'cargoResponsable'   => $data["cargo"],
            'clasificacion'      => $data["clasificacion"],
            'lugar'              => $data["lugar"],
            'descripcion'        => $data["descripcion"],
            'accidentado_id'     => 0,
        ]);
        
        PeligrosHallazgosAccidente::create([
            'sistema_id'  => session('sistema')->id,
            'origen_id'          => $accidente->id,
            'origen_table'       => "Accidentes",
            'factorHumano'       => "",
        ]);
        
        return $accidente->id;
    }
    
    private function updateDatosGenerales($idAccidente,$data){
        $accidente = Accidente::find($idAccidente);
        $accidente->centrosTrabajo_id     =  $data["centro"];
        $accidente->proceso_id            =  $data["proceso"];
        $accidente->fechaAccidente        =  $data["fecha"];
        $accidente->horaAccidente         =  $data["hora"];
        $accidente->cargoResponsable      =  $data["cargo"];
        $accidente->clasificacion         =  $data["clasificacion"];
        $accidente->lugar                 =  $data["lugar"];
        $accidente->descripcion           =  $data["descripcion"];
        $accidente->save();
        return $idAccidente;
    }
    
    public function crearDatosAccidentado($id){
        $data = request()->validate([
            'identificacion'  => 'integer|required',
            'tipoEvento'      => 'string|required',
            'accidenteGrave'  => 'string',
            'incapacidad'     => 'string|required',
            'afectacion'      => 'string|required',
            'empresa'         => 'string|required',
            'tipoEmpleado'    => 'string|required',
            'jornada'         => 'string|required',
            'laborHabitual'   => 'string|required',
        ],[
            'identificacion.required'       => 'Identificacion del Accidentado',
            'tipoEvento.required'      => 'Debe seleccionar un tipo de evento',
            'incapacidad.required'     => 'Debe seleccionar si generó incapacidad',
            'afectacion.required'       => 'Debe seleccionar un tipo de afectación',
            'empresa.required'        => 'Nombre Empresa involucrada',
            'tipoEmpleado.required' => 'Indique si el empleado es Directo o Contratista',
            'jornada.required'       => 'Jornadad Diurna o Nocturna',
            'laborHabitual.required' => 'Indique si la Labor es habitual',
            
            'identificacion.integer'       => 'Identificacion del Accidentado',
            'tipoEvento.string'      => 'Revise el formato de los datos',
            'incapacidad.string'     => 'Revise el formato de los datos',
            'afectacion.string'       => 'Revise el formato de los datos',
            'empresa.string'        => 'Revise el formato de los datos',
            'tipoEmpleado.string' => 'Revise el formato de los datos',
            'jornada.string'       => 'Revise el formato de los datos',
            'laborHabitual.string' => 'Revise el formato de los datos',
        ]);
        
        $accidente = Accidente::find($id);
        $accidente->accidentado_id        =  $data["identificacion"];
        $accidente->tipo_evento           =  $data["tipoEvento"];
        $accidente->accidente_grave       =  isset($data["accidenteGrave"])?$data["accidenteGrave"]:'No';
        $accidente->incapacidad           =  $data["incapacidad"];
        $accidente->afectacion            =  $data["afectacion"];
        $accidente->nombre_empresa        =  $data["empresa"];
        $accidente->empleado_tipo         =  $data["tipoEmpleado"];
        $accidente->jornada               =  $data["jornada"];
        $accidente->labor_habitual        =  $data["laborHabitual"];
        $accidente->save();
        
        return redirect()->route("afectacion-cuerpo-accidente",["id"=>$id]);
        
    }
    
    public function crearAfectacionCuerpo($id){
        $data = request()->validate([
            'partes'        => 'required|array',
        ],[
            'partes.required'   => 'Debe seleccionar por lo menos una opción',
        ]);
        
        AccidentesAfectacione::where('sistema_id',session('sistema')->id)
                ->where('accidente_id',$id)
                ->where('tipo','Cuerpo')
                ->delete();
        
        foreach($data['partes'] as $parte){
            $arrData = explode('-',$parte);
            AccidentesAfectacione::create([
                'sistema_id'   => session('sistema')->id ,
                'accidente_id'        => $id,
                'tipo'                =>'Cuerpo',
                'categoria'           => $arrData[0],
                'descripcion'         => $arrData[1]
            ]);
        }    
        return redirect()->route("afectacion-lesion-accidente",["id"=>$id]);
    }
    
    public function crearAfectacionLesion($id){
        $data = request()->validate([
            'lesiones'        => 'required|array',
        ],[
            'lesiones.required'   => 'Debe seleccionar por lo menos una opción',
        ]);
        
        AccidentesAfectacione::where('sistema_id',session('sistema')->id)
                ->where('accidente_id',$id)
                ->where('tipo','Lesion')
                ->delete();
        foreach($data['lesiones'] as $lesion){
            
            AccidentesAfectacione::create([
                'sistema_id'   => session('sistema')->id ,
                'accidente_id'        => $id,
                'tipo'                =>'Lesion',
                'categoria'           => 0,
                'descripcion'         => $lesion
            ]);
        }    
        return redirect()->route("afectacion-agente-accidente",["id"=>$id]);
    }
    
    public function crearAfectacionAgente($id){
        $data = request()->validate([
            'agentes'        => 'required|array',
        ],[
            'agentes.required'   => 'Debe seleccionar por lo menos una opción',
        ]);
        
        AccidentesAfectacione::where('sistema_id',session('sistema')->id)
                ->where('accidente_id',$id)
                ->where('tipo','Agentes')
                ->delete();
        
        foreach($data['agentes'] as $parte){
            $arrData = explode('-',$parte);
            AccidentesAfectacione::create([
                'sistema_id'   => session('sistema')->id ,
                'accidente_id'        => $id,
                'tipo'                =>'Agentes',
                'categoria'           => $arrData[0],
                'descripcion'         => $arrData[1]
            ]);
        }    
        return redirect()->route("afectacion-fuente-accidente",["id"=>$id]);
    }
    
    public function crearAfectacionFuente($id){
        $data = request()->validate([
            'fuentes'        => 'required|array',
        ],[
            'fuentes.required'   => 'Debe seleccionar por lo menos una opción',
        ]);
        
        AccidentesAfectacione::where('sistema_id',session('sistema')->id)
                ->where('accidente_id',$id)
                ->where('tipo','Fuentes')
                ->delete();
        
        foreach($data['fuentes'] as $parte){
            $arrData = explode('-',$parte);
            AccidentesAfectacione::create([
                'sistema_id'   => session('sistema')->id ,
                'accidente_id'        => $id,
                'tipo'                =>'Fuentes',
                'categoria'           => $arrData[0],
                'descripcion'         => $arrData[1]
            ]);
        }    
        return redirect()->route("peligro-asociado-accidente",["id"=>$id]);
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
            ->where('origen_table','Accidentes')
            ->update([
                "clasificacion"         =>  $data["clasificacion"],
                "categoria"             =>  $data["descripcion"],
                "subCategoria"          => isset($data["subdescripcion"])?$data["subdescripcion"]:0,
                "fuentes"               =>  implode(",", $data["fuentes"]),
                "especificacion"        =>  $data["especificacion"],
                "factorHumano"          => isset($data["factorH"])?$data["factorH"]:"N/A"
            ]);
        return redirect()->route("causas-inmediatas-accidente",['id'=>$id]);
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
                ->where('origen_table','Accidentes')
                ->where('tipo','Inmediata')
                ->where('factor',$data["factor"])
                ->where('categoria',$data["categoria"])
                ->delete();
        
        CausasBasicasInmediata::create([
            'sistema_id'  => session('sistema')->id,
            'origen_id'          => $id,
            'origen_table'       => "Accidentes",
            'tipo'               => "Inmediata",
            'factor'             => $data["factor"],
            'categoria'          => $data["categoria"],
            'descripcion'        => implode(",",$data["arrmedidas"]),
            'observaciones'      => $data["detalles"],
        ]);
        return redirect()->route("causas-inmediatas-accidente",['id'=>$id]);
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
                ->where('origen_table','Accidentes')
                ->where('tipo','Basica')
                ->where('factor',$data["factor"])
                ->where('categoria',$data["categoria"])
                ->delete();
        
        CausasBasicasInmediata::create([
            'sistema_id'  => session('sistema')->id,
            'origen_id'          => $id,
            'origen_table'       => "Accidentes",
            'tipo'               => "Basica",
            'factor'             => $data["factor"],
            'categoria'          => $data["categoria"],
            'descripcion'        => implode(",",$data["arrmedidas"]),
            'observaciones'      => $data["detalles"],
        ]);
        return redirect()->route("causas-basicas-accidente",['id'=>$id]);
    }
    
    public function eliminarCausaInmediata($idAccidente, $idCausa){
        CausasBasicasInmediata::where('id',$idCausa)
                ->where('sistema_id',session('sistema')->id)
                ->where('origen_id',$idAccidente)
                ->where('origen_table','Accidentes')
                ->where('tipo','Inmediata')
                ->delete();
        return redirect()->route("causas-inmediatas-accidente",['id'=>$idAccidente]);
    }
    
    public function eliminarCausaBasica($idAccidente, $idCausa){
        CausasBasicasInmediata::where('id',$idCausa)
                ->where('sistema_id',session('sistema')->id)
                ->where('origen_id',$idAccidente)
                ->where('origen_table','Accidentes')
                ->where('tipo','Basica')
                ->delete();
        return redirect()->route("causas-basicas-accidente",['id'=>$idAccidente]);
    }
    
    public function crearCostos($id){
        $data = request()->validate([
            'directos'          => 'string|required',
            'seguimiento'       => 'string|required',
            'relevo'            => 'string|required',
            'imagen'            => 'string|required',
            'parada'            => 'string|required',
            'legal'             => 'string|required',
            'productividad'     => 'string|required',
        ],[
            'directos.required' => 'Ingrese los costos directos',
            'seguimiento.required' => 'Ingrese los costos por seguimiento',
            'relevo.required' => 'Ingrese los costos por personal',
            'imagen.required' => 'Ingrese los costos por imagen',
            'parada.required' => 'Ingrese los costos por parada de operación',
            'legal.required' => 'Ingrese los costos legales',
            'productividad.required' => 'Ingrese los costos por productividad',
        ]);
        AccidentesCosto::where('sistema_id',session('sistema')->id)
                ->where('accidente_id',$id)
                ->delete();
        
        AccidentesCosto::create([
           'sistema_id'      => session('sistema')->id,
           'accidente_id'           => $id, 
           'costos'      => $data["directos"],
           'persona'     => $data["relevo"],
           'operacion'   => $data["parada"],
           'productividad'      => $data["productividad"], 
           'seguimiento'        => $data["seguimiento"], 
           'imagen_corporativa' => $data["imagen"],  
           'legales'     => $data["legal"],  
        ]);
        
        return redirect()->route("accidente",['id'=>$id]);
    }
    
    public function cancelarAccidente($id=null){
        Accidente::where('sistema_id',session('sistema')->id)
                ->where('id',$id)
                ->delete();
        CausasBasicasInmediata::where('sistema_id',session('sistema')->id)
                ->where('origen_id',$id)
                ->where('origen_table','Accidentes')
                ->delete();
        
        return redirect()->route("accidentes");
    }
}
