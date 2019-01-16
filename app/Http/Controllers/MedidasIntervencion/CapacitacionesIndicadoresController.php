<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\CapacitacionesCalendario;

class CapacitacionesIndicadoresController extends Controller
{
    public function Indicadores(){
        return view('analissta.Capacitaciones.indicadores');
    }
    
    public function getDataIndicadores(){
        $cumplimiento = $colorCumplimiento = [];
        for($i=0;$i<=11;$i++):
            $textMes = helpers::meses_de_numero_a_texto($i);
            $progMes = CapacitacionesCalendario::where("sistema_id",session('sistema')->id)
                ->where("mes",$textMes)
                ->get();
                
            $ejecMes = CapacitacionesCalendario::where("sistema_id",session('sistema')->id)
                ->where("ejecutada","Si")        
                ->where("mes",$textMes)
                ->get();
            
            $vrCumplimiento = 0;
            if(count($ejecMes) > 0){$vrCumplimiento = round(count($ejecMes)/ count($progMes)*100,2);}
            
            array_push($colorCumplimiento, 'rgba('.rand(0, 255).','.rand(10, 255).','.rand(0, 180).',1)');    //$this->getColorBarra($i)
            array_push($cumplimiento,$vrCumplimiento);
        endfor;
        
        $cobertura   = $this->getDataCobertura();
        $horasHombre = $this->getDataHorasHombre();
        
        return response()->json([
                "cumplimiento"        => $cumplimiento,
                'colorCumplimiento'   => $colorCumplimiento,
                'cobertura'           => $cobertura,
                'horasHombre'         => $horasHombre
        ]);
    }
    
    private function getDataCobertura(){
        $respuesta = $cobertura = $colorCobertura = [];
        for($i=0;$i<=11;$i++):
            $asistentes=$invitados=$vrCobertura=0;
            $textMes = helpers::meses_de_numero_a_texto($i);
                
            $ejecMes = CapacitacionesCalendario::where("sistema_id",session('sistema')->id)
                ->where("ejecutada","Si")        
                ->where("mes",$textMes)
                ->get();
            foreach ($ejecMes as $ejecutada) {
                $asistentes += (int)$ejecutada->asistentes;
                $invitados += (int)$ejecutada->invitados;
            }
            if(count($ejecMes) > 0){$vrCobertura = round($asistentes/ $invitados*100,2);}
            
            array_push($colorCobertura, 'rgba('.rand(0, 255).','.rand(10, 255).','.rand(0, 180).',1)');
            array_push($cobertura,$vrCobertura);
        endfor;
        
        array_push($respuesta, 
            [
                'data' => $cobertura, 
                'backgroundColor' => $colorCobertura,
                'label' => '% Cobertura Mensual'
            ]
        );
        return $respuesta;
    }
    
    private function getDataHorasHombre(){
        $respuesta = $horasHombre = $colorHoras = [];
        for($i=0;$i<=11;$i++):
            $totHoras=$vrHoras=0;
            $textMes = helpers::meses_de_numero_a_texto($i);
                
            $ejecMes = CapacitacionesCalendario::where("sistema_id",session('sistema')->id)
                ->where("ejecutada","Si")        
                ->where("mes",$textMes)
                ->get();
            foreach ($ejecMes as $ejecutada) {
                $totHoras += $ejecutada->asistentes * $ejecutada->duracion;
            }
            if(count($ejecMes) > 0){$vrHoras = round($totHoras,2);}
            
            array_push($colorHoras, 'rgba('.rand(0, 255).','.rand(10, 255).','.rand(0, 180).',1)');
            array_push($horasHombre,$vrHoras);
        endfor;
        
        array_push($respuesta, 
            [
                'data' => $horasHombre, 
                'backgroundColor' => $colorHoras,
                'label' => '% Horas Hombre (hr)'
            ]
        );
        return $respuesta;
    }
}
