<?php

namespace App\Http\Controllers\PlanesGestion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\PlanesGestion\PveSeguridade;
use App\PlanesGestion\PveSeguridadLineaBase;
use App\PlanesGestion\PveSeguridadMeta;

class PveSeguridadController extends Controller
{
    public function guardarData(){
        $data = request()->validate([
            'idPve'    => 'string|required',
            'nombre'    => 'string|required',
            'cargo'     => 'string|required',
            'objetivo'  => 'string|required',
            'lineaBase'  => 'string|required',
            'frecAnalisis'   => 'string|required',
            'cobertura'      => 'integer|required',
            'cumplimiento'   => 'string|required',
            'frecAnalisis'   => 'string|required',
            'alcance'   => 'string|required',
        ],[
            'idPve.required'    => 'Diligencie todos los campos',
            'nombre.required'    => 'Diligencie todos los campos',
            'cargo.required'     => 'Diligencie todos los campos',
            'objetivo.required'  => 'Diligencie todos los campos',
            'lineaBase.required'  => 'Diligencie todos los campos',
            'frecAnalisis.required'   => 'Diligencie todos los campos',
            'cobertura.required'      => 'Diligencie todos los campos',
            'cumplimiento.required'   => 'Diligencie todos los campos',
            'frecAnalisis.required'   => 'Diligencie todos los campos',
            'alcance.required.required'   => 'Diligencie todos los campos',
            
            'nombre.string'    => 'Diligencie todos los campos',
            'cargo.string'     => 'Diligencie todos los campos',
            'objetivo.string'  => 'Diligencie todos los campos',
            'lineaBase.string'  => 'Diligencie todos los campos',
            'frecAnalisis.string'   => 'Diligencie todos los campos',
            'cobertura.integer'      => 'Diligencie todos los campos',
            'cumplimiento.string'   => 'Diligencie todos los campos',
            'frecAnalisis.string'   => 'Diligencie todos los campos',
            'alcance.string'   => 'Diligencie todos los campos',
        ]);
        
        $this->configurarMetas($data['idPve']);
        
        if($data['lineaBase'] === 'SI'){
            $this->configurarLineaBase($data['idPve']);
        }
        
        PveSeguridade::where('id',$data['idPve'])
                ->where('sistema_id',session('sistema')->id)
                ->update([
                   'estado'  => 'Programado', //
                   'nombre'   => $data['nombre'],
                   'cargo'    => $data['cargo'],
                   'objetivo' => $data['objetivo'],
                   'alcance'  => $data['alcance'],
                   'cobertura'  => $data['cobertura'],
                   'cumplimiento'  => $data['cumplimiento'],
                   'frecuencia_analisis'  => $data['frecAnalisis'], 
                ]);
        
        return;
    }
    
    private function configurarMetas($idPve){
        $data = request()->validate([
            'objetivo-incidencia' => 'string|required',
            'meta-incidencia'     => 'string|required',
            'unidad-incidencia'   => 'string|required',
            'objetivo-prevalencia'  => 'string|required',
            'meta-prevalencia'      => 'string|required',
            'unidad-prevalencia'    => 'string|required',
            'objetivo-casos-nuevos' => 'string|required',
            'meta-casos-nuevos'     => 'string|required',
            'unidad-casos-nuevos'   => 'string|required',
        ],[
            'objetivo-incidencia.required' => 'Diligencie todos los datos',
            'meta-incidencia.required'     => 'Diligencie todos los datos',
            'unidad-incidencia.required'   => 'Diligencie todos los datos',
            'objetivo-prevalencia.required'  => 'Diligencie todos los datos',
            'meta-prevalencia.required'      => 'Diligencie todos los datos',
            'unidad-prevalencia.required'    => 'Diligencie todos los datos',
            'objetivo-casos-nuevos.required' => 'Diligencie todos los datos',
            'meta-casos-nuevos.required'     => 'Diligencie todos los datos',
            'unidad-casos-nuevos.required'   => 'Diligencie todos los datos',
            
            'objetivo-incidencia.string' => 'Diligencie todos los datos',
            'meta-incidencia.string'     => 'Diligencie todos los datos',
            'unidad-incidencia.string'   => 'Diligencie todos los datos',
            'objetivo-prevalencia.string'  => 'Diligencie todos los datos',
            'meta-prevalencia.string'      => 'Diligencie todos los datos',
            'unidad-prevalencia.string'    => 'Diligencie todos los datos',
            'objetivo-casos-nuevos.string' => 'Diligencie todos los datos',
            'meta-casos-nuevos.string'     => 'Diligencie todos los datos',
            'unidad-casos-nuevos.string'   => 'Diligencie todos los datos',
        ]);
        
        $this->guardarMetasIncidencia($idPve,$data['objetivo-incidencia'],$data['meta-incidencia'],$data['unidad-incidencia']);
        $this->guardarMetasPrevalencia($idPve,$data['objetivo-prevalencia'],$data['meta-prevalencia'],$data['unidad-prevalencia']);
        $this->guardarMetasMortalidad($idPve,$data['objetivo-casos-nuevos'],$data['meta-casos-nuevos'],$data['unidad-casos-nuevos']);
    }
    
    private function guardarMetasIncidencia($idPve,$objetivo,$meta,$unidad){
        $existeIncidencia = PveSeguridadMeta::where('pve_seguridades_id',$idPve)
                ->where('nombreMeta','Eficacia Incidencia')
                ->get();
        
        if(count($existeIncidencia)>0){
            $existeIncidencia[0]->objetivo  = $objetivo;
            $existeIncidencia[0]->unidad    = $unidad;
            $existeIncidencia[0]->valorMeta = $meta;
            $existeIncidencia[0]->save();
            return;
        }
        
        PveSeguridadMeta::create([
           "nombreMeta" => 'Eficacia Incidencia',
           "pve_seguridades_id" => $idPve, 
           "objetivo" => $objetivo,
           "unidad"   => $unidad,
           "valorMeta"     => $meta, 
        ]);
        return;
    }
    
    private function guardarMetasPrevalencia($idPve,$objetivo,$meta,$unidad){
        $existePrevalencia = PveSeguridadMeta::where('pve_seguridades_id',$idPve)
                ->where('nombreMeta','Eficacia Prevalencia')
                ->get();
        
        if(count($existePrevalencia)>0){
            $existePrevalencia[0]->objetivo  = $objetivo;
            $existePrevalencia[0]->unidad    = $unidad;
            $existePrevalencia[0]->valorMeta = $meta;
            $existePrevalencia[0]->save();
            return;
        }
        
        PveSeguridadMeta::create([
           "nombreMeta" => 'Eficacia Prevalencia',
           "pve_seguridades_id" => $idPve,
           "objetivo" => $objetivo,
           "unidad"   => $unidad,
           "valorMeta"     => $meta, 
        ]);
        return;
    }
    
    private function guardarMetasMortalidad($idPve,$objetivo,$meta,$unidad){
        $existeCasosNuevos = PveSeguridadMeta::where('pve_seguridades_id',$idPve)
                ->where('nombreMeta','Eficacia Casos Nuevos')
                ->get();
        
        if(count($existeCasosNuevos)>0){
            $existeCasosNuevos[0]->objetivo  = $objetivo;
            $existeCasosNuevos[0]->unidad    = $unidad;
            $existeCasosNuevos[0]->valorMeta = $meta;
            $existeCasosNuevos[0]->save();
            return;
        }
        
        PveSeguridadMeta::create([
           "nombreMeta" => 'Eficacia Casos Nuevos',
           "pve_seguridades_id" => $idPve, 
           "objetivo" => $objetivo,
           "unidad"   => $unidad,
           "valorMeta"     => $meta, 
        ]);
        return;
    }
    
    private function configurarLineaBase($idPve){
        $data = request()->validate([
            'incidencia'=> 'array|required',
            'prevalencia' => 'array|required',
            'casos-nuevos'=> 'array|required',
        ],[
            'incidencia.required'=> 'Diligencie todos los campos',
            'prevalencia.required' => 'Diligencie todos los campos',
            'casos-nuevos.required'=> 'Diligencie todos los campos',
            
            'incidencia.array'=> 'Diligencie todos los campos',
            'prevalencia.array' => 'Diligencie todos los campos',
            'casos-nuevos.array'=> 'Diligencie todos los campos',
        ]);
        
        $this->lineaBaseIncidencia($idPve,$data["incidencia"]);
        $this->lineaBasePrevalencia($idPve,$data["prevalencia"]);
        $this->lineaBaseMortalidad($idPve,$data["casos-nuevos"]);
        
    }
    
    private function lineaBaseIncidencia($idPve,$arrIncidencia){
        $existeBaseIncidencia = PveSeguridadLineaBase::where('pve_seguridades_id',$idPve)
                ->where('nombreMeta','Eficacia Incidencia')
                ->get();
        
        if(count($existeBaseIncidencia)>0){
            $baseIncidencia = $existeBaseIncidencia[0];
        }else{
            $baseIncidencia = PveSeguridadLineaBase::create([
                'pve_seguridades_id' => $idPve,
                'nombreMeta'      => 'Eficacia Incidencia',
             ]);
        }
        
        for($i=0;$i<count($arrIncidencia);$i++){
            $mes = helpers::meses_de_numero_a_texto($i);
            $baseIncidencia->$mes = $arrIncidencia[$i];
        }
        $baseIncidencia->save();
    }
    
    private function lineaBasePrevalencia($idPve,$arrPrevalencia){
        $existeBasePrevalencia = PveSeguridadLineaBase::where('pve_seguridades_id',$idPve)
                ->where('nombreMeta','Eficacia Prevalencia')
                ->get();
        
        if(count($existeBasePrevalencia)>0){
            $basePrevalencia = $existeBasePrevalencia[0];
        }else{
            $basePrevalencia = PveSeguridadLineaBase::create([
                'pve_seguridades_id' => $idPve,
                'nombreMeta'      => 'Eficacia Prevalencia',
             ]);
        }
        
        for($i=0;$i<count($arrPrevalencia);$i++){
            $mes = helpers::meses_de_numero_a_texto($i);
            $basePrevalencia->$mes = $arrPrevalencia[$i];
        }
        $basePrevalencia->save();
    }
    
    private function lineaBaseMortalidad($idPve,$arrMortalidad){
        $existeBaseCasosNuevos = PveSeguridadLineaBase::where('pve_seguridades_id',$idPve)
                ->where('nombreMeta','Eficacia Casos Nuevos')
                ->get();
        
        if(count($existeBaseCasosNuevos)>0){
            $baseCasosNuevos = $existeBaseCasosNuevos[0];
        }else{
            $baseCasosNuevos = PveSeguridadLineaBase::create([
                'pve_seguridades_id' => $idPve,
                'nombreMeta'      => 'Eficacia Casos Nuevos',
             ]);
        }
        
        for($i=0;$i<count($arrMortalidad);$i++){
            $mes = helpers::meses_de_numero_a_texto($i);
            $baseCasosNuevos->$mes = $arrMortalidad[$i];
        }
        $baseCasosNuevos->save();
    }
}
