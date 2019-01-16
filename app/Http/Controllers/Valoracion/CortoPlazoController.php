<?php

namespace App\Http\Controllers\Valoracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CortoPlazo;
use App\Peligro;
use App\Http\Controllers\Valoracion\RequisitosLegalesController;
use App\Http\Controllers\PlanesGestion\PgrpController;


class CortoPlazoController extends Controller
{
    protected $objValoracion;


    public function getObjetoPorValoracion($peligro){
        $obj = CortoPlazo::where('peligro_id',$peligro->id)
                ->where('tipo','valoracion')
                ->get();
       $this->objValoracion = $obj[0]; 
    }
    
    public function getObjetoPorRevaloracion($peligro){
        $obj = CortoPlazo::where('peligro_id',$peligro->id)
                ->where('tipo','revaloracion')
                ->get();
       $this->objValoracion = $obj->last(); 
    }
    
    public function eliminar_corto_plazo(){
        $this->eliminarPGRP();
        $consulta = CortoPlazo::where('peligro_id',session('idPeligro'))->first();
        if(isset($consulta->id)){
            RequisitosLegalesController::eliminar_requisito_Legal($consulta->id,'Corto Plazo');
        }
        
        CortoPlazo::where('peligro_id',session('idPeligro'))->delete();
        //ACA ELIMINAR EL PGPR
    }
    
    public function eliminar_corto_plazo_revaloracion($idPeligro){
        $consulta = CortoPlazo::where('peligro_id',$idPeligro)
                ->where('tipo','revaloracion')
                ->get();
        if(isset($consulta->last()->id)){
            RequisitosLegalesController::eliminar_requisito_Legal($consulta->last()->id,'Corto Plazo');
            $consulta->last()->delete();
        }
        
    }
    
    public function eliminarPGRP(){
        //si llega a existir y es el unico peligro en ese pgrp se elimina el anterior pgrp
        $tienePgrp=CortoPlazo::where('peligro_id',session('idPeligro'))
                ->where('pgrp','Si')
                ->get();
        
        if(count($tienePgrp)>0){
            $peligrosConElmismoPGRP = CortoPlazo::where('pgrp_id',$tienePgrp[0]->pgrp_id)
                    ->where('pgrp_table',$tienePgrp[0]->pgrp_table)
                    ->get();
            
            if(count($peligrosConElmismoPGRP) === 1){
                //quiere decir que este es el unico que tiene ese pgrp, por lo tanto se debe borrar el pgrp de la tabla
                $cadena = str_replace(" ","",ucwords(str_replace("_"," ", $tienePgrp[0]->pgrp_table)));
                $nombreFuncion = "eliminar$cadena";
                $pgrpController = new PgrpController();
                $pgrpController->$nombreFuncion($tienePgrp[0]->pgrp_id);
            }
        }
        return;
    }


    public function create_corto_plazo($fuente,$medio,$individuo,$admon,$idPeligro,$tipo='valoracion'){
        CortoPlazo::create([
            'sistema_id'             =>  session('sistema')->id,
            'peligro_id'          =>  $idPeligro,
            'tipo'                =>  $tipo,    
            'fuente'              =>  $fuente,
            'medio'               =>  $medio,
            'individuo'           =>  $individuo,
            'administrativo'      =>  $admon,
        ]);
    }
    
    public function create_valoracion_corto_plazo($nd, $ne, $nc,$idPeligro){
        $np = (int)$nd*(int)$ne;
        $nri = (int)$np*(int)$nc;
        $pgrp_id = $this->verificar_pgrp($nri,$idPeligro);
        
        $this->objValoracion->nd   = $nd;
        $this->objValoracion->ne   = $ne; 
        $this->objValoracion->nc   = $nc;
        $this->objValoracion->np   = $np===0?2:$np;
        $this->objValoracion->nri  = $nri;
        $this->objValoracion->pgrp = ($pgrp_id === 0)?"No":"Si";
        $this->objValoracion->pgrp_id = $pgrp_id;
        $this->objValoracion->pgrp_table = ($pgrp_id === 0)?"N/A":$this->getTablePGRP($idPeligro);
        
        $this->objValoracion->save();
    }
    
    private function verificar_pgrp($nri,$idPeligro){
        
        $peligro = Peligro::find($idPeligro);
        //Si la clasificacion es diferente de Condiciones Naturales y los valores de nri corresponden para ser PGRP
        if((int)$peligro->clasificacion !== 7 && (((int)$nri >=150 && (int)$nri <= 500) || ((int)$nri >=600 && (int)$nri <= 4000 ))){
            $pgrp = new PgrpController();
            return $pgrp->crearPGRP($peligro);
        }
        //ACA ELIMINAR EL PGPR
        return 0;
    }
    
    private function getTablePGRP($idPeligro){
        $peligro = Peligro::find($idPeligro);
        $table="N/A";
        switch ((int)$peligro->clasificacion) {
            case 1:
                $table = "pgrp_fisicos";
            break;
            case 6:
                $table = "pgrp_seguridades";
            break;
            default:
                $table = "pgrp_generales";
            break;
        }
        return $table;
    }
    
    public function create_criterios_corto_plazo($cliente, $contratista, $directos, $vistante, $consecuencia, $legal,$requisitos,$idPeligro){
        $this->objValoracion->cliente           = $cliente; 
        $this->objValoracion->contratista       = $contratista;
        $this->objValoracion->directos          = $directos; 
        $this->objValoracion->visitantes        = $vistante;
        $this->objValoracion->peorConsecuencia  = $consecuencia;
        $this->objValoracion->reqLegal          = $legal;
        
        $this->objValoracion->save();
        $this->verificarRequisitoLegal($this->objValoracion->id,$legal,$requisitos);
    }
    
    private function verificarRequisitoLegal($id,$legal,$requisitos){
        RequisitosLegalesController::eliminar_requisito_Legal($id,'Corto Plazo');
        
        if($legal === "Si"){
            RequisitosLegalesController::crear_requisito_legal($id,$requisitos,'Corto Plazo');
        }
    }
    
    
}
