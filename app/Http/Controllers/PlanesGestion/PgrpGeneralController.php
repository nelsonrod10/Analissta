<?php

namespace App\Http\Controllers\PlanesGestion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\PlanesGestion\PgrpGenerale;
use App\PlanesGestion\PgrpGeneralLineaBase;
use App\PlanesGestion\PgrpGeneralMeta;

class PgrpGeneralController extends Controller
{
    public function guardarData(){
        $data = request()->validate([
            'idPgrp'    => 'string|required',
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
            'idPgrp.required'    => 'Diligencie todos los campos',
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
        
        $this->configurarMetas($data['idPgrp']);
        
        if($data['lineaBase'] === 'SI'){
            $this->configurarLineaBase($data['idPgrp']);
        }
        
        PgrpGenerale::where('id',$data['idPgrp'])
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
    
    private function configurarMetas($idPgrp){
        $data = request()->validate([
            'objetivo-frecuencia' => 'string|required',
            'meta-frecuencia'     => 'string|required',
            'unidad-frecuencia'   => 'string|required',
            'objetivo-severidad'  => 'string|required',
            'meta-severidad'      => 'string|required',
            'unidad-severidad'    => 'string|required',
            'objetivo-ili'        => 'string|required',
            'meta-ili'            => 'string|required',
            'unidad-ili'          => 'string|required',
            'objetivo-mortalidad' => 'string|required',
            'meta-mortalidad'     => 'string|required',
            'unidad-mortalidad'   => 'string|required',
        ],[
            'objetivo-frecuencia.required' => 'Diligencie todos los datos',
            'meta-frecuencia.required'     => 'Diligencie todos los datos',
            'unidad-frecuencia.required'   => 'Diligencie todos los datos',
            'objetivo-severidad.required'  => 'Diligencie todos los datos',
            'meta-severidad.required'      => 'Diligencie todos los datos',
            'unidad-severidad.required'    => 'Diligencie todos los datos',
            'objetivo-ili.required'        => 'Diligencie todos los datos',
            'meta-ili.required'            => 'Diligencie todos los datos',
            'unidad-ili.required'          => 'Diligencie todos los datos',
            'objetivo-mortalidad.required' => 'Diligencie todos los datos',
            'meta-mortalidad.required'     => 'Diligencie todos los datos',
            'unidad-mortalidad.required'   => 'Diligencie todos los datos',
            
            'objetivo-frecuencia.string' => 'Diligencie todos los datos',
            'meta-frecuencia.string'     => 'Diligencie todos los datos',
            'unidad-frecuencia.string'   => 'Diligencie todos los datos',
            'objetivo-severidad.string'  => 'Diligencie todos los datos',
            'meta-severidad.string'      => 'Diligencie todos los datos',
            'unidad-severidad.string'    => 'Diligencie todos los datos',
            'objetivo-ili.string'        => 'Diligencie todos los datos',
            'meta-ili.string'            => 'Diligencie todos los datos',
            'unidad-ili.string'          => 'Diligencie todos los datos',
            'objetivo-mortalidad.string' => 'Diligencie todos los datos',
            'meta-mortalidad.string'     => 'Diligencie todos los datos',
            'unidad-mortalidad.string'   => 'Diligencie todos los datos',
        ]);
        
        $this->guardarMetasFrecuencia($idPgrp,$data['objetivo-frecuencia'],$data['meta-frecuencia'],$data['unidad-frecuencia']);
        $this->guardarMetasSeveridad($idPgrp,$data['objetivo-severidad'],$data['meta-severidad'],$data['unidad-severidad']);
        $this->guardarMetasIli($idPgrp,$data['objetivo-ili'],$data['meta-ili'],$data['unidad-ili']);
        $this->guardarMetasMortalidad($idPgrp,$data['objetivo-mortalidad'],$data['meta-mortalidad'],$data['unidad-mortalidad']);
    }
    
    private function guardarMetasFrecuencia($idPgrp,$objetivo,$meta,$unidad){
        $existeFrecuencia = PgrpGeneralMeta::where('pgrp_generales_id',$idPgrp)
                ->where('nombreMeta','Eficacia Frecuencia')
                ->get();
        
        if(count($existeFrecuencia)>0){
            $existeFrecuencia[0]->objetivo  = $objetivo;
            $existeFrecuencia[0]->unidad    = $unidad;
            $existeFrecuencia[0]->valorMeta = $meta;
            $existeFrecuencia[0]->save();
            return;
        }
        
        PgrpGeneralMeta::create([
           "nombreMeta" => 'Eficacia Frecuencia',
           "pgrp_generales_id" => $idPgrp, 
           "objetivo" => $objetivo,
           "unidad"   => $unidad,
           "valorMeta"     => $meta, 
        ]);
        return;
    }
    
    private function guardarMetasSeveridad($idPgrp,$objetivo,$meta,$unidad){
        $existeSeveridad = PgrpGeneralMeta::where('pgrp_generales_id',$idPgrp)
                ->where('nombreMeta','Eficacia Severidad')
                ->get();
        
        if(count($existeSeveridad)>0){
            $existeSeveridad[0]->objetivo  = $objetivo;
            $existeSeveridad[0]->unidad    = $unidad;
            $existeSeveridad[0]->valorMeta = $meta;
            $existeSeveridad[0]->save();
            return;
        }
        
        PgrpGeneralMeta::create([
           "nombreMeta" => 'Eficacia Severidad',
           "pgrp_generales_id" => $idPgrp,
           "objetivo" => $objetivo,
           "unidad"   => $unidad,
           "valorMeta"     => $meta, 
        ]);
        return;
    }
    
    private function guardarMetasIli($idPgrp,$objetivo,$meta,$unidad){
        $existeili = PgrpGeneralMeta::where('pgrp_generales_id',$idPgrp)
                ->where('nombreMeta','Eficacia ili')
                ->get();
        
        if(count($existeili)>0){
            $existeili[0]->objetivo  = $objetivo;
            $existeili[0]->unidad    = $unidad;
            $existeili[0]->valorMeta = $meta;
            $existeili[0]->save();
            return;
        }
        
        PgrpGeneralMeta::create([
           "nombreMeta" => 'Eficacia ili',
           "pgrp_generales_id" => $idPgrp,
           "objetivo" => $objetivo,
           "unidad"   => $unidad,
           "valorMeta"     => $meta, 
        ]);
        return;
    }
    
    private function guardarMetasMortalidad($idPgrp,$objetivo,$meta,$unidad){
        $existeTasaMortalidad = PgrpGeneralMeta::where('pgrp_generales_id',$idPgrp)
                ->where('nombreMeta','Eficacia Tasa Mortalidad')
                ->get();
        
        if(count($existeTasaMortalidad)>0){
            $existeTasaMortalidad[0]->objetivo  = $objetivo;
            $existeTasaMortalidad[0]->unidad    = $unidad;
            $existeTasaMortalidad[0]->valorMeta = $meta;
            $existeTasaMortalidad[0]->save();
            return;
        }
        
        PgrpGeneralMeta::create([
           "nombreMeta" => 'Eficacia Tasa Mortalidad',
           "pgrp_generales_id" => $idPgrp, 
           "objetivo" => $objetivo,
           "unidad"   => $unidad,
           "valorMeta"     => $meta, 
        ]);
        return;
    }
    
    private function configurarLineaBase($idPgrp){
        $data = request()->validate([
            'frecuencia'=> 'array|required',
            'severidad' => 'array|required',
            'ili'       => 'array|required',
            'mortalidad'=> 'array|required',
        ],[
            'frecuencia.required'=> 'Diligencie todos los campos',
            'severidad.required' => 'Diligencie todos los campos',
            'ili.required'       => 'Diligencie todos los campos',
            'mortalidad.required'=> 'Diligencie todos los campos',
            
            'frecuencia.array'=> 'Diligencie todos los campos',
            'severidad.array' => 'Diligencie todos los campos',
            'ili.array'       => 'Diligencie todos los campos',
            'mortalidad.array'=> 'Diligencie todos los campos',
        ]);
        
        $this->lineaBaseFrecuencia($idPgrp,$data["frecuencia"]);
        $this->lineaBaseSeveridad($idPgrp,$data["severidad"]);
        $this->lineaBaseIli($idPgrp,$data["ili"]);
        $this->lineaBaseMortalidad($idPgrp,$data["mortalidad"]);
        
    }
    
    private function lineaBaseFrecuencia($idPgrp,$arrFrecuencia){
        $existeBaseFrecuencia = PgrpGeneralLineaBase::where('pgrp_generales_id',$idPgrp)
                ->where('nombreMeta','Eficacia Frecuencia')
                ->get();
        
        if(count($existeBaseFrecuencia)>0){
            $baseFrecuencia = $existeBaseFrecuencia[0];
        }else{
            $baseFrecuencia = PgrpGeneralLineaBase::create([
                'pgrp_generales_id' => $idPgrp,
                'nombreMeta'      => 'Eficacia Frecuencia',
             ]);
        }
        
        for($i=0;$i<count($arrFrecuencia);$i++){
            $mes = helpers::meses_de_numero_a_texto($i);
            $baseFrecuencia->$mes = $arrFrecuencia[$i];
        }
        $baseFrecuencia->save();
    }
    
    private function lineaBaseSeveridad($idPgrp,$arrSeveridad){
        $existeBaseSeveridad = PgrpGeneralLineaBase::where('pgrp_generales_id',$idPgrp)
                ->where('nombreMeta','Eficacia Severidad')
                ->get();
        
        if(count($existeBaseSeveridad)>0){
            $baseSeveridad = $existeBaseSeveridad[0];
        }else{
            $baseSeveridad = PgrpGeneralLineaBase::create([
                'pgrp_generales_id' => $idPgrp,
                'nombreMeta'      => 'Eficacia Severidad',
             ]);
        }
        
        for($i=0;$i<count($arrSeveridad);$i++){
            $mes = helpers::meses_de_numero_a_texto($i);
            $baseSeveridad->$mes = $arrSeveridad[$i];
        }
        $baseSeveridad->save();
    }
    
    private function lineaBaseIli($idPgrp,$arrIli){
        $existeBaseili = PgrpGeneralLineaBase::where('pgrp_generales_id',$idPgrp)
                ->where('nombreMeta','Eficacia ili')
                ->get();
        
        if(count($existeBaseili)>0){
            $baseili = $existeBaseili[0];
        }else{
            $baseili = PgrpGeneralLineaBase::create([
                'pgrp_generales_id' => $idPgrp,
                'nombreMeta'      => 'Eficacia ili',
             ]);
        }
        
        for($i=0;$i<count($arrIli);$i++){
            $mes = helpers::meses_de_numero_a_texto($i);
            $baseili->$mes = $arrIli[$i];
        }
        $baseili->save();
    }
    
    private function lineaBaseMortalidad($idPgrp,$arrMortalidad){
        $existeBaseTasaMortalidad = PgrpGeneralLineaBase::where('pgrp_generales_id',$idPgrp)
                ->where('nombreMeta','Eficacia Tasa Mortalidad')
                ->get();
        
        if(count($existeBaseTasaMortalidad)>0){
            $baseTasaMortalidad = $existeBaseTasaMortalidad[0];
        }else{
            $baseTasaMortalidad = PgrpGeneralLineaBase::create([
                'pgrp_generales_id' => $idPgrp,
                'nombreMeta'      => 'Eficacia Tasa Mortalidad',
             ]);
        }
        
        for($i=0;$i<count($arrMortalidad);$i++){
            $mes = helpers::meses_de_numero_a_texto($i);
            $baseTasaMortalidad->$mes = $arrMortalidad[$i];
        }
        $baseTasaMortalidad->save();
    }
}
