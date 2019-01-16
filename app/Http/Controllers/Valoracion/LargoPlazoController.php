<?php

namespace App\Http\Controllers\Valoracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LargoPlazo;
use App\Peligro;
use App\Http\Controllers\PlanesGestion\PveController;

class LargoPlazoController extends Controller
{
    protected $objValoracion;
    
    public function getObjetoPorValoracion($peligro){
        $obj = LargoPlazo::where('peligro_id',$peligro->id)
                ->where('tipo','valoracion')
                ->get();
       $this->objValoracion = $obj[0]; 
    }
    
    public function getObjetoPorRevaloracion($peligro){
        $obj = LargoPlazo::where('peligro_id',$peligro->id)
                ->where('tipo','revaloracion')
                ->get();
       $this->objValoracion = $obj->last(); 
    }
    
    public function eliminar_largo_plazo(){
        $this->eliminarPVE();
        $consulta = LargoPlazo::where('peligro_id',session('idPeligro'))->first();
        if(isset($consulta->id)){
            RequisitosLegalesController::eliminar_requisito_Legal($consulta->id,'Largo Plazo');
        }
        
        LargoPlazo::where('peligro_id',session('idPeligro'))->delete();
        //ACA ELIMINAR EL PVE
    }
    
    public function eliminar_largo_plazo_revaloracion($idPeligro){
        $consulta = LargoPlazo::where('peligro_id',$idPeligro)
                ->where('tipo','revaloracion')
                ->get();
        if(isset($consulta->last()->id)){
            RequisitosLegalesController::eliminar_requisito_Legal($consulta->last()->id,'Largo Plazo');
            $consulta->last()->delete();
        }
        
        
        //ACA ELIMINAR EL PGPR
    }
    
    public function eliminarPVE(){
        //si llega a existir y es el unico peligro en ese pve se elimina el anterior pve
        $tienePve=LargoPlazo::where('peligro_id',session('idPeligro'))
                ->where('pve','Si')
                ->get();
        if(count($tienePve)>0){
            $peligrosConElmismoPGRP = LargoPlazo::where('pve_id',$tienePve[0]->pve_id)
                    ->where('pve_table',$tienePve[0]->pve_table)
                    ->get();
            
            if(count($peligrosConElmismoPGRP) === 1){
                //quiere decir que este es el unico que tiene ese pve, por lo tanto se debe borrar el pve de la tabla
                $cadena = str_replace(" ","",ucwords(str_replace("_"," ", $tienePve[0]->pve_table)));
                $nombreFuncion = "eliminar$cadena";
                $pveController = new PveController();
                $pveController->$nombreFuncion($tienePve[0]->pve_id);
            }
        }
    }
    
    public function create_largo_plazo($fuente,$medio,$individuo,$admon,$idPeligro,$tipo='valoracion'){
        LargoPlazo::create([
            'sistema_id'          =>  session('sistema')->id,
            'peligro_id'          =>  $idPeligro,
            'tipo'                => $tipo,  
            'fuente'              =>  $fuente,
            'medio'               =>  $medio,
            'individuo'           =>  $individuo,
            'administrativo'      =>  $admon,
        ]);
    }
    
    public function create_valoracion_largo_plazo($nd, $ne, $nc,$idPeligro){
        $np = (int)$nd*(int)$ne;
        $nri = (int)$np*(int)$nc;
        $pve_id = $this->verificar_pve($nri,$idPeligro);
        
        $this->objValoracion->nd   = $nd;
        $this->objValoracion->ne   = $ne; 
        $this->objValoracion->nc   = $nc;
        $this->objValoracion->np   = $np===0?2:$np;
        $this->objValoracion->nri  = $nri;
        $this->objValoracion->pve = ($pve_id === 0)?"No":"Si";
        $this->objValoracion->pve_id = $pve_id;
        $this->objValoracion->pve_table = ($pve_id === 0)?"N/A":$this->getTablePVE($idPeligro);
        
        $this->objValoracion->save();
    }
    
    private function verificar_pve($nri,$idPeligro){
        $peligro = Peligro::find($idPeligro);
        //Si la clasificacion es diferente de Condiciones Naturales y los valores de nri corresponden para ser PVE
        if((int)$peligro->clasificacion !== 7 && (((int)$nri >=150 && (int)$nri <= 500) || ((int)$nri >=600 && (int)$nri <= 4000 ))){
            $pve = new PveController();
            return $pve->crearPVE($peligro);
        }
        //ACA ELIMINAR EL PVE
        return 0;
    }
    
    private function getTablePVE($idPeligro){
        $peligro = Peligro::find($idPeligro);
        $table="N/A";
        switch ((int)$peligro->clasificacion) {
            case 1:
                $table = "pve_fisicos";
            break;
            case 6:
                $table = "pve_seguridades";
            break;
            default:
                $table = "pve_generales";
            break;
        }
        return $table;
    }
    
    public function create_criterios_largo_plazo($cliente, $contratista, $directos, $vistante, $consecuencia, $legal,$requisitos,$idPeligro){
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
        RequisitosLegalesController::eliminar_requisito_Legal($id,'Largo Plazo');
        if($legal === "Si"){
            RequisitosLegalesController::crear_requisito_legal($id,$requisitos,'Largo Plazo');
        }
    }
}
