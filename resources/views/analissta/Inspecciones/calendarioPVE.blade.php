<hr/>
<div class="columns small-12 text-center" style="background:grey; color:white"><b>VIGILANCIA EPIDEMIOLOGICA ( PVE )</b></div>
<div class="columns small-12 text-center"><i>(Se muestran el total de Inspecciones, <b>Programadas y Ejecutadas</b> por mes)</i></div>
<hr/>
<?php

use App\Http\Controllers\helpers;
use App\InspeccionesCalendario;
use App\InspeccionesValoracione;
use App\LargoPlazo;

$xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    foreach($sistema->pveFisico as $pveFisico):
        $categoriaFisico = $xml_GTC45->xpath("//peligros/clasificacion[id=1]/listDescripciones/descripcion[id=$pveFisico->categoria]");
        $idsPeligrosFisico = LargoPlazo::where('sistema_id',$sistema->id)
            ->where('pve','Si')
            ->where('pve_id',$pveFisico->id)
            ->where('pve_table','pve_fisicos')
            ->get();
        
        $arrInspeccionesFis=[];
        
        foreach($idsPeligrosFisico as $idPeligro):
                $idsInspecciones = InspeccionesValoracione::where('sistema_id',$sistema->id)
                ->where('peligro_id',$idPeligro->peligro_id)
                ->get();
                foreach($idsInspecciones as $idFis):
                    array_push($arrInspeccionesFis, $idFis->id);
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
                foreach($arrInspeccionesFis as $idInspeccion):
                    $programadas = InspeccionesCalendario::where('sistema_id',$sistema->id)
                        ->where('inspeccion_id',$idInspeccion)
                        ->where('tipo','valoracion')
                        ->where('anio',helpers::getCurrentYear())
                        ->where('mes',$textMes)
                        ->get();

                    $ejecutadas = InspeccionesCalendario::where('sistema_id',$sistema->id)
                        ->where('inspeccion_id',$idInspeccion)
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
                    <a href="{{route('calendario-inspecciones-mes',['mes'=>$textMes])}}">
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
    foreach($sistema->pveSeguridad as $pveSeguridad):
        
        $categoriaSeguridad = $xml_GTC45->xpath("//peligros/clasificacion[id=6]/listDescripciones/descripcion[id=$pveSeguridad->categoria]");
        $idsPeligrosSeguridad = LargoPlazo::where('sistema_id',$sistema->id)
            ->where('pve','Si')
            ->where('pve_id',$pveSeguridad->id)
            ->where('pve_table','pve_seguridades')
            ->get();
        
        $arrInspeccionesSeg=[];
        
        foreach($idsPeligrosSeguridad as $idPeligroSeg):
                $idsInspeccionesSeg = InspeccionesValoracione::where('sistema_id',$sistema->id)
                ->where('peligro_id',$idPeligroSeg->peligro_id)
                ->get();
                foreach($idsInspeccionesSeg as $idSeg):
                    array_push($arrInspeccionesSeg, $idSeg->id);
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
                foreach($arrInspeccionesSeg as $idInspeccionSeg):
                    
                    $programadasSeg = InspeccionesCalendario::where('sistema_id',$sistema->id)
                        ->where('inspeccion_id',$idInspeccionSeg)
                        ->where('tipo','valoracion')
                        ->where('anio',helpers::getCurrentYear())
                        ->where('mes',$textMes)
                        ->get();

                    $ejecutadasSeg = InspeccionesCalendario::where('sistema_id',$sistema->id)
                        ->where('inspeccion_id',$idInspeccionSeg)
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
                    <a href="{{route('calendario-inspecciones-mes',['mes'=>$textMes])}}">
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
    foreach($sistema->pveGeneral as $pveGeneral):
        $clasifGeneral = $xml_GTC45->xpath("//peligros/clasificacion[id=$pveGeneral->clasificacion]");
        $idsPeligrosGeneral = LargoPlazo::where('sistema_id',$sistema->id)
            ->where('pve','Si')
            ->where('pve_id',$pveGeneral->id)
            ->where('pve_table','pve_generales')
            ->get();
        
        $arrInspeccionesGen=[];
        
        foreach($idsPeligrosGeneral as $idPeligroGen):
                $idsInspeccionesGen = InspeccionesValoracione::where('sistema_id',$sistema->id)
                ->where('peligro_id',$idPeligroGen->peligro_id)
                ->get();
                foreach($idsInspeccionesGen as $idGen):
                    array_push($arrInspeccionesGen, $idGen->id);
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
                foreach($arrInspeccionesGen as $idInspeccionGen):
                    
                    $programadasGen = InspeccionesCalendario::where('sistema_id',$sistema->id)
                        ->where('inspeccion_id',$idInspeccionGen)
                        ->where('tipo','valoracion')
                        ->where('anio',helpers::getCurrentYear())
                        ->where('mes',$textMes)
                        ->get();

                    $ejecutadasGen = InspeccionesCalendario::where('sistema_id',$sistema->id)
                        ->where('inspeccion_id',$idInspeccionGen)
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
                    <a href="{{route('calendario-inspecciones-mes',['mes'=>$textMes])}}">
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
    

    