<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\InspeccionesCalendario;

class InspeccionesIndicadoresController extends Controller
{
    
    public function Indicadores(){
        return view('analissta.Inspecciones.indicadores');
    }
    
    public function getDataIndicadores(){
        $cumplimiento = $colorCumplimiento = [];
        for($i=0;$i<=11;$i++):
            $textMes = helpers::meses_de_numero_a_texto($i);
            $progMes = InspeccionesCalendario::where("sistema_id",session('sistema')->id)
                ->where("mes",$textMes)
                ->get();
                
            $ejecMes = InspeccionesCalendario::where("sistema_id",session('sistema')->id)
                ->where("ejecutada","Si")        
                ->where("mes",$textMes)
                ->get();
            
            $vrCumplimiento = 0;
            if(count($ejecMes) > 0){$vrCumplimiento = round(count($ejecMes)/ count($progMes)*100,2);}
            
            array_push($colorCumplimiento, 'rgba('.rand(0, 255).','.rand(10, 255).','.rand(0, 180).',1)');    //$this->getColorBarra($i)
            array_push($cumplimiento,$vrCumplimiento);
        endfor;
        
        return response()->json([
                "cumplimiento"        => $cumplimiento,
                'colorCumplimiento'   => $colorCumplimiento,
        ]);
    }
}
