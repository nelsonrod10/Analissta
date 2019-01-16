@extends('analissta.layouts.appProgramarMedida')
@section('content')
<?php
    use App\Proceso;
    use App\Peligro;
    
    $proceso = Proceso::find($actividad->proceso_id);
    $peligro = Peligro::find(session('idPeligro'));
    
    $xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    $xpath_nombrePeligro = $xml_GTC45->xpath("//peligros/clasificacion[id=$peligro->clasificacion]");
    $xpath_descPeligro = $xml_GTC45->xpath("//peligros/clasificacion[id=$peligro->clasificacion]/listDescripciones/descripcion[id=$peligro->categoria]");
    $clasificacionPeligro = $xpath_nombrePeligro[0]->nombre;
    $categoriaPeligro = $xpath_descPeligro[0]->nombre;
    
    if(session('idPeligro')){
        $peligroBD = Peligro::find(session('idPeligro'));
        $personasBD = $peligroBD->efectoPersona;
        $propiedadBD = $peligroBD->efectoPropiedad;
        $procesosBD = $peligroBD->efectoProcesos;
    }
    $vrPersonas = (old('personas')!==null)? old('personas'):$personasBD;
    $vrPropiedad = (old('propiedad')!==null)? old('propiedad'):$propiedadBD;
    $vrProcesos = (old('proceso')!==null)? old('proceso'):$procesosBD;
?>

<div class="row">
    <div class="text-center"><h5><b>Proceso | {{ ucwords($proceso->nombre) }}</b></h5></div>
    <div class="text-center"><b>Actividad | {{ ucwords($actividad->nombre) }}</b></div>
</div>
<div class="row">
    <div class="columns small-12 medium-8 small-centered">
        <form id="frm-nuevoPeligroEfectos" name="frm-nuevoPeligroEfectos" method="POST" action="{{ route('efectos-peligro',['idActividad'=>$actividad->id]) }}">
            {{ csrf_field() }}  
            <div class="row columns text-center" >
                <div style="text-decoration: underline"><b>VALORACION PELIGRO </b></div>
                <div>{{ $clasificacionPeligro }} - {{ $categoriaPeligro }}</div>
            </div>
            <div class="row columns text-center" style="text-decoration: underline">
                <b>EFECTOS DEL PELIGRO</b>
            </div>
            @include('analissta.Asesores.crearEmpresa.errors')
            <br/>

            <div class="row">
                <div class="columns small-3 medium-2 ">
                    <b>Personas: </b>
                </div>
                <div class="columns small-9 medium-8 end">
                    <div>
                        <input type="radio" required="true" id="efectoPersonasPeligroCP" name="personas" value="Corto Plazo" <?php echo ($vrPersonas === "Corto Plazo")?"checked":"" ?>/>
                        <label for="efectoPersonasPeligroCP">Accidente de trabajo <small><i>(Corto Plazo)</i></small></label>
                    </div>
                    <div>
                        <input type="radio" required="true" id="efectoPersonasPeligroLP" name="personas" value="Largo Plazo" <?php echo ($vrPersonas === "Largo Plazo")?"checked":"" ?>/>
                        <label for="efectoPersonasPeligroLP">Enfermedad Laboral <small><i>(Largo Plazo )</i></small></label>
                    </div>
                    <div>
                        <input type="radio" required="true" id="efectoPersonasPeligroCLP" name="personas" value="Corto y Largo Plazo" <?php echo ($vrPersonas === "Corto y Largo Plazo")?"checked":"" ?> />
                        <label for="efectoPersonasPeligroCLP">Accidente de trabajo y Enfermedad Laboral <small><i>(Corto y largo plazo)</i></small></label>
                    </div>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="columns small-3 medium-2 ">
                    <b>Propiedad: </b>
                </div>
                <div class="columns small-12 medium-8 end">
                    <textarea id="efecPropPeligro" name="propiedad" placeholder="Efectos a la propiedad" style="width:100%; height:80px; font-size:14px">{{$vrPropiedad}}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="columns small-3 medium-2 ">
                    <b>Procesos: </b>
                </div>
                <div class="columns small-12 medium-8 end">
                    <textarea id="efecProcesoPeligro" name="proceso" placeholder="Efectos en los procesos" style="width:100%; height:80px; font-size:14px">{{$vrProcesos}}</textarea>
                </div>
            </div>
            <div class="row text-center">
                <div class="columns small-12" data-tabs="">
                    <a class="button small" href="{{ route('identificacion-peligro',['idActividad'=>$actividad->id]) }}">Anterior</a>
                    
                    <a class="button small alert" data-open="modal-confirm-cancelValoracion">Cancelar</a>
                    
                    <input type="submit" value="Siguiente" class="button small success"/>
                </div>
            </div>
            
        </form>
    </div>
</div>

@include('analissta.Valoracion.modalCancelarValoracion')
@include('analissta.Valoracion.scriptsValoracion')
@endsection