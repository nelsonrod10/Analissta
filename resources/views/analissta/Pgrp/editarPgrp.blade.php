@extends('analissta.layouts.app')
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

@section('content')
    @section('titulo-encabezado')
        Editar Datos - Gestión del Riesgo Prioritario 
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
    <form id="frm-editar-pgrp" name="edit-dataGralRiesgoPrioritario" method="POST" action="{{route('datos-pgrp')}}">
        {{ csrf_field() }}
        <input type="hidden" class="hide" hidden="true" name="idPgrp" value="{{$pgrp->id}}"/>
        <input type="hidden" class="hide" hidden="true" name="clasificacion" value="{{$clasificacion}}"/>
        <div class="row">
            <div class="columns small-12 medium-2">
                <label for="nombrePrograma" class="middle"><b>Nombre Programa: </b></label>
            </div>
            <div class="columns small-12 medium-4">
                <input type="text" id="nombre" name="nombre" required="true" placeholder="Ejm: Manejo de Alturas" value="{{$pgrp->nombre}}" />
            </div>
            <div class="columns small-12 medium-2">
                <label for="cargoResponsable" class="middle"><b>Cargo Responsable: </b></label>
            </div>
            <div class="columns small-12 medium-4 end">
                <input type="text" id="responsable" name="cargo" required="true" placeholder="Ejm: Gerente HSEQ" value="{{$pgrp->cargo}}"  />
            </div>
        </div>
        <div class="row">
            <div class="columns small-12"><h5><b>Objetivo del Programa</b></h5></div>
            <div class="columns small-12">
                <label><b>Descripción General</b><i> (Realiza una descripción textual)</i></label>
                <textArea name="objetivo" required="true" style="height:auto; min-height:80px;" onkeyup="mostrarBtns(this,'div-DataGeneral-2')"  >{{$pgrp->objetivo}}</textArea>
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
                    <input type="radio" name="lineaBase" id="SI" class="check-linea-base" value="SI"  required="true" <?php echo ($pgrp->lineaBase)?" checked":""; ?>/>
                    <label for="SI"> SI </label>
                    <input type="radio" name="lineaBase" id="NO" class="check-linea-base" value="NO" required="true" <?php echo (!$pgrp->lineaBase)?" checked":""; ?>/>
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
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecEne}}' /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevEne}}" /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliEne}}" /></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortEne}}" /></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Febrero</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0"  value='{{$frecFeb}}' /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevFeb}}" /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliFeb}}" /></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortFeb}}" /></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Marzo</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0"value='{{$frecMar}}' /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevMar}}" /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliMar}}" /></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortMar}}" /></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Abril</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecAbr}}' /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevAbr}}" /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliAbr}}" /></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortAbr}}" /></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Mayo</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecMay}}' /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevMay}}" /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliMay}}" /></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortMay}}" /></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Junio</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecJun}}' /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevJun}}" /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliJun}}" /></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortJun}}" /></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Julio</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecJul}}' /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevJul}}" /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliJul}}" /></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortJul}}" /></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Agosto</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecAgo}}' /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevAgo}}" /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliAgo}}" /></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortAgo}}" /></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Septiembre</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecSep}}' /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevSep}}" /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliSep}}" /></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortSep}}" /></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Octubre</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecOct}}' /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevOct}}" /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliOct}}" /></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortOct}}" /></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Noviembre</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecNov}}' /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevNov}}" /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliNov}}" /></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortNov}}" /></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Diciembre</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="frecuencia[]" type="number" step="0.00005" min="0" value='{{$frecDic}}' /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="severidad[]" type="number" step="0.00005" min="0" value="{{$sevDic}}" /></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="ili[]" type="number" step="0.00005" min="0" value="{{$iliDic}}" /></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="mortalidad[]" type="number" step="0.005" min="0" value="{{$mortDic}}" /></div>
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
                        <select required="true" name="frecAnalisis" >
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
                        <select required="true" name="objetivo-frecuencia" id="objetivo-frecuencia" >
                            <option value="">Seleccione...</option>
                            <option value="Aumentar" <?php echo ($objetivoFrecuencia == 'Aumentar')?"selected":"" ?>>Aumentar</option>
                            <option value="Reducir" <?php echo ($objetivoFrecuencia == 'Reducir')?"selected":"" ?>>Reducir</option>
                            <option value="Mantener" <?php echo ($objetivoFrecuencia == 'Mantener')?"selected":"" ?>>Mantener</option>
                        </select>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Valor Meta</label>
                        <input type="number" name="meta-frecuencia" required="true" step="0.00005" min="0" value="{{$valorFrecuencia}}" />
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Unidad</label>
                        <select required="true" name="unidad-frecuencia" id="unidad-frecuencia" >
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
                        <select required="true" name="objetivo-severidad" id="objetivo-severidad" >
                            <option value="">Seleccione...</option>
                            <option value="Aumentar" <?php echo ($objetivoSeveridad == 'Aumentar')?"selected":"" ?>>Aumentar</option>
                            <option value="Reducir" <?php echo ($objetivoSeveridad == 'Reducir')?"selected":"" ?>>Reducir</option>
                            <option value="Mantener" <?php echo ($objetivoSeveridad == 'Mantener')?"selected":"" ?>>Mantener</option>
                        </select>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Valor Meta</label>
                        <input type="number" name="meta-severidad" required="true" step="0.00005" min="0"  value="{{$valorSeveridad}}" />
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Unidad</label>
                        <select required="true" name="unidad-severidad" id="unidad-severidad" >
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
                        <select required="true" name="objetivo-ili" id="objetivo-ili" >
                            <option value="">Seleccione...</option>
                            <option value="Aumentar" <?php echo ($objetivoIli == 'Aumentar')?"selected":"" ?>>Aumentar</option>
                            <option value="Reducir" <?php echo ($objetivoIli == 'Reducir')?"selected":"" ?>>Reducir</option>
                            <option value="Mantener" <?php echo ($objetivoIli == 'Mantener')?"selected":"" ?>>Mantener</option>
                        </select>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Valor Meta</label>
                        <input type="number" name="meta-ili" required="true" step="0.00005" min="0" value="{{$valorIli}}" />
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Unidad</label>
                        <select required="true" name="unidad-ili" id="unidad-ili" >
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
                        <select required="true" name="objetivo-mortalidad" id="objetivo-mortalidad" >
                            <option value="">Seleccione...</option>
                            <option value="Aumentar" <?php echo ($objetivoMortalidad == 'Aumentar')?"selected":"" ?>>Aumentar</option>
                            <option value="Reducir" <?php echo ($objetivoMortalidad == 'Reducir')?"selected":"" ?>>Reducir</option>
                            <option value="Mantener" <?php echo ($objetivoMortalidad == 'Mantener')?"selected":"" ?>>Mantener</option>
                        </select>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Valor Meta</label>
                        <input type="number" name="meta-mortalidad" required="true" step="0.005" min="0"  value="{{$valorMortalidad}}" />
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Unidad</label>
                        <select required="true" name="unidad-mortalidad" id="unidad-mortalidad" >
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
                        <select required="true" name="cobertura" readonly="true" >
                            <option value="100">100%</option>   
                        </select>
                    </div>  
                    <div class="columns small-12 medium-4">
                        <label><b>Meta Cumplimiento</b></label>
                        <select required="true" name="cumplimiento" >
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
                        <textArea required="true" name="alcance" style="height:auto; min-height:80px;" onkeyup="mostrarBtns(this,'div-DataGeneral-4')" >{{$pgrp->alcance}}</textArea>
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
        
        <div class="row columns text-center">
        <span id="div-DataGeneral-4">
            <input type="submit" class="button small" value="Guardar"/>
        </span>

        <a class="button small alert">Cancelar
        </a>
        </div>
    </form>
    
    
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
            $("#frm-editar-pgrp").on("submit",function(e){
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

