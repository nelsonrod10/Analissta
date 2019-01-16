<?php

namespace App\Http\Controllers;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\ResultadosAnualesSistema;
use Illuminate\Http\Request;


class ResultadosAnualesController extends Controller
{
    public function crearResultadosCrearEmpresa() {
        (string)$anioAnterior = helpers::getLastYear();
        
        $data = request()->validate([
            'tasaMort' => 'string|required',
            'ili' => 'string|required',
            'if' => 'string|required',
            'isev' => 'string|required',
            'tasaAcc' => 'string|required',
            'tasaEnfL' => 'string|required',
            'ifEnfL' => 'string|required',
            
        ],[
            'tasaMort.required' => 'Debe indicar la Tasa de Mortalidad del año '.$anioAnterior,
            'ili.required' => 'Debe indicar el ILI del año '.$anioAnterior,
            'if.required' => 'Debe indicar el Indice de Frecuencia del año '.$anioAnterior,
            'isev.required' => 'Debe indicar el Indice de Severidad del año '.$anioAnterior,
            'tasaAcc.required' => 'Debe indicar la Tasa de Accidentalidad del año '.$anioAnterior,
            'tasaEnfL.required' => 'Debe indicar la Tasa de Enfermedad Laboral del año '.$anioAnterior,
            'ifEnfL.required' => 'Debe indicar el Indice de Frecuencia de Enfermedad Laboral del año '.$anioAnterior,
        ]);
        
        $resultados = ResultadosAnualesSistema::where('empresaCliente_id',session('idEmpresaCliente'))
            ->where('anio',$anioAnterior)
            ->first();
        
        if($resultados){
            $this->actualizarResultados($data,$resultados);
            
        }else{
            $this->crearResultados($data,$anioAnterior);
        }
        
    }
   
    private function crearResultados($data,$anioAnterior){
        ResultadosAnualesSistema::create([
            'empresaCliente_id'         =>  session('idEmpresaCliente'),
            'anio'                      =>  $anioAnterior,
            'tasaMortalidad'            =>  $data["tasaMort"],
            'indLesionesIncapacitantes' =>  $data["ili"],
            'indFrecuencia'             =>  $data["if"],
            'indSeveridad'              =>  $data["isev"],
            'tasaAccidentalidad'        =>  $data["tasaAcc"],
            'tasaEnfLaboral'            =>  $data["tasaEnfL"],
            'indFrecEnfLaboral'         =>  $data["ifEnfL"],
        ]);
        return;
    }
   
    private function actualizarResultados($data,$resultados){
        
        $resultados->tasaMortalidad            =  $data["tasaMort"];
        $resultados->indLesionesIncapacitantes =  $data["ili"];
        $resultados->indFrecuencia             =  $data["if"];
        $resultados->indSeveridad              =  $data["isev"];
        $resultados->tasaAccidentalidad        =  $data["tasaAcc"];
        $resultados->tasaEnfLaboral            =  $data["tasaEnfL"];
        $resultados->indFrecEnfLaboral         =  $data["ifEnfL"];
        
        $resultados->save();
    }
}
