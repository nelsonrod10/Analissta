<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\ActividadesCalendario;
use App\ActividadesValoracione;
use App\LargoPlazo;
use App\CortoPlazo;

class ActividadesIndicadoresController extends Controller
{
    public function Indicadores(){
        return view('analissta.Actividades.indicadores');
    }
    
    public function getDataIndicadores(){
        $cumplimiento = $colorCumplimiento = [];
        for($i=0;$i<=11;$i++):
            $textMes = helpers::meses_de_numero_a_texto($i);
            $progMes = ActividadesCalendario::where("sistema_id",session('sistema')->id)
                ->where("mes",$textMes)
                ->get();
                
            $ejecMes = ActividadesCalendario::where("sistema_id",session('sistema')->id)
                ->where("ejecutada","Si")        
                ->where("mes",$textMes)
                ->get();
            
            $vrCumplimiento = 0;
            if(count($ejecMes) > 0){$vrCumplimiento = round(count($ejecMes)/ count($progMes)*100,2);}
            
            array_push($colorCumplimiento, 'rgba('.rand(0, 255).','.rand(10, 255).','.rand(0, 180).',1)');    //$this->getColorBarra($i)
            array_push($cumplimiento,$vrCumplimiento);
        endfor;
        
        $pve = $this->getDataIndicadoresPVE();
        $pgrp = $this->getDataIndicadoresPGRP();
        
        return response()->json([
                "cumplimiento"        => $cumplimiento,
                'colorCumplimiento'   => $colorCumplimiento,
                'dataPve'             => $pve,
                'dataPgrp'             => $pgrp
        ]);
    }
    
    private function getDataIndicadoresPVE(){
        $peligros=[];
        $peligro = LargoPlazo::where('sistema_id',session('sistema')->id)
                ->where("pve","Si")
                ->get()
                ->unique(function ($item) {
                        return $item['pve_table'].$item['pve_id'];
                });
        foreach ($peligro as $dataPeligro) {
            $data=[];
            $actividades = ActividadesValoracione::where('sistema_id',session('sistema')->id)
                ->where("peligro_id",$dataPeligro->peligro_id)
                ->get();
            foreach ($actividades as $actividad) {
                $color = 'rgba('.rand(0, 255).','.rand(10, 255).','.rand(0, 180).',1)';
                for($i=0;$i<=11;$i++):
                    $textMes = helpers::meses_de_numero_a_texto($i);
                    $subText = strtoupper(substr($textMes, 0, 3)); 

                    $progMes = ActividadesCalendario::where("sistema_id",session('sistema')->id)
                        ->where('actividad_id',$actividad->id)
                        ->where("mes",$textMes)
                        ->get();

                    $ejecMes = ActividadesCalendario::where("sistema_id",session('sistema')->id)
                        ->where('actividad_id',$actividad->id)    
                        ->where("ejecutada","Si")        
                        ->where("mes",$textMes)
                        ->get();
                    $vrCumplimiento = 0;
                    if(count($ejecMes) > 0){$vrCumplimiento = round(count($ejecMes)/ count($progMes)*100,2);}
                    
                    array_push($data, $vrCumplimiento);

                endfor;    
            }
            
            array_push($peligros,
                [
                    "data"=> $data,        
                    "backgroundColor"=>'rgba('.rand(0, 255).','.rand(0, 255).','.rand(0, 180).',1)',
                    "label" => $this->getNombrePVE_PGRP($dataPeligro->peligro->clasificacion,$dataPeligro->peligro->categoria)
                ]
            );
            
        }
        
        
        
        return $peligros;
        
    }
    
    private function getDataIndicadoresPGRP(){
        $peligros=[];
        $peligro = CortoPlazo::where('sistema_id',session('sistema')->id)
                ->where("pgrp","Si")
                ->get()
                ->unique(function ($item) {
                        return $item['pgrp_table'].$item['pgrp_id'];
                });
        
        foreach ($peligro as $dataPeligro) {
            $data=[];
            $actividades = ActividadesValoracione::where('sistema_id',session('sistema')->id)
                ->where("peligro_id",$dataPeligro->peligro_id)
                ->get();
            foreach ($actividades as $actividad) {
                for($i=0;$i<=11;$i++):
                    $textMes = helpers::meses_de_numero_a_texto($i);
                    $subText = strtoupper(substr($textMes, 0, 3)); 

                    $progMes = ActividadesCalendario::where("sistema_id",session('sistema')->id)
                        ->where('actividad_id',$actividad->id)
                        ->where("mes",$textMes)
                        ->get();

                    $ejecMes = ActividadesCalendario::where("sistema_id",session('sistema')->id)
                        ->where('actividad_id',$actividad->id)    
                        ->where("ejecutada","Si")        
                        ->where("mes",$textMes)
                        ->get();
                    $vrCumplimiento = 0;
                    if(count($ejecMes) > 0){$vrCumplimiento = round(count($ejecMes)/ count($progMes)*100,2);}
                    
                    array_push($data, $vrCumplimiento);
                    
                endfor;    
                
            }
            array_push($peligros,
                [
                    "data"=> $data,        
                    "backgroundColor"=>'rgba('.rand(0, 255).','.rand(0, 255).','.rand(0, 180).',1)',
                    "label" => $this->getNombrePVE_PGRP($dataPeligro->peligro->clasificacion,$dataPeligro->peligro->categoria)
                ]
            );
        }
        
        
        
        return $peligros;
        
    }
    
    private function getNombrePVE_PGRP($clasificacion,$categoria){
        
        $archivo = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
        $nombreClasificacion = $archivo->xpath("//peligros/clasificacion[id=$clasificacion]");
        $nombreCategoria = $archivo->xpath("//peligros/clasificacion[id=$clasificacion]/listDescripciones/descripcion[id=$categoria]");
        if($clasificacion === 1 || $clasificacion === 6){
            return ucfirst(strtolower(substr_replace((string)$nombreCategoria[0]->nombre, "...", 20)));
        }else{
            return ucfirst(strtolower((string)$nombreClasificacion[0]->nombre));
        }
        
    }
    /*private function getColorBarra($valor){
        switch ($valor) {
            case 0: return 'rgba(255, 99, 132, 0.8)'; break;
            case 1: return 'rgba(54, 162, 235, 0.8)'; break;
            case 2: return 'rgba(255, 206, 86, 0.8)'; break;
            case 3: return 'rgba(75, 192, 192, 0.8)'; break;
            case 4: return 'rgba(153, 102, 255, 0.8)'; break;
            case 5: return 'rgba(255, 159, 64, 0.8)'; break;
            case 6: return 'rgba(153, 102, 80, 0.8)'; break;
            case 7: return 'rgba(54, 30, 255, 0.8)'; break;
            case 8: return 'rgba(190, 210, 255, 0.8)'; break;
            case 9: return 'rgba(20, 255, 100, 0.8)'; break;
            case 10: return 'rgba(200, 80, 180, 0.8)'; break;
            case 11: return 'rgba(255, 60, 30, 0.8)'; break;
        }
    }*/
}
