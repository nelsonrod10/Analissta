@extends('analissta.layouts.appSideBar')
<?php
    use App\Http\Controllers\helpers;
    use App\Accidentes\Accidente;
    use App\PeligrosHallazgosAccidente;
    use App\Accidentes\AccidentesAfectacione;
    use App\CausasBasicasInmediata;
    
    $xml_peligros = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    $xml_cuerpo = simplexml_load_file(base_path("archivosXML/Accidentalidad/xml_PartesCuerpo.xml"));
    $xml_lesion = simplexml_load_file(base_path("archivosXML/Accidentalidad/xml_TipoLesion.xml"));
    $xml_agentes = simplexml_load_file(base_path("archivosXML/Accidentalidad/xml_AgentesAccidente.xml"));
    $xml_fuentes = simplexml_load_file(base_path("archivosXML/Accidentalidad/xml_FuentesLesion.xml"));
    $xmlCausasBasicas = simplexml_load_file(base_path("archivosXML/Hallazgos/xml_Causas_Basicas.xml"));
    $xmlCausasInmediatas = simplexml_load_file(base_path("archivosXML/Hallazgos/xml_Causas_Inmediatas.xml"));
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    
    $peligroAsociado = PeligrosHallazgosAccidente::where('sistema_id',$sistema->id)
            ->where('origen_id',$accidente->id)
            ->where('origen_table','Accidentes')
            ->get();
    
    $clasifPeligro = $xml_peligros->xpath("//peligros/clasificacion[id={$peligroAsociado[0]->clasificacion}]");
    $descPeligro = $xml_peligros->xpath("//peligros/clasificacion[id={$peligroAsociado[0]->clasificacion}]/listDescripciones/descripcion[id={$peligroAsociado[0]->categoria}]");
    
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

@include('analissta.Accidentes.menuAccidentes')


@endsection
@section('content')
    @section('titulo-encabezado')
        Accidentes 
    @endsection
    
    @section('buttons-submenus')
        <a class="button small" href="{{route('crear-accidente')}}">Crear Accidente</a>
        <a class="button small warning" href="{{route('accidentes')}}">Listado Accidentes</a>
    @endsection
    
    <div class="row columns text-center">
        <h5><b>Reporte Accidente</b></h5>
        <i>Este reporte se creó el {{$accidente->created_at}}</i>
    </div>
    <br/>
    <div class="row">
        <div class="columns small-12 callout alert text-center">
            <div><i><b>**Recuerde que la Resolución 1401 de 2015, Aritculo 4, numeral 2 exige: **</b></i></div>
            <div><i style="font-size:13px">Investigar todos los incidentes y accidentes de trabajo dentro de los quince (15) días siguientes a su ocurrencia, a través del equipo investigador, conforme lo determina la presente resolución.</i></div>
        </div>
    </div>
    <fieldset class="fieldset">
        <div class="row">
            <div class="columns small-12 text-center">
                <div style="background:gray;color:white"><b>Datos Generales</b></div>
                <br/>
            </div>
            <div class="columns small-6">
                <div class="row">
                <div class="columns small-4"><b>Fecha Accidente:</b></div>
                <div class="columns small-8">{{$accidente->fechaAccidente}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Hora Accidente:</b></div>
                    <div class="columns small-8">{{$accidente->horaAccidente}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Centro Trabajo:</b></div>
                    <div class="columns small-8">{{$accidente->centroTrabajo->nombre}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Proceso:</b></div>
                    <div class="columns small-8">{{$accidente->proceso->nombre}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Riesgo Asociado:</b></div>
                    <div class="columns small-8">{{ucwords(strtolower($clasifPeligro[0]->nombre))}}, {{ucwords(strtolower($descPeligro[0]->nombre))}}</div>
                </div>
            </div>
            <div class="columns small-6">
                <div class="row">
                    <div class="columns small-4"><b>Clasificación:</b></div>
                    <div class="columns small-8">{{$accidente->clasificacion}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Tipo Evento:</b></div>
                    <div class="columns small-8">{{$accidente->tipo_evento}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Afectación:</b></div>
                    <div class="columns small-8">{{$accidente->afectacion}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Empresa:</b></div>
                    <div class="columns small-8">{{$accidente->nombre_empresa}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Tipo Empresa:</b></div>
                    <div class="columns small-8">{{$accidente->empleado_tipo}}</div>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <!--<div class="row">
            <div class="columns small-12">
                <div><b>Riesgo Derivado:</b></div>
                <fieldset class="fieldset">
                    <xsl:value-of select="clasificacion/riesgoAsociado/riesgoDerivado"/>
                </fieldset>
            </div>
        </div>-->
        <div class="row">
            <div class="columns small-12">
                <div><b>Lugar Exácto:</b></div>
                <fieldset class="fieldset">{{$accidente->lugar}}</fieldset>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12">
                <div><b>Descripción:</b></div>
                <fieldset class="fieldset">{{$accidente->descripcion}}</fieldset>
            </div>
        </div>
        
        <div class="row">
            <div class="columns small-12 callout alert text-center">
                <div><i style="font-size:14px"><b>**Todo Accidente genera obligatoriamente un Hallazgo**</b></i></div>
                @if($accidente->incapacidad == 'Si')
                    <div><i style="font-size:14px"><b>**Este accidente generó incapacidad, por lo tanto debe reportarse el ausentimo correspondiente**</b></i></div>
                @endif
            </div>

            <div class="columns small-6 text-right end">
                <br/>
                <!--Cuando quede el codigo de hallazgo, modificar esta parte-->
                @if(isset($accidente->hallazgo->hallazgo_id))
                    <a class="button small" href="{{route('hallazgo',['id'=>$accidente->hallazgo->hallazgo_id])}}">Ver Hallazgo</a> 
                @else
                    <a class="button small alert" href="{{route('crear-hallazgo-accidente',['idAccidente'=>$accidente->id])}}">Configurar Hallazgo</a> 
                @endif    
            </div>   
            <div class="columns small-6 text-left end">
                <br/>
            @if($accidente->incapacidad == 'Si')
                <!--Cuando quede el codigo de ausentismo, modificar esta parte--> 
                @if(isset($accidente->ausentismo->ausentismo_id))
                    <a class="button small success-2" href="{{route('ausentismo',['id'=>$accidente->ausentismo->ausentismo_id])}}">Ver Ausentismo</a> 
                @else
                    <a class="button small warning" href="{{route('crear-ausentismo-accidente',['idAccidente'=>$accidente->id])}}">Configurar Ausentismo</a> 
                @endif
            @endif
            </div>  
        </div>
        <hr/>
        <div class="row">
            <div class="columns small-12 text-center">
                <div style="background:gray;color:white"><b>Evidencias</b></div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="columns small-12">
                @if($accidente->evidencias->where('origen_table','Accidente')->count() > 0)
                    <div class="row text-center">
                        <div class="columns small-12 medium-5"><b>Nombre del Archivo</b></div>
                        <div class="columns small-12 medium-3"><b>Fecha de creación</b></div>
                        <div class="columns small-12 medium-2 end"></div>
                    </div>    
                    @foreach($accidente->evidencias()->where('origen_table','Accidente')->get() as $evidencia)
                        <div class="row">
                            <div class="columns small-12 medium-5">{{pathinfo($evidencia->evidencia,2)}}</div>
                            <div class="columns small-12 medium-3">{{$evidencia->created_at}}</div>
                            <div class="columns small-12 medium-2 end"><a class="button tiny" href="{{route('gestionar-evidencia.show',$evidencia)}}">Descargar</a></div>
                        </div>    
                    @endforeach
                @else
                    <div class="callout success text-center">
                        <div class="row columns"><i class="fi-info" style="font-size: 28px; color:#ff6600"></i></div>
                        <div><i>No se ha agregado ninguna evidencia.</i></div>
                    </div>
                @endif
            </div>
            <div class="columns small-12 text-center">
                <a class="button success-2" data-open="reveal-agregar-evidencia-accidente">Agregar Evidencia</a>
                @include('analissta.Accidentes.modal-agregar-evidencia');
            </div>
        </div>
        <div class="row">
            <div class="columns small-12 text-center">
                <div style="background:gray;color:white"><b>Datos Específicos</b></div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="columns small-12 medium-6">
                <div class="row">
                    <div class="columns small-12 text-center" >
                        <div style="border-bottom:1px solid gray"><b>Datos Accidentado</b></div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Nombre:</b></div>
                    <div class="columns small-8">{{$accidente->accidentado->nombre}} {{$accidente->accidentado->apellidos}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Genero:</b></div>
                    <div class="columns small-8">{{$accidente->accidentado->genero}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Identificación:</b></div>
                    <div class="columns small-8">{{$accidente->accidentado->identificacion}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Cargo:</b></div>
                    <div class="columns small-8">{{$accidente->accidentado->cargo}}</div>
                </div>
            </div>
            <div class="columns small-12 medium-6">
                <div class="row">
                    <div class="columns small-12 text-center" >
                        <div style="border-bottom:1px solid gray"><b>Datos Accidente</b></div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Jornada:</b></div>
                    <div class="columns small-8">{{$accidente->jornada}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Labor Habitual:</b></div>
                    <div class="columns small-8">{{$accidente->labor_habitual}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Incapacidad:</b></div>
                    <div class="columns small-8">{{$accidente->incapacidad}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Accidente Grave:</b></div>
                    <div class="columns small-8">{{$accidente->accidente_grave}}</div>
                </div>
            </div>
        </div>
        <br/>
        <div class="columns small-12 callout success text-center">
            @if($accidente->accidente_grave = 'Si')
                <div><i><b>Este accidente esta reportado como GRAVE, por lo tanto tenga presente el articulo 14 del decreto 472 de 2015 que exige: </b></i></div>
                <div><i style="font-size:12px"><b>Artículo 14.</b> Reporte de accidentes y enfermedades a las Direcciones Territoriales y Oficinas Especiales. Los empleadores reportarán los accidentes graves y mortales, así como las enfermedades diagnosticadas como laborales, directamente a la Dirección Territorial u Oficinas Especiales correspondientes, dentro de los dos (2) días hábiles siguientes al evento o recibo del diagnóstico de la enfermedad, independientemente del reporte que deben realizar a las Administradoras de Riesgos Laborales y Empresas Promotoras de Salud y lo establecido en el artículo 40 del Decreto 1530 de 1996. </i></div>
            @endif
        </div>
        <hr/>
        <div class="row">
            <div class="columns small-12 text-center">
                <div style="background:gray;color:white"><b>Afectación</b></div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="columns small-12 medium-6">
                <div class="row">
                    <div class="columns small-12 text-center" >
                        <div style="border-bottom:1px solid gray"><b>Partes del Cuerpo</b></div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12" style="min-height:100px;height:auto">
                        <?php
                            $cuerpo = AccidentesAfectacione::where('sistema_id',$sistema->id)
                                    ->where('accidente_id',$accidente->id)
                                    ->where('tipo','Cuerpo')
                                    ->get()
                        ?>
                        <ul>
                        @foreach($cuerpo as $itemCuerpo)
                            <?php
                                $categoriaCuerpo = $xml_cuerpo->xpath("//partesCuerpo/tipo[@id=$itemCuerpo->categoria]/nombre");
                                $parteCuerpo = $xml_cuerpo->xpath("//partesCuerpo/tipo[@id=$itemCuerpo->categoria]/parte[@id=$itemCuerpo->descripcion]");
                            ?>
                            <li>
                                <b>{{$categoriaCuerpo[0]}}</b> - {{$parteCuerpo[0]}}
                                @if($parteCuerpo[0]->attributes()['textAuxiliar'])
                                    <i>.({{$parteCuerpo[0]->attributes()['textAuxiliar']}})</i>
                                @endif
                            </li>
                        @endforeach
                        </ul>    
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 text-center" >
                        <div style="border-bottom:1px solid gray"><b>Agentes</b></div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12" style="min-height:100px;height:auto">
                        <?php
                            $agentes = AccidentesAfectacione::where('sistema_id',$sistema->id)
                                    ->where('accidente_id',$accidente->id)
                                    ->where('tipo','Agentes')
                                    ->get();
                        ?>
                        <ul>
                        @foreach($agentes as $itemAgente)
                            <?php
                                $tipoAgente = $xml_agentes->xpath("//agentes/tipo[@id=$itemAgente->categoria]/nombre");
                                $agente = $xml_agentes->xpath("//agentes/tipo[@id=$itemAgente->categoria]/agente[@id=$itemAgente->descripcion]");
                            ?>
                            <li>
                                <b>{{$tipoAgente[0]}}</b> - {{$agente[0]}}
                                @if($agente[0]->attributes()['textAuxiliar'])
                                    <i>.({{$agente[0]->attributes()['textAuxiliar']}})</i>
                                @endif
                            </li>
                        @endforeach
                        </ul>  
                    </div>
                </div>
            </div>
            <div class="columns small-12 medium-6">
                <div class="row">
                    <div class="columns small-12 text-center"  >
                        <div style="border-bottom:1px solid gray"><b>Lesiones</b></div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12" style="min-height:100px;height:auto">
                        <?php
                            $lesiones = AccidentesAfectacione::where('sistema_id',$sistema->id)
                                    ->where('accidente_id',$accidente->id)
                                    ->where('tipo','Lesion')
                                    ->get();
                        ?>
                        <ul>
                        @foreach($lesiones as $itemLesion)
                            <?php
                                $lesion = $xml_lesion->xpath("//lesiones/tipo[@id=$itemLesion->descripcion]");
                            ?>
                            <li>{{$lesion[0]}}</li>
                        @endforeach
                        </ul>  
                    </div>
                </div>
                
                <div class="row">
                    <div class="columns small-12 text-center" >
                        <div style="border-bottom:1px solid gray"><b>Fuentes</b></div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12" style="min-height:100px;height:auto">
                        <?php
                            $fuentes = AccidentesAfectacione::where('sistema_id',$sistema->id)
                                    ->where('accidente_id',$accidente->id)
                                    ->where('tipo','Fuentes')
                                    ->get();
                        ?>
                        <ul>
                        @foreach($fuentes as $itemFuente)
                            <?php
                                $tipoFuente = $xml_fuentes->xpath("//fuentesLesion/tipo[@id=$itemFuente->categoria]/nombre");
                                $fuente = $xml_fuentes->xpath("//fuentesLesion/tipo[@id=$itemFuente->categoria]/fuentes[@id=$itemFuente->descripcion]");
                            ?>
                            <li>
                                <b>{{$tipoFuente[0]}}</b> - {{$fuente[0]}}
                                @if($fuente[0]->attributes()['textAuxiliar'])
                                    <i>.({{$fuente[0]->attributes()['textAuxiliar']}})</i>
                                @endif
                            </li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12 text-center">
                <div style="background:gray;color:white"><b>Causas</b></div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="columns small-12 medium-6">
                <div class="row">
                    <div class="columns small-12 text-center" >
                        <div style="border-bottom:1px solid gray"><b>Causas Inmediatas</b></div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12" style="min-height:100px;height:auto">
                        <?php
                            $causasInmediatasBD = CausasBasicasInmediata::where('sistema_id',$sistema->id)
                                ->where('origen_id',$accidente->id)
                                ->where('origen_table','Accidentes')
                                ->where('tipo','Inmediata')
                                ->get();
                        ?>
                        <ul>
                            @foreach($causasInmediatasBD as $causaInmediata)
                                @php 
                                  $arrCausasInmediatas  = explode(",",$causaInmediata->descripcion);
                                @endphp
                                @foreach($arrCausasInmediatas as $descCausaInmediata)
                                    @php
                                        $nombCausa  = $xmlCausasInmediatas->xpath("//causasInmediatas/factor[@factor=$causaInmediata->factor]/categoria[@id=$causaInmediata->categoria]/descripcion/item[id=$descCausaInmediata]");
                                    @endphp
                                    <li>{{$nombCausa[0]->nombre}}</li>
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row columns text-center">
                    <b>Observaciones.</b>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-9">
                    <ul>
                    @foreach($causasInmediatasBD as $causaInmediata)
                        <li>{{$causaInmediata->observaciones}}</li>
                    @endforeach    
                    </ul>
                    </div>
                </div>
            </div>
            <div class="columns small-12 medium-6">
                <div class="row">
                    <div class="columns small-12 text-center" >
                        <div style="border-bottom:1px solid gray"><b>Causas Basicas</b></div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12" style="min-height:100px;height:auto">
                        <?php
                            $causasBasicasBD = CausasBasicasInmediata::where('sistema_id',$sistema->id)
                                ->where('origen_id',$accidente->id)
                                ->where('origen_table','Accidentes')
                                ->where('tipo','Basica')
                                ->get();
                        ?>
                        <ul>
                            @foreach($causasBasicasBD as $causaBasica)
                                @php 
                                  $arrCausasBasicas  = explode(",",$causaBasica->descripcion);
                                @endphp
                                @foreach($arrCausasBasicas as $descCausaBasica)
                                    @php
                                        $nombCausa  = $xmlCausasBasicas->xpath("//causasBasicas/factor[@factor=$causaBasica->factor]/categoria[@id=$causaBasica->categoria]/descripcion/item[id=$descCausaBasica]");
                                    @endphp
                                    <li>{{$nombCausa[0]->nombre}}</li>
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row columns text-center">
                    <b>Observaciones.</b>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-9">
                    <ul>
                    @foreach($causasBasicasBD as $causaBasica)
                        <li>{{$causaBasica->observaciones}}</li>
                    @endforeach    
                    </ul>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="row">
                <div class="columns small-12 text-center">
                    <div style="background:gray;color:white"><b>Costos</b></div>
                </div>
            </div>
            <br/>
            <div class="columns small-12 medium-6">
                <div class="row">
                    <div class="columns small-12" >
                        <div><b>Directos: </b>$ {{$accidente->costos->costos}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12" >
                        <div><b>Seguimiento Accidente: </b>$ {{$accidente->costos->seguimiento}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12" >
                        <div><b>Reemplazo Accidentado: </b>$ {{$accidente->costos->persona}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12" >
                        <div><b>Imagen Corporativa: </b>$ {{$accidente->costos->imagen_corporativa}}</div>
                    </div>
                </div>
            </div>
            <div class="columns small-12 medium-6">
                <div class="row">
                    <div class="columns small-12" >
                        <div><b>Paradas Producción: </b>$ {{$accidente->costos->operacion}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12" >
                        <div><b>Legales (ejm: Demandas): </b>$ {{$accidente->costos->legales}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12" >
                        <div><b>Productividad: </b>$ {{$accidente->costos->productividad}}</div>
                    </div>
                </div>

            </div>

        </div>
        <div class="row columns text-center">
                <?php
                    $totalCostos = $accidente->costos->costos + $accidente->costos->persona + $accidente->costos->operacion + $accidente->costos->productividad +$accidente->costos->seguimiento + $accidente->costos->imagen_corporativa + $accidente->costos->legales;
                ?>
                <h5><b>Costo Total: $
                    <span id="span-costoTotal">
                    {{$totalCostos}}
                </span> (COP)</b>
                </h5>
            </div>
        
    </fieldset>        
@endsection

