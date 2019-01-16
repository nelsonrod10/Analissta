<?php

namespace App\Http\Controllers\Valoracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RequisitosLegale;
use App\CortoPlazo;
use App\LargoPlazo;

class RequisitosLegalesController extends Controller
{
    public static function eliminar_requisito_Legal($id,$tipo){
        RequisitosLegale::where('tipo_id',(int)$id)->where('tipo_texto',$tipo)->delete();
    }
    
    public static function crear_requisito_legal($idTipo,$requisitos,$tipo){
        RequisitosLegale::create([
            'tipo_id'       =>$idTipo,
            'tipo_texto'    =>$tipo,
            'requisitos'    =>$requisitos
        ]);
    }
    
    public static function eliminar_requisitos_legales(){
        $cortoPlazo = CortoPlazo::where('peligro_id',session('idPeligro'))->get();
        $largoPlazo = LargoPlazo::where('peligro_id',session('idPeligro'))->get();
        if(isset($cortoPlazo[0]->id)){
            RequisitosLegale::where('tipo_id',(int)$cortoPlazo[0]->id)->delete();
        }
        if(isset($largoPlazo[0]->id)){
            RequisitosLegale::where('tipo_id',(int)$largoPlazo[0]->id)->delete();
        }
        
    }
}
