<hr/>
<div class="columns small-12 text-center" style="background:grey; color:white"><b>GESTION DEL RIESGO ( PGRP )</b></div>
<div class="columns small-12 text-center"><i>(Se muestran el total de Actividades, <b>Programadas y Ejecutadas</b> por mes)</i></div>
<hr/>
<?php

use App\Http\Controllers\helpers;
use App\ActividadesCalendario;
use App\ActividadesValoracione;
use App\CortoPlazo;

$xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    foreach($sistema->pgrpFisico as $pgrpFisico):
        $categoriaFisico = $xml_GTC45->xpath("//peligros/clasificacion[id=1]/listDescripciones/descripcion[id=$pgrpFisico->categoria]");
        $idsPeligrosFisico = CortoPlazo::where('sistema_id',$sistema->id)
            ->where('pgrp','Si')
            ->where('pgrp_id',$pgrpFisico->id)
            ->where('pgrp_table','pgrp_fisicos')
            ->get();
        
        $arrActividadesFis=[];
        
        foreach($idsPeligrosFisico as $idPeligro):
                $idsActividades = ActividadesValoracione::where('sistema_id',$sistema->id)
                ->where('peligro_id',$idPeligro->peligro_id)
                ->get();
                foreach($idsActividades as $idFis):
                    array_push($arrActividadesFis, $idFis->id);
                endforeach;
        endforeach;
?>
    <div class="row">
        <div class="columns small-12"><b>Fisico</b></div>
        <div class="columns small-2"><small>{{substr($categoriaFisico[0]->nombre,0,20)}}</small></div>
        <div class="columns small-10">
            <?php
                for($i=0;$i<=11;$i++):
                    $textMes = helpers::meses_de_numero_a_texto($i);
                $totalProgramadas = $totalEjecutadas = 0;
                foreach($arrActividadesFis as $idActividad):
                    $programadas = ActividadesCalendario::where('sistema_id',$sistema->id)
                        ->where('actividad_id',$idActividad)
                        ->where('tipo','valoracion')
                        ->where('anio',helpers::getCurrentYear())
                        ->where('mes',$textMes)
                        ->get();

                    $ejecutadas = ActividadesCalendario::where('sistema_id',$sistema->id)
                        ->where('actividad_id',$idActividad)
                        ->where('tipo','valoracion')
                        ->where('ejecutada','Si')
                        ->where('anio',helpers::getCurrentYear())
                        ->where('mes',$textMes)
                        ->get();    
                    $totalProgramadas += count($programadas);
                    $totalEjecutadas += count($ejecutadas);
                endforeach;
            ?>
            <div class="columns small-1" style="border-right: 1px solid lightgray;">
                <div class="row" style="height:40px;border-bottom: 1px solid lightgray">
                    <a href="{{route('calendario-actividades-mes',['mes'=>$textMes])}}">
                        <div class="columns small-6" style="background:#f29c13">{{$totalProgramadas}}</div>
                        <div class="columns small-6" style="background:#3adb76">{{$totalEjecutadas}}</div>    
                    </a>
                </div>
            </div>
            <?php
                endfor;
            ?>
        </div>
    </div>
    
<?php
    endforeach;
?>
    
<?php
    foreach($sistema->pgrpSeguridad as $pgrpSeguridad):
        $categoriaSeguridad = $xml_GTC45->xpath("//peligros/clasificacion[id=6]/listDescripciones/descripcion[id=$pgrpSeguridad->categoria]");
        $idsPeligrosSeguridad = CortoPlazo::where('sistema_id',$sistema->id)
            ->where('pgrp','Si')
            ->where('pgrp_id',$pgrpSeguridad->id)
            ->where('pgrp_table','pgrp_seguridades')
            ->get();
        
        $arrActividadesSeg=[];
        
        foreach($idsPeligrosSeguridad as $idPeligroSeg):
                $idsActividadesSeg = ActividadesValoracione::where('sistema_id',$sistema->id)
                ->where('peligro_id',$idPeligroSeg->peligro_id)
                ->get();
                foreach($idsActividadesSeg as $idSeg):
                    array_push($arrActividadesSeg, $idSeg->id);
                endforeach;
        endforeach;
?>
    <div class="row">
        <div class="columns small-12"><b>Seguridad</b></div>
        <div class="columns small-2"><small>{{substr($categoriaSeguridad[0]->nombre,0,20)}}</small></div>
        <div class="columns small-10">
            <?php
                for($i=0;$i<=11;$i++):
                    $textMes = helpers::meses_de_numero_a_texto($i);
                    $totalProgramadasSeg = $totalEjecutadasSeg = 0;
                foreach($arrActividadesSeg as $idActividadSeg):
                    $programadasSeg = ActividadesCalendario::where('sistema_id',$sistema->id)
                        ->where('actividad_id',$idActividadSeg)
                        ->where('tipo','valoracion')
                        ->where('anio',helpers::getCurrentYear())
                        ->where('mes',$textMes)
                        ->get();

                    $ejecutadasSeg = ActividadesCalendario::where('sistema_id',$sistema->id)
                        ->where('actividad_id',$idActividadSeg)
                        ->where('tipo','valoracion')
                        ->where('ejecutada','Si')
                        ->where('anio',helpers::getCurrentYear())
                        ->where('mes',$textMes)
                        ->get();    
                    $totalProgramadasSeg += count($programadasSeg);
                    $totalEjecutadasSeg += count($ejecutadasSeg);
                endforeach;
            ?>
            <div class="columns small-1" style="border-right: 1px solid lightgray;">
                <div class="row" style="height:40px;border-bottom: 1px solid lightgray">
                    <a href="#">
                        <div class="columns small-6" style="background:#f29c13">{{$totalProgramadasSeg}}</div>
                        <div class="columns small-6" style="background:#3adb76">{{$totalEjecutadasSeg}}</div>    
                    </a>
                </div>
            </div>
            <?php
                endfor;
            ?>
        </div>
    </div>
    
<?php
    endforeach;
?>

    
<?php
    foreach($sistema->pgrpGeneral as $pgrpGeneral):
        $clasifGeneral = $xml_GTC45->xpath("//peligros/clasificacion[id=$pgrpGeneral->clasificacion]");
        $idsPeligrosGeneral = CortoPlazo::where('sistema_id',$sistema->id)
            ->where('pgrp','Si')
            ->where('pgrp_id',$pgrpGeneral->id)
            ->where('pgrp_table','pgrp_generales')
            ->get();
        
        $arrActividadesGen=[];
        
        foreach($idsPeligrosGeneral as $idPeligroGen):
                $idsActividadesGen = ActividadesValoracione::where('sistema_id',$sistema->id)
                ->where('peligro_id',$idPeligroGen->peligro_id)
                ->get();
                foreach($idsActividadesGen as $idGen):
                    array_push($arrActividadesGen, $idGen->id);
                endforeach;
        endforeach;
               
?>
    <div class="row">
        <div class="columns small-2"><b>{{$clasifGeneral[0]->nombre}}</b></div>
        <div class="columns small-10">
            <?php
                for($i=0;$i<=11;$i++):
                    $textMes = helpers::meses_de_numero_a_texto($i);
                    $totalProgramadasGen = $totalEjecutadasGen = 0;
                foreach($arrActividadesGen as $idActividadGen):
                    $programadasGen = ActividadesCalendario::where('sistema_id',$sistema->id)
                        ->where('actividad_id',$idActividadGen)
                        ->where('tipo','valoracion')
                        ->where('anio',helpers::getCurrentYear())
                        ->where('mes',$textMes)
                        ->get();

                    $ejecutadasGen = ActividadesCalendario::where('sistema_id',$sistema->id)
                        ->where('actividad_id',$idActividadGen)
                        ->where('tipo','valoracion')
                        ->where('ejecutada','Si')
                        ->where('anio',helpers::getCurrentYear())
                        ->where('mes',$textMes)
                        ->get();    
                    $totalProgramadasGen += count($programadasGen);
                    $totalEjecutadasGen += count($ejecutadasGen);
                endforeach;
            ?>
            <div class="columns small-1" style="border-right: 1px solid lightgray;">
                <div class="row" style="height:40px;border-bottom: 1px solid lightgray">
                    <a href="#">
                        <div class="columns small-6" style="background:#f29c13">{{$totalProgramadasGen}}</div>
                        <div class="columns small-6" style="background:#3adb76">{{$totalEjecutadasGen}}</div>    
                    </a>
                </div>
            </div>
            <?php
                endfor;
            ?>
        </div>
    </div>
<?php
    endforeach;
    

    