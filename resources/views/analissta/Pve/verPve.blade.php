@extends('analissta.layouts.appSideBar')
<?php
use App\Http\Controllers\helpers;
use App\LargoPlazo;
use App\Peligro;

    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    
    $xmlPeligros = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    $clasifPeligro = $xmlPeligros->xpath("//peligros/clasificacion[id={$clasificacion}]");
    
    switch ($clasificacion) {
        case 1:
            $pve_table = "pve_fisicos";
            break;
        case 6:
            $pve_table = "pve_seguridades";
            break;
        default:
            $pve_table = "pve_generales";
            break;
    }
    $peligrosAsociados = LargoPlazo::where('sistema_id',$sistema->id)
            ->where('pve_id',$pve->id)
            ->where('pve_table',$pve_table)
            ->get();
    
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

@include('analissta.Pve.menuPve')


@endsection
@section('content')
    @section('titulo-encabezado')
        Vigilancia Epidemiológica 
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
    <form id="frm-crear-pve" name="dataGralRiesgoPrioritario" method="POST" action="{{route('datos-pve')}}">
        {{ csrf_field() }}
        <input type="hidden" class="hide" hidden="true" name="idPve" value="{{$pve->id}}"/>
        <input type="hidden" class="hide" hidden="true" name="clasificacion" value="{{$clasificacion}}"/>
        <div class="row">
            <div class="columns small-12 medium-2">
                <label for="nombrePrograma" class="middle"><b>Nombre Programa: </b></label>
            </div>
            <div class="columns small-12 medium-4">
                <input type="text" id="nombre" name="nombre" required="true" placeholder="Ejm: Manejo de Alturas" value="{{$pve->nombre}}" <?php echo ($pve->estado == 'Programado')?"readonly":""; ?>/>
            </div>
            <div class="columns small-12 medium-2">
                <label for="cargoResponsable" class="middle"><b>Cargo Responsable: </b></label>
            </div>
            <div class="columns small-12 medium-4 end">
                <input type="text" id="responsable" name="cargo" required="true" placeholder="Ejm: Gerente HSEQ" value="{{$pve->cargo}}"  <?php echo ($pve->estado == 'Programado')?"readonly":""; ?>/>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12"><h5><b>Objetivo del Programa</b></h5></div>
            <div class="columns small-12">
                <label><b>Descripción General</b><i> (Realiza una descripción textual)</i></label>
                <textArea name="objetivo" required="true" style="height:auto; min-height:80px;" onkeyup="mostrarBtns(this,'div-DataGeneral-2')"  <?php echo ($pve->estado == 'Programado')?"readonly":""; ?>>{{$pve->objetivo}}</textArea>
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
                    <input type="radio" name="lineaBase" id="SI" class="check-linea-base" value="SI"  required="true" <?php echo ($pve->estado == 'Programado')?"disabled":""; echo ($pve->lineaBase)?" checked":""; ?> />
                    <label for="SI"> SI </label>
                    <input type="radio" name="lineaBase" id="NO" class="check-linea-base" value="NO" required="true" <?php echo ($pve->estado == 'Programado')?"disabled":""; echo (!$pve->lineaBase)?" checked":""; ?> />
                    <label for="NO"> NO </label>
                </div>
                @if($pve->estado == 'Programado' && $pve->lineaBase) 
                    <div>
                        <a class="button small" onclick="$('#div-dataPeriodoAnterior').toggleClass('hide')">Ver Datos</a>
                    </div>
                @endif

            </div>
            <div class="columns small-12 hide" id="div-dataPeriodoAnterior">
                <?php
                    $inciEne=$inciFeb=$inciMar=$inciAbr=$inciMay=$inciJun=$inciJul=$inciAgo=$inciSep=$inciOct=$inciNov=$inciDic=null;
                    $prevEne=$prevFeb=$prevMar=$prevAbr=$prevMay=$prevJun=$prevJul=$prevAgo=$prevSep=$prevOct=$prevNov=$prevDic=null;
                    $nuevosEne=$nuevosFeb=$nuevosMar=$nuevosAbr=$nuevosMay=$nuevosJun=$nuevosJul=$nuevosAgo=$nuevosSep=$nuevosOct=$nuevosNov=$nuevosDic=null;
                    if($pve->estado == 'Programado'){
                        foreach ($pve->lineaBase as $linea) {
                            switch ($linea->nombreMeta) {
                                case "Eficacia Incidencia":
                                    $inciEne=$linea->Enero;$inciFeb=$linea->Febrero;$inciMar=$linea->Marzo;$inciAbr=$linea->Abril;$inciMay=$linea->Mayo;$inciJun=$linea->Junio;
                                    $inciJul=$linea->Julio;$inciAgo=$linea->Agosto;$inciSep=$linea->Septiembre;$inciOct=$linea->Octubre;$inciNov=$linea->Noviembre;$inciDic=$linea->Diciembre;
                                    break;
                                case "Eficacia Prevalencia":
                                    $prevEne=$linea->Enero;$prevFeb=$linea->Febrero;$prevMar=$linea->Marzo;$prevAbr=$linea->Abril;$prevMay=$linea->Mayo;$prevJun=$linea->Junio;
                                    $prevJul=$linea->Julio;$prevAgo=$linea->Agosto;$prevSep=$linea->Septiembre;$prevOct=$linea->Octubre;$prevNov=$linea->Noviembre;$prevDic=$linea->Diciembre;
                                    break;
                                case "Eficacia ili":
                                    $iliEne=$linea->Enero;$iliFeb=$linea->Febrero;$iliMar=$linea->Marzo;$iliAbr=$linea->Abril;$iliMay=$linea->Mayo;$iliJun=$linea->Junio;
                                    $iliJul=$linea->Julio;$iliAgo=$linea->Agosto;$iliSep=$linea->Septiembre;$iliOct=$linea->Octubre;$iliNov=$linea->Noviembre;$iliDic=$linea->Diciembre;
                                    break;
                                case "Eficacia Casos Nuevos":
                                    $nuevosEne=$linea->Enero;$nuevosFeb=$linea->Febrero;$nuevosMar=$linea->Marzo;$nuevosAbr=$linea->Abril;$nuevosMay=$linea->Mayo;$nuevosJun=$linea->Junio;
                                    $nuevosJul=$linea->Julio;$nuevosAgo=$linea->Agosto;$nuevosSep=$linea->Septiembre;$nuevosOct=$linea->Octubre;$nuevosNov=$linea->Noviembre;$nuevosDic=$linea->Diciembre;
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
                        <div class="columns small-12 medium-2"><b style="text-decoration:underline">Incidencia</b></div>
                        <div class="columns small-12 medium-2"><b style="text-decoration:underline">Prevalencia</b></div>
                        <div class="columns small-12 medium-2 end"><b style="text-decoration:underline">Casos Nuevos</b></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Enero</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="incidencia[]" type="number" step="0.00005" min="0" value='{{$inciEne}}' <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="prevalencia[]" type="number" step="0.00005" min="0" value="{{$prevEne}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="casos-nuevos[]" type="number" step="0.005" min="0" value="{{$nuevosEne}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Febrero</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="incidencia[]" type="number" step="0.00005" min="0"  value='{{$inciFeb}}' <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="prevalencia[]" type="number" step="0.00005" min="0" value="{{$prevFeb}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base"  name="casos-nuevos[]" type="number" step="0.005" min="0" value="{{$nuevosFeb}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Marzo</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="incidencia[]" type="number" step="0.00005" min="0"value='{{$inciMar}}' <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="prevalencia[]" type="number" step="0.00005" min="0" value="{{$prevMar}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="casos-nuevos[]" type="number" step="0.005" min="0" value="{{$nuevosMar}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Abril</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="incidencia[]" type="number" step="0.00005" min="0" value='{{$inciAbr}}' <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="prevalencia[]" type="number" step="0.00005" min="0" value="{{$prevAbr}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="casos-nuevos[]" type="number" step="0.005" min="0" value="{{$nuevosAbr}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Mayo</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="incidencia[]" type="number" step="0.00005" min="0" value='{{$inciMay}}' <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="prevalencia[]" type="number" step="0.00005" min="0" value="{{$prevMay}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="casos-nuevos[]" type="number" step="0.005" min="0" value="{{$nuevosMay}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Junio</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="incidencia[]" type="number" step="0.00005" min="0" value='{{$inciJun}}' <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="prevalencia[]" type="number" step="0.00005" min="0" value="{{$prevJun}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="casos-nuevos[]" type="number" step="0.005" min="0" value="{{$nuevosJun}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Julio</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="incidencia[]" type="number" step="0.00005" min="0" value='{{$inciJul}}' <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="prevalencia[]" type="number" step="0.00005" min="0" value="{{$prevJul}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="casos-nuevos[]" type="number" step="0.005" min="0" value="{{$nuevosJul}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Agosto</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="incidencia[]" type="number" step="0.00005" min="0" value='{{$inciAgo}}' <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="prevalencia[]" type="number" step="0.00005" min="0" value="{{$prevAgo}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="casos-nuevos[]" type="number" step="0.005" min="0" value="{{$nuevosAgo}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Septiembre</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="incidencia[]" type="number" step="0.00005" min="0" value='{{$inciSep}}' <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="prevalencia[]" type="number" step="0.00005" min="0" value="{{$prevSep}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="casos-nuevos[]" type="number" step="0.005" min="0" value="{{$nuevosSep}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Octubre</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="incidencia[]" type="number" step="0.00005" min="0" value='{{$inciOct}}' <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="prevalencia[]" type="number" step="0.00005" min="0" value="{{$prevOct}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="casos-nuevos[]" type="number" step="0.005" min="0" value="{{$nuevosOct}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Noviembre</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="incidencia[]" type="number" step="0.00005" min="0" value='{{$inciNov}}' <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="prevalencia[]" type="number" step="0.00005" min="0" value="{{$prevNov}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="casos-nuevos[]" type="number" step="0.005" min="0" value="{{$nuevosNov}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><label class="middle">Diciembre</label></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="incidencia[]" type="number" step="0.00005" min="0" value='{{$inciDic}}' <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2"><input class="input-linea-base" name="prevalencia[]" type="number" step="0.00005" min="0" value="{{$prevDic}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
                        <div class="columns small-12 medium-2 end"><input class="input-linea-base" name="casos-nuevos[]" type="number" step="0.005" min="0" value="{{$nuevosDic}}" <?php echo ($pve->estado == 'Programado')?"disabled":""?>/></div>
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
                        <select required="true" name="frecAnalisis" <?php echo ($pve->estado == 'Programado')?"disabled":""; ?>>
                            <option value="">Seleccione</option>
                            <option value="Trimestral"  <?php echo ($pve->frecuencia_analisis == 'Trimestral')?"selected":""; ?>>Trimestral</option>
                            <option value="Semestral" <?php echo ($pve->frecuencia_analisis == 'Semestral')?"selected":""; ?>>Semestral</option>
                            <option value="Anual" <?php echo ($pve->frecuencia_analisis == 'Anual')?"selected":""; ?>>Anual</option>   
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
                        <div>Si deacuerdo a los datos del programa de vigilancia del período anterior, se quiere <b>Reducir</b> la eficacia del objetivo para este nuevo período</div>
                        <!--<div class="text-center"><a class="button small btnObjetivo" onclick="selecObjetivoEspecifico(this,'Reducir')">Reducir</a></div>-->
                    </div>
                    <div class="columns small-12 medium-4 fieldset fieldsetObjetivo" id="fieldset-Aumentar">
                        <div class="text-center"><b>Aumentar</b></div>
                        <div>Si deacuerdo a los datos del programa de vigilancia del período anterior, se quiere <b>Aumentar</b> la eficacia del objetivo para este nuevo período</div>
                        <!--<div class="text-center"><a class="button small btnObjetivo" onclick="selecObjetivoEspecifico(this,'Aumentar')">Aumentar</a></div>-->
                    </div>
                    <div class="columns small-12 medium-4 fieldset fieldsetObjetivo" id="fieldset-Mantener">
                        <div class="text-center"><b>Mantener</b></div>
                        <div>Si deacuerdo a los datos del programa de vigilancia del período anterior, se quiere <b>Mantener</b> el mismo objetivo para este nuevo período</div>
                        <!--<div class="text-center"><a class="button small btnObjetivo" onclick="selecObjetivoEspecifico(this,'Mantener')">Mantener</a></div>-->
                    </div>
                </div>
            </xsl:if>

            <div id="div-DataGeneral-3">
                <?php
                    $objetivoIncidencia=$unidadIncidencia=$objetivoPrevalencia=$unidadPrevalencia=$objetivoIli=$unidadIli=$objetivoCasosNuevos=$unidadCasosNuevos="";
                    $valorIncidencia=$valorPrevalencia=$valorIli=$valorCasosNuevos=null;
                    if($pve->estado == 'Programado'){
                        foreach ($pve->metas as $meta) {
                            switch ($meta->nombreMeta) {
                                case "Eficacia Incidencia":
                                    $objetivoIncidencia = $meta->objetivo;
                                    $valorIncidencia = $meta->valorMeta;
                                    $unidadIncidencia= $meta->unidad;
                                    break;
                                case "Eficacia Prevalencia":
                                    $objetivoPrevalencia = $meta->objetivo;
                                    $valorPrevalencia = $meta->valorMeta;
                                    $unidadPrevalencia= $meta->unidad;
                                    break;
                                case "Eficacia Casos Nuevos":
                                    $objetivoCasosNuevos = $meta->objetivo;
                                    $valorCasosNuevos = $meta->valorMeta;
                                    $unidadCasosNuevos= $meta->unidad;
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
    
<!-- EFICACIA INCIDENCIA-->                               
                
                <div class="row">
                    <div class="columns small-12 medium-3"><label class="middle"><b>Eficacia Incidencia</b></label></div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Objetivo</label>
                        <select required="true" name="objetivo-incidencia" id="objetivo-incidencia" <?php echo ($pve->estado == 'Programado')?"disabled":""?>>
                            <option value="">Seleccione...</option>
                            <option value="Aumentar" <?php echo ($objetivoIncidencia == 'Aumentar')?"selected":"" ?>>Aumentar</option>
                            <option value="Reducir" <?php echo ($objetivoIncidencia == 'Reducir')?"selected":"" ?>>Reducir</option>
                            <option value="Mantener" <?php echo ($objetivoIncidencia == 'Mantener')?"selected":"" ?>>Mantener</option>
                        </select>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Valor Meta</label>
                        <input type="number" name="meta-incidencia" required="true" step="0.00005" min="0" value="{{$valorIncidencia}}" <?php echo ($pve->estado == 'Programado')?"readonly":""?>/>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Unidad</label>
                        <select required="true" name="unidad-incidencia" id="unidad-incidencia" <?php echo ($pve->estado == 'Programado')?"disabled":""?>>
                           <option value="">Seleccione...</option>
                           <option value="unidad" <?php echo ($unidadIncidencia == 'unidad')?"selected":"" ?>>Unidad</option>
                           <option value="porcentaje"  <?php echo ($unidadIncidencia == 'porcentaje')?"selected":"" ?>>Porcentaje %</option>
                        </select>
                    </div>

                </div>
<!-- EFICACIA PREVALENCIA-->                                
                <div class="row">
                    <div class="columns small-12 medium-3"><label class="middle"><b>Eficacia Prevalencia</b></label></div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Objetivo</label>
                        <select required="true" name="objetivo-prevalencia" id="objetivo-prevalencia" <?php echo ($pve->estado == 'Programado')?"disabled":""?>>
                            <option value="">Seleccione...</option>
                            <option value="Aumentar" <?php echo ($objetivoPrevalencia == 'Aumentar')?"selected":"" ?>>Aumentar</option>
                            <option value="Reducir" <?php echo ($objetivoPrevalencia == 'Reducir')?"selected":"" ?>>Reducir</option>
                            <option value="Mantener" <?php echo ($objetivoPrevalencia == 'Mantener')?"selected":"" ?>>Mantener</option>
                        </select>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Valor Meta</label>
                        <input type="number" name="meta-prevalencia" required="true" step="0.00005" min="0"  value="{{$valorPrevalencia}}" <?php echo ($pve->estado == 'Programado')?"readonly":""?>/>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Unidad</label>
                        <select required="true" name="unidad-prevalencia" id="unidad-prevalencia" <?php echo ($pve->estado == 'Programado')?"disabled":""?>>
                            <option value="">Seleccione...</option>
                            <option value="unidad" <?php echo ($unidadPrevalencia == 'unidad')?"selected":"" ?>>Unidad</option>
                            <option value="porcentaje"  <?php echo ($unidadPrevalencia == 'porcentaje')?"selected":"" ?>>Porcentaje %</option>
                        </select>
                    </div>

                </div>

<!-- EFICACIA TASA CASOS NUEVOS-->                                
                <div class="row">
                    <div class="columns small-12 medium-3"><label class="middle"><b>Eficacia Casos Nuevos</b></label></div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Objetivo</label>
                        <select required="true" name="objetivo-casos-nuevos" id="objetivo-casos-nuevos" <?php echo ($pve->estado == 'Programado')?"disabled":""?>>
                            <option value="">Seleccione...</option>
                            <option value="Aumentar" <?php echo ($objetivoCasosNuevos == 'Aumentar')?"selected":"" ?>>Aumentar</option>
                            <option value="Reducir" <?php echo ($objetivoCasosNuevos == 'Reducir')?"selected":"" ?>>Reducir</option>
                            <option value="Mantener" <?php echo ($objetivoCasosNuevos == 'Mantener')?"selected":"" ?>>Mantener</option>
                        </select>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Valor Meta</label>
                        <input type="number" name="meta-casos-nuevos" required="true" step="0.005" min="0"  value="{{$valorCasosNuevos}}" <?php echo ($pve->estado == 'Programado')?"readonly":""?>/>
                    </div>
                    <div class="columns small-4 medium-3">
                        <label class="show-for-small-only">Unidad</label>
                        <select required="true" name="unidad-casos-nuevos" id="unidad-casos-nuevos" <?php echo ($pve->estado == 'Programado')?"disabled":""?>>
                            <option value="">Seleccione...</option>
                            <option value="unidad" <?php echo ($unidadCasosNuevos == 'unidad')?"selected":"" ?>>Unidad</option>
                            <option value="porcentaje"  <?php echo ($unidadCasosNuevos == 'porcentaje')?"selected":"" ?>>Porcentaje %</option>
                        </select>
                    </div>
                </div>  

                <hr/>
                <div class="row">

<!--METAS COBERTURA Y CUMPLIMIENTO-->                                         
                    <div class="columns small-12 medium-4">
                        <label><b>Meta Cobertura</b></label>
                        <select required="true" name="cobertura" readonly="true" <?php echo ($pve->estado == 'Programado')?"disabled":""?>>
                            <option value="100">100%</option>   
                        </select>
                    </div>  
                    <div class="columns small-12 medium-4">
                        <label><b>Meta Cumplimiento</b></label>
                        <select required="true" name="cumplimiento" <?php echo ($pve->estado == 'Programado')?"disabled":""?>>
                            <option value="">Seleccione</option>
                            <option value="50" <?php echo ($pve->cumplimiento == '50')?"selected":"" ?>>50%</option>
                            <option value="60" <?php echo ($pve->cumplimiento == '60')?"selected":"" ?>>60%</option>
                            <option value="70" <?php echo ($pve->cumplimiento == '70')?"selected":"" ?>>70%</option>   
                            <option value="80" <?php echo ($pve->cumplimiento == '80')?"selected":"" ?>>80%</option>
                            <option value="90" <?php echo ($pve->cumplimiento == '90')?"selected":"" ?>>90%</option>
                            <option value="100" <?php echo ($pve->cumplimiento == '100')?"selected":"" ?>>100%</option>   
                        </select>
                    </div>   

                </div>
<!--- ALCANCE -->                                                                                                                                                                                                                                                      
                <div class="row">
                    <div class="columns small-12"><h5><b>Alcance</b></h5></div>
                    <div class="columns small-12">
                        <textArea required="true" name="alcance" style="height:auto; min-height:80px;" onkeyup="mostrarBtns(this,'div-DataGeneral-4')" <?php echo ($pve->estado == 'Programado')?"readonly":""?>>{{$pve->alcance}}</textArea>
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
        @if($pve->estado == 'Programado')
            <div class="row">
                <div class="columns small-4 small-centered">
                    <a class="button expanded success-2" href="{{route('editar-pve',['clasificacion'=>$clasificacion,'id'=>$pve->id])}}"> Editar Datos</a>
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
    
    @if($pve->estado == 'Programado')
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
            $("#frm-crear-pve").on("submit",function(e){
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

