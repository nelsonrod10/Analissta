@extends('analissta.layouts.appSideBar')
<?php
use App\Http\Controllers\helpers;
use App\Hallazgos\Hallazgo;
use App\PeligrosHallazgosAccidente;
use App\CausasBasicasInmediata;
use App\Hallazgos\HallazgosCierre;
use App\InspeccionesObligatoriasSugerida;
use App\InspeccionesValoracione;
use App\Accidentes\AccidentesHallazgo;

    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    
    $xml_origenes = simplexml_load_file(base_path("archivosXML/Hallazgos/xml_Origenes.xml"));
    $xml_peligros = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    $xmlCausasBasicas    = simplexml_load_file(base_path("archivosXML/Hallazgos/xml_Causas_Basicas.xml"));
    $xmlCausasInmediatas = simplexml_load_file(base_path("archivosXML/Hallazgos/xml_Causas_Inmediatas.xml"));
    
    $origenesBD = Hallazgo::where('sistema_id',$sistema->id)
            ->orderBy('origen_id','asc')
            ->get()
            ->unique('origen_id');
    
    $hallazgo = Hallazgo::where('id',$idHallazgo)
            ->where('sistema_id',$sistema->id)
            ->get();
    
    $peligroAsociado = PeligrosHallazgosAccidente::where('sistema_id',$sistema->id)
            ->where('origen_id',$hallazgo[0]->id)
            ->where('origen_table','Hallazgos')
            ->get();
    
    $causasBasicasBD = CausasBasicasInmediata::where('sistema_id',$sistema->id)
            ->where('origen_id',$hallazgo[0]->id)
            ->where('origen_table','Hallazgos')
            ->where('tipo','Basica')
            ->get();
    
    $causasInmediatasBD = CausasBasicasInmediata::where('sistema_id',$sistema->id)
            ->where('origen_id',$hallazgo[0]->id)
            ->where('origen_table','Hallazgos')
            ->where('tipo','Inmediata')
            ->get();
    
    $nombreOrigen = $xml_origenes->xpath("//origenes/origen[@id={$hallazgo[0]->origen_id}]");
    $clasifPeligro = $xml_peligros->xpath("//peligros/clasificacion[id={$peligroAsociado[0]->clasificacion}]");
    $descPeligro = $xml_peligros->xpath("//peligros/clasificacion[id={$peligroAsociado[0]->clasificacion}]/listDescripciones/descripcion[id={$peligroAsociado[0]->categoria}]");
    
    
    
    $objFechaCierre = new DateTime($hallazgo[0]->fechaCierre);
    $diff = $objFechaActual->diff($objFechaCierre);
    
    if($hallazgo[0]->cerrado === "No"):
        if((int)$diff->format('%R%a') < 0):
            $estado = "Vencido";
            $color = "#cc0000";
        elseif ((int)$diff->format('%R%a') >= 0):
            $estado = "Abierto";
            $color = "#f29c13";
        endif;
    else:    
        $estado = "Cerrado";
        $color = "#339900";
    endif;
    
    
    
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

@include('analissta.Hallazgos.menuHallazgos')


@endsection
@section('content')
    @section('titulo-encabezado')
        Hallazgo
    @endsection
    
    @section('buttons-submenus')
        <a class="button small" href="{{route('crear-hallazgo')}}">Crear Hallazgo</a>
        <a class="button small warning" href="{{route('hallazgos')}}">Listado Hallazgos</a>
    @endsection
    
    <div class="row columns text-center">
        <h5><b>Reporte de Hallazgo</b></h5>
        <i>Este reporte se creó el {{$hallazgo[0]->created_at}}</i>
        @include('analissta.Asesores.crearEmpresa.errors')
    </div>
    <fieldset class="fieldset">
        @if ($hallazgo[0]->cerrado == "Si")
            <div class="row columns text-center ">
                <h5 class="middle success-2">Hallazgo Cerrado</h5>
            </div>
        @endif
        <!--<xsl:if test="@conteo-recurrencia > 1">
            <div class="row columns text-center ">
                <h5 class="middle warning-2">Hallazgo Recurrente</h5>
            </div>
        </xsl:if>-->

        <div class="row">
            <div class="columns small-6">
                <div class="row">
                <div class="columns small-4"><b>Origen:</b></div>
                <div class="columns small-8">{{$nombreOrigen[0]->nombre}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Riesgo Asociado:</b></div>
                    <div class="columns small-8">{{ucwords(strtolower($clasifPeligro[0]->nombre))}}, {{ucwords(strtolower($descPeligro[0]->nombre))}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Centro Trabajo:</b></div>
                    <div class="columns small-8">{{$hallazgo[0]->centroTrabajo->nombre}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Proceso:</b></div>
                    <div class="columns small-8">{{$hallazgo[0]->proceso->nombre}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Fecha Hallazgo:</b></div>
                    <div class="columns small-8">{{$hallazgo[0]->fechaHallazgo}}</div>
                </div>
                <div class="row">
                    <div class="columns small-4"><b>Estado:</b></div>
                    <div class="columns small-8">
                        <label class="label" style="background:<?php echo $color ?>">{{$estado}}</label>
                    </div>
                </div>
            </div>
            <div class="columns small-6">
                <div class="row">
                    <div class="columns small-6"><b>Responsable:</b></div>
                    <div class="columns small-6">{{$hallazgo[0]->cargoResponsable}}</div>
                </div>
                <div class="row">
                    <div class="columns small-6"><b>Acto/Condición:</b></div>
                    <div class="columns small-6">{{$hallazgo[0]->actoCondicion}}</div>
                </div>
                <div class="row">
                    <div class="columns small-6"><b>Tipo Acción:</b></div>
                    <div class="columns small-6">{{$hallazgo[0]->tipoAccion}}</div>
                </div>
                <div class="row">
                    <div class="columns small-6"><b>Plan de Acción:</b></div>
                    <div class="columns small-6">{{$hallazgo[0]->planAccion}}</div>
                </div>
                <div class="row">
                    <div class="columns small-6"><b>Fecha Cierre Propuesta:</b></div>
                    <div class="columns small-6">{{$hallazgo[0]->fechaCierre}}</div>
                </div>
                <!--<xsl:if test="count(cierreHallazgo)>1">
                    <div class="row">
                        <div class="columns small-6"><b>Reapertura de Hallazgo No :</b></div>
                        <div class="columns small-6">
                            <xsl:value-of select="count(cierreHallazgo)"/>
                             
                            <a style="color:blue; font-size:14px" href="#cierre-hallazgo"><b> Ver Historial</b></a>
                        </div>
                    </div>
                </xsl:if>
                <xsl:if test="(@estado='Cerrado' or @estado='Cerrado / Abierto') and @estadoCierreActual='cerrado'">
                    <div class="row">
                        <div class="columns small-6"><b>Fecha Cierre Real:</b></div>
                        <div class="columns small-6">
                            <xsl:value-of select="Acciones/@fechaCierreReal"/>
                        </div>
                    </div>
                </xsl:if>-->
            </div>
        </div>
        @if($hallazgo[0]->origen_id == 3)
            <?php
                $accidente = AccidentesHallazgo::where('sistema_id',$sistema->id)
                        ->where('hallazgo_id',$idHallazgo)
                        ->get();
            ?>
            <br/>
            <div class="row">
                <div class="columns small-12 callout success text-center">
                    <div><b>Este Hallazgo esta relacionado con un Accidente</b></div>
                    <a class="button tiny" href="{{route("accidente",['id'=>$accidente[0]->accidente_id])}}">Ver Accidente</a>
                </div>
            </div>
        @endif
        @if($hallazgo[0]->origen_id == 2)
            <br/>
            <div class="row">
                <div class="columns small-12 callout success text-center">
                    <div><b>Inspeccion(es) Relacionada(s) con este Hallazgo:</b></div>
                    <div><b></b></div>
                    <?php
                        $idInspeccion = $hallazgo[0]->origen_externo_id;
                        $tipoInspeccion = $hallazgo[0]->origen_externo_tipo;
                        
                        if($tipoInspeccion == "obligatoria" || $tipoInspeccion == "sugerida"){
                            $inspeccion = InspeccionesObligatoriasSugerida::find($idInspeccion);
                            $nombreRuta = "inspeccion-$tipoInspeccion";
                        }else{
                            $inspeccion = InspeccionesValoracione::find($idInspeccion);
                            $nombreRuta = "inspeccion-valoracion";
                        }
                    ?>
                    <br/>
                    <div class="columns small-6 medium-4">{{$inspeccion->nombre}}</div>
                    <div class="columns small-6 medium-4 end"><a class="button small" href="{{route($nombreRuta,["id"=>$idInspeccion])}}">Ver Inspección</a></div>
                </div>
            </div>
        @endif
        <hr/>
        <div class="row">
            <div class="columns small-12">
                <div><b>Descripción:</b></div>
                <fieldset class="fieldset">{{$hallazgo[0]->descripcion}}</fieldset>
            </div>
            <div class="columns small-12 text-center">
                <a class="button success-2" data-open="reveal-editarDatos-hallazgo">Editar Datos</a>
            </div>
        </div>
        <div class="row fieldset">
            <div class="columns small-12">
                <div class='text-center'><h5><b>Evidencias</b></h5></div>
                <br/>
                @if($hallazgo[0]->evidencias->where('origen_table','Hallazgo')->count() > 0)
                    <div class="row text-center">
                        <div class="columns small-12 medium-5"><b>Nombre del Archivo</b></div>
                        <div class="columns small-12 medium-3"><b>Fecha de creación</b></div>
                        <div class="columns small-12 medium-2 end"></div>
                    </div>    
                    @foreach($hallazgo[0]->evidencias()->where('origen_table','Hallazgo')->get() as $evidencia)
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
                <a class="button success-2" data-open="reveal-agregar-evidencia-hallazgo">Agregar Evidencia</a>
                @include('analissta.Hallazgos.modal-agregar-evidencia');
            </div>
        </div>
        <div class="reveal small" data-reveal="" id="reveal-editarDatos-hallazgo">
            <div class="row columns text-center">
                <h5 style="text-decoration:underline"><b>Editar Datos Hallazgo</b></h5>
            </div>
            <br/>
            <form method="POST" name="frm-editarDatos-hallazgo" action="{{route('editar-datos-hallazgo',['id'=>$idHallazgo])}}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="columns small-12 medium-4"><b class="middle">Cargo Responsable: </b></div>
                    <div class="columns small-12 medium-6 end">
                        <input type="text" class="input-required-paso-5" name="responsable" required="true" placeholder="Cargo Responsable" value="{{$hallazgo[0]->cargoResponsable}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12">
                        <p><b>Descripción del Hallazgo</b></p>
                        <textarea name="descripcion" sytle="min-height:100px;" required="true" class="input-required-paso-5" placeholder="Describa Brevemente el hallazgo" style="min-height:100px; height:auto">{{$hallazgo[0]->descripcion}}</textarea>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="columns small-12">
                        <input type="submit" value="Actualizar" class="button small success-2"/>
                        <a class="button small alert" data-close="">Cancelar</a>
                    </div>
                </div>
            </form>
            <button class="close-button" data-close="" aria-label="Close modal" type="button">
              <span aria-hidden="true">X</span>
            </button>
        </div>
        <div class="row">
            <div class="columns small-12">
                <ul class="tabs" data-tabs="" id="example-tabs">
                    <li class="tabs-title is-active" ><a style="font-size:16px;color: #3c3737;" href="#causasBasicas" aria-selected="true"><b>Causas Basicas</b></a></li>
                    <li class="tabs-title"><a style="font-size:16px;color: #3c3737;" href="#causasInmediatas"><b>Causas Inmediatas</b></a></li>
                    @if(count($hallazgo[0]->actividades) >0)
                        <li class="tabs-title"><a style="font-size:16px;color: #3c3737;" href="#actividades"><b>Actividades</b></a></li>
                    @endif
                    @if(count($hallazgo[0]->capacitaciones) >0)
                        <li class="tabs-title"><a style="font-size:16px;color: #3c3737;" href="#capacitaciones"><b>Capacitaciones</b></a></li>
                    @endif

                </ul>
            </div>
            <div class="tabs-content" data-tabs-content="tabs-actividades">
                <div class="tabs-panel is-active" id="causasBasicas">
                    <div class="row columns text-center">
                        <h5><b>Listado Causas Básicas</b></h5>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-9">
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
                        <h5><b>Observaciones.</b></h5>
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
                <div class="tabs-panel" id="causasInmediatas">
                    <div class="row columns text-center">
                        <h5><b>Listado Causas Inmediatas</b></h5>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-9">
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
                        <h5><b>Observaciones.</b></h5>
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
                @if(count($hallazgo[0]->actividades) >0)
                    <div class="tabs-panel" id="actividades">
                        <div class="row">
                            <div class="columns small-12 medium-9 small-centered text-center">
                                <h5><b>Listado Actividades Asociadas</b></h5>
                                <i>Haga click en la actividad que desea ver</i>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="columns small-12 medium-9 small-centered">
                                <div class="row">
                                    <div class="columns small-6 text-center">
                                        <b>Nombre Actividad</b>
                                    </div>
                                    <div class="columns small-3 text-center end">
                                        <b>Ejecución (%)</b>
                                    </div>
                                </div>
                                @foreach($hallazgo[0]->actividades as $actividad)
                                    <div class="row">
                                        <div class="columns small-6 text-center">
                                            <a style="text-decoration:underline" href="{{route('actividad-hallazgo',['id'=>$actividad->id])}}">{{$actividad->nombre}}</a>
                                        </div>
                                        <div class="columns small-3 text-center end">{{$actividad->ejecucionTotal}}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>    
                    </div>
                @endif
                @if(count($hallazgo[0]->capacitaciones) >0)
                    <div class="tabs-panel" id="capacitaciones">
                        <div class="row columns text-center">
                            <h5><b>Listado Capacitaciones Asociadas</b></h5>
                            <i>Haga click en la capacitación que desea ver</i>
                        </div>
                        <br/>
                        <div class="row columns">
                            <div class="columns small-12 medium-9 small-centered">
                                <div class="row">
                                    <div class="columns small-6 text-center">
                                        <b>Nombre Capacitación</b>
                                    </div>
                                    <div class="columns small-3 text-center end">
                                        <b>Ejecución (%)</b>
                                    </div>
                                </div>
                                @foreach($hallazgo[0]->capacitaciones as $capacitacion)
                                    <div class="row">
                                        <div class="columns small-6 text-center">
                                            <a style="text-decoration:underline" href="{{route('capacitacion-hallazgo',['id'=>$capacitacion->id])}}">{{$capacitacion->nombre}}</a>
                                        </div>
                                        <div class="columns small-3 text-center end">{{$capacitacion->ejecucionTotal}}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>    
                    </div>
                @endif
            </div>    
        </div>
        <hr/>
        <div class="row" id="cierre-hallazgo">
            <div class="columns small-12">
                <div class="row columns text-center">
                    <h5 style="text-decoration:underline;color:white;background:gray"><b>CIERRE HALLAZGO:</b></h5>
                </div>

                <div class="row columns">
                    <i>
                        <ul>
                            <li>
                                <span>La fecha de cierre propuesta es el (aaaa/mm/dd) <b>{{$hallazgo[0]->fechaCierre}}</b>,</span>
                                <span> este hallazgo se encuentra: <label class="label" style="background:<?php echo $color ?>">{{$estado}}</label></span>
                                <div>
                                    <a style="color:blue" onclick="$('.info-cierre-hallazgos').toggleClass('hide')"><b> Ver mas...</b></a>
                                </div>

                            </li>
                            <li class="info-cierre-hallazgos hide">El Hallazgo se cerrara automáticamente en el momento en que las actividades y capacitaciones asociadas con este hallazgo se hayan desarrollado en un <b>100 %</b></li>
                            <li class="info-cierre-hallazgos hide">Tipos de estados: 
                                <div class="row">
                                    <div class="columns small-1"><span class="label tiny warning"> Abierto </span></div>
                                    <div class="columns small-11"><span style="font-size:13px"> NO se han ejecutado todas las actividades y capacitaciones, y <b>aún no se ha llegado</b> a la fecha de cierre propuesta.</span></div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="columns small-1"><span class="label tiny alert"> Vencido </span></div>
                                    <div class="columns small-11"><span style="font-size:13px"> NO se han ejecutado todas las actividades y capacitaciones, y ya <b>se superó</b> fecha de cierre propuesta</span></div>
                                </div>
                                <div class="row">
                                    <div class="columns small-1"><span class="label tiny success-2"> Cerrado </span></div>
                                    <div class="columns small-11"><span style="font-size:13px"> Todas las actividades y/o capacitaciones ejecutadas al 100%, y <b>realizadas antes de</b> la fecha de cierre propuesta</span></div>
                                </div>
                            </li>
                        </ul>
                    </i>
                </div>
                <br class="info-cierre-hallazgos hide"/>
            </div>
        </div>
<!-- Si el hallazgo esta cerrado, aparece esta información--> 
        @if($hallazgo[0]->cerrado == "Si")
            <?php
                
                $cierres = HallazgosCierre::where('sistema_id',$sistema->id)
                        ->where('hallazgo_id',$idHallazgo)
                        ->get();
                $cierresEficaces = HallazgosCierre::where('sistema_id',$sistema->id)
                        ->where('hallazgo_id',$idHallazgo)
                        ->where('eficaz','Si')
                        ->get();
            ?>
            <div class="row">
                <div class="columns small-12" id="div-info-cierre">
                    <h5>
                        <b>Información sobre el Cierre:</b>
                    </h5>
                </div>
            </div>
            <fieldset class="fieldset">
                <div class="row">
                    <div class="columns small-6">
                        <b>Fecha de Creación del Hallazgo: </b>{{$hallazgo[0]->fechaHallazgo}}
                    </div>
                    <!--<div class="columns small-6">
                        <b>Fecha de Cierre Real: </b><xsl:value-of select="Acciones/@fechaCierreReal"/>
                    </div>-->
                </div>
                @if(count($cierresEficaces) == 0)
                    <div class="row columns text-center">
                        <h5 style="text-decoration:underline;color:white;background:red"><b>Diligencie el siguiente formulario</b></h5>
                    </div>
                    <form method="POST" name="frm-cierre-Hallazgo" action="{{route('cierre-hallazgo',["id"=>$idHallazgo])}}">
                        {{ csrf_field() }}
                        <br/>
                        <div class="row">
                            <div class="columns small-12 medium-3"><b>¿Acciones Eficaces?</b></div>
                            <div class="columns small-12 medium-4 end">
                                <select name="eficaz" id="accionEficaz" required="true">
                                    <option value="">Seleccione...</option>
                                    <option value="Si">SI</option>
                                    <option value="No">NO</option>
                                </select>
                            </div>
                        </div>    
                        <div id="optimizarPlan" class="row hide">
                            <div class="columns small-12 medium-3"><b>Optimizar Plan de Accion: </b></div>
                            <div class="columns small-12 medium-8 end">
                                <div class="row columns">
                                    <input type="radio" id="radio1" checked="true" name="optimizar" class="input-optimizar" value="Actividades"></input>
                                    <label for="radio1">Crear más Actividades</label>
                                </div>
                                <div class="row columns">    
                                    <input type="radio" id="radio2" name="optimizar" class="input-optimizar" value="Capacitaciones"></input>
                                    <label for="radio2">Crear más Capacitaciones</label>
                                </div>
                                <div class="row columns">
                                    <input type="radio" id="radio3" name="optimizar" class="input-optimizar" value="Actividades y Capacitaciones" ></input>
                                    <label for="radio3">Crear más Actividades y Capacitaciones</label>
                                </div>
                            </div>
                            <div class="columns small-12 medium-3"><b>Fecha de Cierre: </b></div>
                            <div class="columns small-12 medium-4 end">
                                <input type="date" id="nuevaFechaCierre" name="nuevaFecha" class="input-optimizar" min="{{$fechaActual}}" value="{{$fechaActual}}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12">
                                <p><b>Observaciones </b></p>
                                <textarea name="observaciones" sytle="min-height:100px;" required="true" class="input-required-paso-5" placeholder="Escriba las observaciones que considere necesarias" style="min-height:100px; height:auto"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12">
                                <p><b>Ubicación Evidencias </b></p>
                                <textarea name="evidencias" sytle="min-height:100px;" required="true" class="input-required-paso-5" placeholder="Escriba donde se encuentran las evidencias fisicas o digitales" style="min-height:100px; height:auto"></textarea>
                            </div>
                        </div>
                        <div class="columns small-12 text-center">
                            <!--<div class="row columns small-12"><i><b class="msj-error-programarIntervencion" style="color:red"></b></i></div>
                            <a class="button tiny alert" href="hallazgos.php">Cancelar</a>-->
                                     
                            <input type="submit" class="button success" value="Guardar"/>

                        </div>
                    </form>
                @else
                    <div class="row columns text-center ">
                        <h5 class="middle success-2">Hallazgo Cerrado</h5>
                    </div>
                
                @endif
                <h5 style="text-decoration:underline"><b>Gestión del Hallazgo</b></h5>
                <?php
                $i=0;
                foreach($cierres as $cierre):
                    $i++;
                ?>
                    <hr/>
                    <div class="row columns">
                        @if($cierre->eficaz == 'Si')
                            <h5><b>Cierre Final</b></h5>
                        @else    
                            <h5><b>Reapertura No {{$i}}</b></h5>
                        @endif    
                    </div>
                    <div class="row columns">
                        <b>Fecha Reapertura: </b>{{$cierre->fechaReapertura}}
                    </div>
                    <div class="row columns">
                        <b>Acción Eficaz: </b>{{$cierre->eficaz}}
                    </div>

                    <xsl:if test="@accionEficaz = 'NO'">
                        <div class="row columns">
                            <b>Fecha de Cierre Propuesta: </b>{{$cierre->fechaCierrePropuesta}}
                        </div>
                    </xsl:if>
                    <br/>
                    <div class="row columns">
                        <div><b>Observaciones: </b></div>
                        <fieldset style="border:1px solid lightgray; height:auto; min-height:50px">
                            {{$cierre->observaciones}}
                        </fieldset>
                    </div>
                    <br/>
                    <div class="row columns">
                        <div><b>Ubicación Evidencia : </b></div>
                        <fieldset style="border:1px solid lightgray; height:auto; min-height:50px">
                            {{$cierre->evidencias}}
                        </fieldset>
                    </div>
                    <br/>
                <?php    
                    endforeach;    
                ?>
            </fieldset>
        @endif
<!-- Si el hallazgo esta cerrado, aparece esta información--> 
        <xsl:if test="(@estado = 'Abierto' or @estado = 'Abierto / Vencido') and count(cierreHallazgo) > 0">
            <xsl:call-template name="historial-hallazgo"></xsl:call-template>
        </xsl:if>                





        <br/>
    </fieldset>
    <script>
        $(document).ready(function(){
           $("#accionEficaz").on("change",function(){
               
               if($(this).val() === "Si"){
                   $("#optimizarPlan").addClass('hide');
                   $(".input-optimizar").each(function(){
                       $(this).removeAttr("required");
                   });
               }
               if($(this).val() === "No"){
                   $("#optimizarPlan").removeClass('hide');
                   $(".input-optimizar").each(function(){
                       $(this).attr("required","");
                   });
               }
           });
        });
    </script>  
@endsection

