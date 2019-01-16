@extends('analissta.layouts.appProgramarMedida')
@section('content')
<?php
    use App\Proceso;
    use App\Peligro;
    use App\CortoPlazo;
    use App\LargoPlazo;
    
    $proceso = Proceso::find($actividad->proceso_id);
    $peligro = Peligro::find(session('idPeligro'));
    
    $xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    $xpath_nombrePeligro = $xml_GTC45->xpath("//peligros/clasificacion[id=$peligro->clasificacion]");
    $xpath_descPeligro = $xml_GTC45->xpath("//peligros/clasificacion[id=$peligro->clasificacion]/listDescripciones/descripcion[id=$peligro->categoria]");
    $clasificacionPeligro = $xpath_nombrePeligro[0]->nombre;
    $categoriaPeligro = $xpath_descPeligro[0]->nombre;
    
    $peligroBD = Peligro::find(session('idPeligro'));
    $efecto = $peligroBD->efectoPersona;
    
    $fuenteCP = $medioCP = $individuoCP = $administrativoCP = "";
    $fuenteLP = $medioLP = $individuoLP = $administrativoLP = "";
        
    if($peligroBD->cortoPlazo){
        $fuenteCP = $peligroBD->cortoPlazo->fuente;
        $medioCP = $peligroBD->cortoPlazo->medio;
        $individuoCP = $peligroBD->cortoPlazo->individuo;
        $administrativoCP = $peligroBD->cortoPlazo->administrativo;
    }
    
    if($peligroBD->largoPlazo){
        $fuenteLP = $peligroBD->largoPlazo->fuente;
        $medioLP = $peligroBD->largoPlazo->medio;
        $individuoLP = $peligroBD->largoPlazo->individuo;
        $administrativoLP = $peligroBD->largoPlazo->administrativo;
    }
    
?>

<div class="row">
    <div class="text-center"><h5><b>Proceso | {{ ucwords($proceso->nombre) }}</b></h5></div>
    <div class="text-center"><b>Actividad | {{ ucwords($actividad->nombre) }}</b></div>
</div>
<div class="row">
    <div class="columns small-12 medium-8 small-centered">
        <form id="frm-nuevoPeligroEfectos" name="frm-nuevoPeligroEfectos" method="POST" action="{{ route('controles-peligro',['idActividad'=>$actividad->id]) }}">
            {{ csrf_field() }}  
            <div class="row columns text-center" >
                <div style="text-decoration: underline"><b>VALORACION PELIGRO </b></div>
                <div>{{ $clasificacionPeligro }} - {{ $categoriaPeligro }}</div>
            </div>
            <div class="row columns text-center" style="text-decoration: underline">
                <b>CONTROLES EXISTENTES</b>
            </div>
            @include('analissta.Asesores.crearEmpresa.errors')
            <br/>
            @if($peligro->efectoPersona === 'Corto Plazo' || $peligro->efectoPersona === 'Largo Plazo')
                <?php
                    if($peligro->efectoPersona === 'Corto Plazo'){
                        $textoEfecto = "Accidentes de Trabajo";
                    }
                    if($peligro->efectoPersona === 'Largo Plazo'){
                        $textoEfecto = "Enfermedades Laborales";
                    }
                ?>
            <div class="row">
                <div class="columns small-12 text-center" style="background:orange;color:white">
                    Controles existentes para {{ $textoEfecto}}
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="columns small-12 medium-2">
                    <b>Fuente: </b>
                </div>
                <div class="columns small-12 medium-8 end">
                    <textarea id="contFuentePeligro" name="fuente"  style="font-size:14px;height:70px" placeholder="Actualmente como esta controlando aquello que genera el peligro (ejm: maquina, equipo, herramienta)">@if($efecto == 'Corto Plazo'){{ $fuenteCP }}@else{{ $fuenteLP }}@endif</textarea>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12 medium-2">
                    <b>Medio: </b>
                </div>
                <div class="columns small-12 medium-8 end">
                    <textarea id="contMedioPeligro" name="medio"  style="font-size:14px;height:70px"  placeholder="Actualmente como esta controlando el ambiente que rodea la fuente del peligro">@if($efecto == 'Corto Plazo'){{ $medioCP }}@else{{ $medioLP }}@endif</textarea>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12 medium-2">
                    <b>Individuo: </b>
                </div>
                <div class="columns small-12 medium-8 end">
                    <textarea id="contIndividuoPeligro" name="individuo"  style="font-size:14px;height:70px" placeholder="Actualmente como proteje a las personas que estan expuestas al peligro (ejm: EPP)">@if($efecto == 'Corto Plazo'){{ $individuoCP }}@else{{ $individuoLP }}@endif</textarea>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12 medium-2">
                    <b>Administrativos: </b>
                </div>
                <div class="columns small-12 medium-8 end">
                    <textarea id="contAdmonPeligro" name="admon"  style="font-size:14px;height:70px"  placeholder="Actualmente existe algun documento, procedimiento, instrutivo etc, relacionado al peligro">@if($efecto == 'Corto Plazo'){{ $administrativoCP }}@else{{ $administrativoLP }}@endif</textarea>
                </div>
            </div>
            @endif
            
            @if($peligro->efectoPersona === 'Corto y Largo Plazo')
            <div class="row">
                <div class="columns small-12 medium-6 text-center">
                    <div style="background:orange;color:white">
                        Controles existentes para Accidentes de Trabajo
                    </div>
                </div>
                <div class="columns medium-6 text-center show-for-medium">
                    <div style="background:orange;color:white">
                        Controles existentes para Enfermedades Laborales
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-2">
                            <b>Fuente: </b>
                        </div>
                        <div class="columns small-12 medium-12 end">
                            <textarea id="contFuentePeligroCP" name="fuenteCP"  style="font-size:14px;height:70px" placeholder="Controles en la fuente">{{$fuenteCP}}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-2">
                            <b>Medio: </b>
                        </div>
                        <div class="columns small-12 medium-12 end">
                            <textarea id="contMedioPeligroCP" name="medioCP"  style="font-size:14px;height:70px"  placeholder="Controles en el medio">{{$medioCP}}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-2">
                            <b>Individuo: </b>
                        </div>
                        <div class="columns small-12 medium-12 end">
                            <textarea id="contIndividuoPeligroCP" name="individuoCP"  style="font-size:14px;height:70px" placeholder="Controles en el individuo">{{$individuoCP}}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12">
                            <b>Administrativos: </b>
                        </div>
                        <div class="columns small-12 medium-12 end">
                            <textarea id="contAdmonPeligroCP" name="admonCP"  style="font-size:14px;height:70px"  placeholder="Controles administrativos">{{$administrativoCP}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 text-center show-for-small-only">
                            <div style="background:#01549b;color:white">
                                Controles existentes para Enfermedades Laborales
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-2">
                            <b>Fuente: </b>
                        </div>
                        <div class="columns small-12 medium-12 end">
                            <textarea id="contFuentePeligroLP" name="fuenteLP"  style="font-size:14px;height:70px" placeholder="Controles en la fuente">{{$fuenteLP}}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-2">
                            <b>Medio: </b>
                        </div>
                        <div class="columns small-12 medium-12 end">
                            <textarea id="contMedioPeligroLP" name="medioLP"  style="font-size:14px;height:70px"  placeholder="Controles en el medio">{{$medioLP}}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-2">
                            <b>Individuo: </b>
                        </div>
                        <div class="columns small-12 medium-12 end">
                            <textarea id="contIndividuoPeligroLP" name="individuoLP"  style="font-size:14px;height:70px" placeholder="Controles en el individuo">{{$individuoLP}}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12">
                            <b>Administrativos: </b>
                        </div>
                        <div class="columns small-12 medium-12 end">
                            <textarea id="contAdmonPeligroLP" name="admonLP"  style="font-size:14px;height:70px"  placeholder="Controles administrativos">{{$administrativoLP}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row text-center">
                <div class="columns small-12" data-tabs="">
                    <a class="button small" href="{{ route('efectos-peligro',['idActividad'=>$actividad->id])}}">Anterior</a>
                    <xsl:text> </xsl:text>
                    <a class="button small alert" data-open="modal-confirm-cancelValoracion">Cancelar</a>
                    <xsl:text> </xsl:text>
                    <input type="submit" value="Siguiente" class="button small success"/>
                </div>
            </div>
        </form>
    </div>
</div>

@include('analissta.Valoracion.modalCancelarValoracion')
@include('analissta.Valoracion.scriptsValoracion')
@endsection