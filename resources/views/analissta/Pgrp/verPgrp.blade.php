@extends('analissta.layouts.appSideBar')
<?php
use App\Http\Controllers\helpers;
use App\CortoPlazo;
use App\Peligro;

    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    
    $xmlPeligros = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    $clasifPeligro = $xmlPeligros->xpath("//peligros/clasificacion[id={$clasificacion}]");
    
    switch ($clasificacion) {
        case 1:
            $pgrp_table = "pgrp_fisicos";
            break;
        case 6:
            $pgrp_table = "pgrp_seguridades";
            break;
        default:
            $pgrp_table = "pgrp_generales";
            break;
    }
    $peligrosAsociados = CortoPlazo::where('sistema_id',$sistema->id)
            ->where('pgrp_id',$pgrp->id)
            ->where('pgrp_table',$pgrp_table)
            ->get();
    /*$peligroAsociado = PeligrosHallazgosAccidente::where('sistema_id',$sistema->id)
            ->where('origen_id',$hallazgo[0]->id)
            ->where('origen_table','Hallazgos')
            ->get();
    
    
    $nombreOrigen = $xml_origenes->xpath("//origenes/origen[@id={$hallazgo[0]->origen_id}]");
    
    $descPeligro = $xml_peligros->xpath("//peligros/clasificacion[id={$peligroAsociado[0]->clasificacion}]/listDescripciones/descripcion[id={$peligroAsociado[0]->categoria}]");*/
?>
@section('sistem-menu')
<style>
    .titulo-origenes{
        font-size: 16px;
        font-weight: bold;
        color: #3c3737;
    }
    .a-hallazgo{
        width: auto;
        height: auto;
        max-width: 80%;
        max-height: 25px;
        overflow: hidden;

    }
    .a-hallazgo a{
        text-decoration: underline;
    }
</style>
@include('analissta.layouts.appTopMenu')

@endsection
@section('sidebar')

@include('analissta.Pgrp.menuPgrp')


@endsection
@section('content')
    @section('titulo-encabezado')
        Gestión del Riesgo Prioritario 
    @endsection
    
    @section('buttons-submenus')
        <!--<a class="button small" href="{{route('crear-hallazgo')}}">Crear Hallazgo</a>
        <a class="button small warning" href="{{route('hallazgos')}}">Listado Hallazgos</a>-->
    @endsection

    <div class="row columns">
        <b>Clasificación: </b><span>{{ucwords(strtolower($clasifPeligro[0]->nombre))}}</span>
    </div>
    <div class="row columns">
        <b>Riesgos Asociados: </b> 
        <b style="font-size:12px"><i>(Haga click sobre el proceso para ver sus detalles)</i></b>
    </div>
    <div class="row columns" style="font-size: 12px">
        <ol>
        <?php
            foreach($peligrosAsociados as $peligroAsociado):
                $peligro = Peligro::find($peligroAsociado->peligro_id);
                $categPeligro = $xmlPeligros->xpath("//peligros/clasificacion[id={$clasificacion}]/listDescripciones/descripcion[id=$peligro->categoria]");
        ?>
            <li>
                {{$categPeligro[0]->nombre}} - <a style="text-decoration: underline" href="{{route('detalles-peligro',['idActividad'=>$peligro->actividad->id,'idPeligro'=>$peligro->id])}}"><b>Proceso: </b>{{$peligro->actividad->proceso->nombre}}- <b>Actividad: </b>{{$peligro->actividad->nombre}}</a>
            </li>
        <?php    
            endforeach;
        ?>
        </ol>
    </div>
    <div class="row">
        <div class="columns small-12">
            <div style="background-color:grey;color:white" class="text-center">Datos Generales</div>
        </div>
    </div>
    <br/>
    <form id="frm-crear-pgrp" name="dataGralRiesgoPrioritario" method="POST" action="{{route('datos-pgrp')}}">
        {{ csrf_field() }}
        <input type="hidden" class="hide" hidden="true" name="idPgrp" value="{{$pgrp->id}}"/>
        <input type="hidden" class="hide" hidden="true" name="clasificacion" value="{{$clasificacion}}"/>
        <div class="row">
            <div class="columns small-12 medium-2">
                <label for="nombrePrograma" class="middle"><b>Nombre Programa: </b></label>
            </div>
            <div class="columns small-12 medium-4">
                <input type="text" id="nombre" name="nombre" required="true" placeholder="Ejm: Manejo de Alturas" value="{{$pgrp->nombre}}" <?php echo ($pgrp->estado == 'Programado')?"readonly":""; ?>/>
            </div>
            <div class="columns small-12 medium-2">
                <label for="cargoResponsable" class="middle"><b>Cargo Responsable: </b></label>
            </div>
            <div class="columns small-12 medium-4 end">
                <input type="text" id="responsable" name="cargo" required="true" placeholder="Ejm: Gerente HSEQ" value="{{$pgrp->cargo}}"  <?php echo ($pgrp->estado == 'Programado')?"readonly":""; ?>/>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12"><h5><b>Objetivo del Programa</b></h5></div>
            <div class="columns small-12">
                <label><b>Descripción General</b><i> (Realiza una descripción textual)</i></label>
                <textArea name="objetivo" required="true" style="height:auto; min-height:80px;" onkeyup="mostrarBtns(this,'div-DataGeneral-2')"  <?php echo ($pgrp->estado == 'Programado')?"readonly":""; ?>>{{$pgrp->objetivo}}</textArea>
            </div>            
        </div>
        <div id="div-DataGeneral-2">
            <div class="columns small-12">
                <h4><b>Metas</b></h4>
            </div>
            <div class="columns small-12">
                <h5><b>Metas Período Anterior</b></h5>
                <div>¿Existen datos de las eficacias del período <b>inmediatamente anterior</b>?</div>
                <div>
                    <input type="radio" name="lineaBase" id="SI" class="check-linea-base" value="SI"  required="true" <?php echo ($pgrp->estado == 'Programado')?"disabled":""; echo ($pgrp->lineaBase)?" checked":""; ?> />
                    <label for="SI"> SI </label>
                    <input type="radio" name="lineaBase" id="NO" class="check-linea-base" value="NO" required="true" <?php echo ($pgrp->estado == 'Programado')?"disabled":""; echo (!$pgrp->lineaBase)?" checked":""; ?> />
                    <label for="NO"> NO </label>
                </div>
                @if($pgrp->estado == 'Programado' && $pgrp->lineaBase) 
                    <div>
                        <a class="button small" onclick="$('#div-dataPeriodoAnterior').toggleClass('hide')">Ver Datos</a>
                    </div>
                @endif

            </div>
            <div class="columns small-12 hide" id="div-dataPeriodoAnterior">
                <?php
                    $frecEne=$frecFeb=$frecMar=$frecAbr=$frecMay=$frecJun=$frecJul=$frecAgo=$frecSep=$frecOct=$frecNov=$frecDic=null;
                    $sevEne=$sevFeb=$sevMar=$sevAbr=$sevMay=$sevJun=$sevJul=$sevAgo=$sevSep=$sevOct=$sevNov=$sevDic=null;
                    $iliEne=$iliFeb=$iliMar=$iliAbr=$iliMay=$iliJun=$iliJul=$iliAgo=$iliSep=$iliOct=$iliNov=$iliDic=null;
                    $mortEne=$mortFeb=$mortMar=$mortAbr=$mortMay=$mortJun=$mortJul=$mortAgo=$mortSep=$mortOct=$mortNov=$mortDic=null;
                    if($pgrp->estado == 'Programado'){
                        foreach ($pgrp->lineaBase as $linea) {
                            switch ($linea->nombreMeta) {
                                case "Eficacia Frecuencia":
                                    $frecEne=$linea->Enero;$frecFeb=$linea->Febrero;$frecMar=$linea->Marzo;$frecAbr=$linea->Abril;$frecMay=$linea->Mayo;$frecJun=$linea->Junio;
                                    $frecJul=$linea->Julio;$frecAgo=$linea->Agosto;$frecSep=$linea->Septiembre;$frecOct=$linea->Octubre;$frecNov=$linea->Noviembre;$frecDic=$linea->Diciembre;
                                    break;
                                case "Eficacia Severidad":
                                    $sevEne=$linea->Enero;$sevFeb=$linea->Febrero;$sevMar=$linea->Marzo;$sevAbr=$linea->Abril;$sevMay=$linea->Mayo;$sevJun=$linea->Junio;
                                    $sevJul=$linea->Julio;$sevAgo=$linea->Agosto;$sevSep=$linea->Septiembre;$sevOct=$linea->Octubre;$sevNov=$linea->Noviembre;$sevDic=$linea->Diciembre;
                                    break;
                                case "Eficacia ili":
                                    $iliEne=$linea->Enero;$iliFeb=$linea->Febrero;$iliMar=$linea->Marzo;$iliAbr=$linea->Abril;$iliMay=$linea->Mayo;$iliJun=$linea->Junio;
                                    $iliJul=$linea->Julio;$iliAgo=$linea->Agosto;$iliSep=$linea->Septiembre;$iliOct=$linea->Octubre;$iliNov=$linea->Noviembre;$iliDic=$linea->Diciembre;
                                    break;
                                case "Eficacia Tasa Mortalidad":
                                    $mortEne=$linea->Enero;$mortFeb=$linea->Febrero;$mortMar=$linea->Marzo;$mortAbr=$linea->Abril;$mortMay=$linea->Mayo;$mortJun=$linea->Junio;
                                    $mortJul=$linea->Julio;$mortAgo=$linea->Agosto;$mortSep=$linea->Septiembre;$mortOct=$linea->Octubre;$mortNov=$linea->Noviembre;$mortDic=$linea->Diciembre;
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                ?>   
                <fieldset class="fieldset text-center">
                    <h6><b>Datos Eficacias Período Anterior</b></h6>
                    <i>Ingrese los datos de los indicadores correspondientes a cada mes del período anterior</i>
                    <div class="row">
                        <div class="columns small-12 medium-3"><b style="text-decoration:underline">Mes</b></div>
                        <div class="columns small-12 medium-2"><b style="text-decoration:underline">Frecuencia</b></div>
                        <div class="columns small-12 medium-2"><b style="text-decoration:underline">Severidad</b></div>
                        <div class="columns small-12 medium-2"><b style="text-decoration:underline">ILI</b></div>
                        <div class="columns small-12 medium-2 end"><b style="text-decoration:underline">Tasa Mortalidad</b></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Enero</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecEne}}' <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevEne}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliEne}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortEne}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Febrero</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0"  value='{{$frecFeb}}' <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevFeb}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliFeb}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base"  name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortFeb}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Marzo</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0"value='{{$frecMar}}' <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevMar}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliMar}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortMar}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Abril</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecAbr}}' <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevAbr}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliAbr}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortAbr}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Mayo</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecMay}}' <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevMay}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliMay}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortMay}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Junio</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecJun}}' <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevJun}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliJun}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortJun}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Julio</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecJul}}' <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevJul}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliJul}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortJul}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Agosto</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecAgo}}' <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevAgo}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliAgo}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortAgo}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Septiembre</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecSep}}' <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevSep}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliSep}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortSep}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Octubre</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecOct}}' <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevOct}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliOct}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortOct}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Noviembre</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecNov}}' <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevNov}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliNov}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortNov}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Diciembre</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecDic}}' <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevDic}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliDic}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortDic}}" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                </fieldset>

            </div>
            <div class="columns small-12">
                <h5><b>Metas Período Actual</b></h5>
                <div class="row">
                    <div class="columns small-12">
                        <label><b>Frecuencia de Medición: </b>Mensual</label>    
                    </div> 
<!-- FRECUENCIA DE ANALISIS -->                                        
                    <div class="columns small-12 medium-4"><label class="middle"><b>Frecuencia de Análisis de los indicadores</b></label></div>
                    <div class="columns small-6 medium-4 end">
                        <select required="true" name="frecAnalisis" <?php echo ($pgrp->estado == 'Programado')?"disabled":""; ?>>
                            <option value="">Seleccione</option>
                            <option value="Trimestral"  <?php echo ($pgrp->frecuencia_analisis == 'Trimestral')?"selected":""; ?>>Trimestral</option>
                            <option value="Semestral" <?php echo ($pgrp->frecuencia_analisis == 'Semestral')?"selected":""; ?>>Semestral</option>
                            <option value="Anual" <?php echo ($pgrp->frecuencia_analisis == 'Anual')?"selected":""; ?>>Anual</option>   
                        </select>
                    </div>    
                </div>
                <!--<xsl:if test="../@programado = 'false' or $flagEditar='true'"><i> ¿Que metas se deben cumplir, para lograr el objetivo de <b id="b-string-objetivo"></b>?</i></xsl:if>-->
            </div>
            <!--<xsl:if test="../@programado = 'true' and $flagEditar = ''">
                <div class="columns small-12">
                    <h6><b>Objetivo Específico: </b> <xsl:value-of select="objetivo/tipo"/></h6>
                </div>            
            </xsl:if>-->
            <xsl:if test="../@programado = 'false' or $flagEditar='true'">
                <div class="row">
                    <div class="columns small-12">
                        <i>Lea detenidamente los siguientes textos de ayuda para luego definir el objetivo específico al momento de configurar cada eficacia</i>
                    </div>
                    <div class="columns small-12 medium-4 fieldset fieldsetObjetivo" id="fieldset-Reducir">
                        <div class="text-center"><b>Reducir</b></div>
                        <div>Si deacuerdo a los datos del programa de gestión del período anterior, se quiere <b>Reducir</b> la eficacia del objetivo para este nuevo período</div>
                        <!--<div class="text-center"><a class="button small btnObjetivo" onclick="selecObjetivoEspecifico(this,'Reducir')">Reducir</a></div>-->
                    </div>
                    <div class="columns small-12 medium-4 fieldset fieldsetObjetivo" id="fieldset-Aumentar">
                        <div class="text-center"><b>Aumentar</b></div>
                        <div>Si deacuerdo a los datos del programa de gestión del período anterior, se quiere <b>Aumentar</b> la eficacia del objetivo para este nuevo período</div>
                        <!--<div class="text-center"><a class="button small btnObjetivo" onclick="selecObjetivoEspecifico(this,'Aumentar')">Aumentar</a></div>-->
                    </div>
                    <div class="columns small-12 medium-4 fieldset fieldsetObjetivo" id="fieldset-Mantener">
                        <div class="text-center"><b>Mantener</b></div>
                        <div>Si deacuerdo a los datos del programa de gestión del período anterior, se quiere <b>Mantener</b> el mismo objetivo para este nuevo período</div>
                        <!--<div class="text-center"><a class="button small btnObjetivo" onclick="selecObjetivoEspecifico(this,'Mantener')">Mantener</a></div>-->
                    </div>
                </div>
            </xsl:if>

            <div id="div-DataGeneral-3">
                <?php
                    $objetivoFrecuencia=$unidadFrecuencia=$objetivoSeveridad=$unidadSeveridad=$objetivoIli=$unidadIli=$objetivoMortalidad=$unidadMortalidad="";
                    $valorFrecuencia=$valorSeveridad=$valorIli=$valorMortalidad=null;
                    if($pgrp->estado == 'Programado'){
                        foreach ($pgrp->metas as $meta) {
                            switch ($meta->nombreMeta) {
                                case "Eficacia Frecuencia":
                                    $objetivoFrecuencia = $meta->objetivo;
                                    $valorFrecuencia = $meta->valorMeta;
                                    $unidadFrecuencia= $meta->unidad;
                                    break;
                                case "Eficacia Severidad":
                                    $objetivoSeveridad = $meta->objetivo;
                                    $valorSeveridad = $meta->valorMeta;
                                    $unidadSeveridad= $meta->unidad;
                                    break;
                                case "Eficacia ili":
                                    $objetivoIli = $meta->objetivo;
                                    $valorIli = $meta->valorMeta;
                                    $unidadIli= $meta->unidad;
                                    break;
                                case "Eficacia Tasa Mortalidad":
                                    $objetivoMortalidad = $meta->objetivo;
                                    $valorMortalidad = $meta->valorMeta;
                                    $unidadMortalidad= $meta->unidad;
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                ?>            
                <div class="row">
                    <div class="row show-for-medium text-center" style="font-weight:bold;">
                        <div class="columns medium-3" style="text-decoration:underline">Tipo Eficacia</div>
                        <div class="columns medium-3" style="text-decoration:underline">Objetivo</div>
                        <div class="columns medium-3" style="text-decoration:underline">Valor Meta</div>
                        <div class="columns medium-3" style="text-decoration:underline">Unidad</div>
                    </div>
                </div>    
    
<!-- EFICACIA FRECUENCIA-->                               
                
                <div class="row">
                    <div class="columns small-12 medium-3"><label class="middle"><b>Eficacia Frecuencia</b></label></div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Objetivo</label>
                        <select required="true" name="objetivo-frecuencia" id="objetivo-frecuencia" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>>
                            <option value="">Seleccione...</option>
                            <option value="Aumentar" <?php echo ($objetivoFrecuencia == 'Aumentar')?"selected":"" ?>>Aumentar</option>
                            <option value="Reducir" <?php echo ($objetivoFrecuencia == 'Reducir')?"selected":"" ?>>Reducir</option>
                            <option value="Mantener" <?php echo ($objetivoFrecuencia == 'Mantener')?"selected":"" ?>>Mantener</option>
                        </select>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Valor Meta</label>
                        <input type="number" name="meta-frecuencia" required="true" step="0.00005" min="0" value="{{$valorFrecuencia}}" <?php echo ($pgrp->estado == 'Programado')?"readonly":""?>/>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Unidad</label>
                        <select required="true" name="unidad-frecuencia" id="unidad-frecuencia" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>>
                           <option value="">Seleccione...</option>
                           <option value="unidad" <?php echo ($unidadFrecuencia == 'unidad')?"selected":"" ?>>Unidad</option>
                           <option value="porcentaje"  <?php echo ($unidadFrecuencia == 'porcentaje')?"selected":"" ?>>Porcentaje %</option>
                        </select>
                    </div>

                </div>
<!-- EFICACIA SEVERIDAD-->                                
                <div class="row">
                    <div class="columns small-12 medium-3"><label class="middle"><b>Eficacia Severidad</b></label></div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Objetivo</label>
                        <select required="true" name="objetivo-severidad" id="objetivo-severidad" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>>
                            <option value="">Seleccione...</option>
                            <option value="Aumentar" <?php echo ($objetivoSeveridad == 'Aumentar')?"selected":"" ?>>Aumentar</option>
                            <option value="Reducir" <?php echo ($objetivoSeveridad == 'Reducir')?"selected":"" ?>>Reducir</option>
                            <option value="Mantener" <?php echo ($objetivoSeveridad == 'Mantener')?"selected":"" ?>>Mantener</option>
                        </select>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Valor Meta</label>
                        <input type="number" name="meta-severidad" required="true" step="0.00005" min="0"  value="{{$valorSeveridad}}" <?php echo ($pgrp->estado == 'Programado')?"readonly":""?>/>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Unidad</label>
                        <select required="true" name="unidad-severidad" id="unidad-severidad" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>>
                            <option value="">Seleccione...</option>
                            <option value="unidad" <?php echo ($unidadSeveridad == 'unidad')?"selected":"" ?>>Unidad</option>
                            <option value="porcentaje"  <?php echo ($unidadSeveridad == 'porcentaje')?"selected":"" ?>>Porcentaje %</option>
                        </select>
                    </div>

                </div>
<!-- EFICACIA ILI-->                                
                <div class="row">
                    <div class="columns small-12 medium-3"><label class="middle"><b>Eficacia ILI</b></label></div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Objetivo</label>
                        <select required="true" name="objetivo-ili" id="objetivo-ili" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>>
                            <option value="">Seleccione...</option>
                            <option value="Aumentar" <?php echo ($objetivoIli == 'Aumentar')?"selected":"" ?>>Aumentar</option>
                            <option value="Reducir" <?php echo ($objetivoIli == 'Reducir')?"selected":"" ?>>Reducir</option>
                            <option value="Mantener" <?php echo ($objetivoIli == 'Mantener')?"selected":"" ?>>Mantener</option>
                        </select>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Valor Meta</label>
                        <input type="number" name="meta-ili" required="true" step="0.00005" min="0" value="{{$valorIli}}" <?php echo ($pgrp->estado == 'Programado')?"readonly":""?>/>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Unidad</label>
                        <select required="true" name="unidad-ili" id="unidad-ili" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>>
                            <option value="">Seleccione...</option>
                            <option value="unidad" <?php echo ($unidadIli == 'unidad')?"selected":"" ?>>Unidad</option>
                            <option value="porcentaje"  <?php echo ($unidadIli == 'porcentaje')?"selected":"" ?>>Porcentaje %</option>
                        </select>
                    </div>

                </div>
<!-- EFICACIA TASA MORTALIDAD-->                                
                <div class="row">
                    <div class="columns small-12 medium-3"><label class="middle"><b>Eficacia Tasa Mortalidad</b></label></div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Objetivo</label>
                        <select required="true" name="objetivo-mortalidad" id="objetivo-mortalidad" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>>
                            <option value="">Seleccione...</option>
                            <option value="Aumentar" <?php echo ($objetivoMortalidad == 'Aumentar')?"selected":"" ?>>Aumentar</option>
                            <option value="Reducir" <?php echo ($objetivoMortalidad == 'Reducir')?"selected":"" ?>>Reducir</option>
                            <option value="Mantener" <?php echo ($objetivoMortalidad == 'Mantener')?"selected":"" ?>>Mantener</option>
                        </select>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Valor Meta</label>
                        <input type="number" name="meta-mortalidad" required="true" step="0.005" min="0"  value="{{$valorMortalidad}}" <?php echo ($pgrp->estado == 'Programado')?"readonly":""?>/>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Unidad</label>
                        <select required="true" name="unidad-mortalidad" id="unidad-mortalidad" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>>
                            <option value="">Seleccione...</option>
                            <option value="unidad" <?php echo ($unidadMortalidad == 'unidad')?"selected":"" ?>>Unidad</option>
                            <option value="porcentaje"  <?php echo ($unidadMortalidad == 'porcentaje')?"selected":"" ?>>Porcentaje %</option>
                        </select>
                    </div>
                </div>  

                <hr/>
                <div class="row">

<!--METAS COBERTURA Y CUMPLIMIENTO-->                                         
                    <div class="columns small-12 medium-4">
                        <label><b>Meta Cobertura</b></label>
                        <select required="true" name="cobertura" readonly="true" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>>
                            <option value="100">100%</option>   
                        </select>
                    </div>  
                    <div class="columns small-12 medium-4">
                        <label><b>Meta Cumplimiento</b></label>
                        <select required="true" name="cumplimiento" <?php echo ($pgrp->estado == 'Programado')?"disabled":""?>>
                            <xsl:if test="../@programado='true' and $flagEditar=''"><xsl:attribute name="disabled"/></xsl:if>
                            <option value="">Seleccione</option>
                            <option value="50" <?php echo ($pgrp->cumplimiento == '50')?"selected":"" ?>>50%</option>
                            <option value="60" <?php echo ($pgrp->cumplimiento == '60')?"selected":"" ?>>60%</option>
                            <option value="70" <?php echo ($pgrp->cumplimiento == '70')?"selected":"" ?>>70%</option>   
                            <option value="80" <?php echo ($pgrp->cumplimiento == '80')?"selected":"" ?>>80%</option>
                            <option value="90" <?php echo ($pgrp->cumplimiento == '90')?"selected":"" ?>>90%</option>
                            <option value="100" <?php echo ($pgrp->cumplimiento == '100')?"selected":"" ?>>100%</option>   
                        </select>
                    </div>   

                </div>
<!--- ALCANCE -->                                                                                                                                                                                                                                                      
                <div class="row">
                    <div class="columns small-12"><h5><b>Alcance</b></h5></div>
                    <div class="columns small-12">
                        <textArea required="true" name="alcance" style="height:auto; min-height:80px;" onkeyup="mostrarBtns(this,'div-DataGeneral-4')" <?php echo ($pgrp->estado == 'Programado')?"readonly":""?>>{{$pgrp->alcance}}</textArea>
                    </div>            
                </div>
                <!--<div class="row">
                    <div class="columns small-12"><h5><b>Recursos Asignados</b></h5></div>
                    <div class="columns small-12">
                        <textArea required="true" name="recursos" style="height:auto; min-height:80px;" ></textArea>
                    </div> 
                </div>-->
        </div>
        </div>
        @if($pgrp->estado == 'Programado')
            <div class="row">
                <div class="columns small-4 small-centered">
                    <a class="button expanded success-2" href="{{route('editar-pgrp',['clasificacion'=>$clasificacion,'id'=>$pgrp->id])}}"> Editar Datos</a>
                </div>
            </div>
        @else
            <div class="row columns text-center">
            <span id="div-DataGeneral-4">
                <input type="submit" class="button small" value="Guardar"/>
            </span>
             
            <a class="button small alert">Cancelar
            </a>
            </div>
        @endif
    </form>
    
    @if($pgrp->estado == 'Programado')
        <?php
            $actividadesValoracion=$capacitacionesValoracion=$inspeccionesValoracion=[];
            foreach($peligrosAsociados as $peligroAsociado):
                    $peligro = Peligro::find($peligroAsociado->peligro_id);
                    foreach($peligro->actividadesValoracion as $actividad):
                        $actividadesValoracion[$actividad->id] =$actividad;
                    endforeach;
                    
                    foreach($peligro->capacitacionesValoracion as $capacitacion):
                        $capacitacionesValoracion[$capacitacion->id] =$capacitacion;
                    endforeach;
                    
                    foreach($peligro->inspeccionesValoracion as $inspeccion):
                        $inspeccionesValoracion[$inspeccion->id] =$inspeccion;
                    endforeach;
            endforeach;        
        ?>
    <div class="row">
        <div class="columns small-12">
            <div style="background-color:grey;color:white" class="text-center">Programación</div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="columns small-12 medium-6 ">
            <div class="text-center" style="background-color:#6699ff;color:white">
                <b>Actividades</b>

            </div>
            <?php
                foreach($actividadesValoracion as $id=>$actividad):
                    switch($actividad->estado):
                        case('Programada'):
                            $color = "#cc0000";
                            break;
                        case('En ejecucion'):
                            $color = "#f29c13";
                            break;
                        case('Ejecutado'):
                            $color = "#3adb76";
                            break;
                        default:
                            $color= "white";
                    endswitch;
            ?>
                <div class="columns small-12 text-center">
                    <a style="text-decoration:underline" href="{{route('actividad-valoracion',['id'=>$id])}}">{{$actividad->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$actividad->estado}}</small></i>
                </div>
            <?php
                endforeach;
            ?>
        </div>
            
        <div class="columns small-12 medium-6 ">
            <div class="text-center" style="background-color:#6699ff;color:white">
                <b>Capacitaciones</b>
            </div>
            <?php
                foreach($capacitacionesValoracion as $id=>$capacitacion):
                    switch($capacitacion->estado):
                        case('Programada'):
                            $color = "#cc0000";
                            break;
                        case('En ejecucion'):
                            $color = "#f29c13";
                            break;
                        case('Ejecutado'):
                            $color = "#3adb76";
                            break;
                        default:
                            $color= "white";
                    endswitch;
            ?>
                <div class="columns small-12 text-center">
                    <a style="text-decoration:underline" href="{{route('capacitacion-valoracion',['id'=>$id])}}">{{$capacitacion->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$capacitacion->estado}}</small></i>
                </div>
            <?php
                endforeach;
            ?>
        </div>
    </div>    
    <br/>
    <div class="row">
        <div class="columns small-12 medium-6 small-centered">
            <div class="text-center" style="background-color:#6699ff;color:white">
                <b>Inspecciones</b>
            </div>
            <?php
                foreach($inspeccionesValoracion as $id=>$inspeccion):
                    switch($inspeccion->estado):
                        case('Programada'):
                            $color = "#cc0000";
                            break;
                        case('En ejecucion'):
                            $color = "#f29c13";
                            break;
                        case('Ejecutado'):
                            $color = "#3adb76";
                            break;
                        default:
                            $color= "white";
                    endswitch;
            ?>
                <div class="columns small-12 text-center">
                    <a style="text-decoration:underline" href="{{route('inspeccion-valoracion',['id'=>$id])}}">{{$inspeccion->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$inspeccion->estado}}</small></i>
                </div>
            <?php
                endforeach;
            ?>
        </div>
    </div>
    
    <br/>
    @endif
    <script>
        $(document).ready(function(){
            $(".check-linea-base").on("click", function(){
                if($(this).val() === "SI"){
                    $('#div-dataPeriodoAnterior').removeClass('hide');
                    $(".input-linea-base").each(function(){
                       $(this).attr("required","");
                    });
                }
                if($(this).val() === "NO"){
                    $('#div-dataPeriodoAnterior').addClass('hide');
                    $(".input-linea-base").each(function(){
                       $(this).removeAttr("required");
                    });
                }
            });
            $("#frm-crear-pgrp").on("submit",function(e){
                var flag=0;
                $(".check-linea-base").each(function(){
                    if($(this).is(":checked") && $(this).val() === "SI"){
                        $(".input-linea-base").each(function(){
                            if($(this).val() === null || $(this).val() === ""){
                                flag = 1;
                            }
                        });
                    }
                    
                });
                
                if(flag === 1){
                    $('#div-dataPeriodoAnterior').removeClass('hide');
                    alert("Diligencie todos los campos de la linea Base");
                    e.preventDefault();
                }
            });
        });
    </script>  
@endsection

