<?php
    use App\Http\Controllers\helpers;

    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    (string)$fechaActual = $objFechaActual->format("Y-m-d");
    
    $fechaSugerida = $evaluacion->anio_sugerido."-".$evaluacion->mes_sugerido."-".$evaluacion->dia_sugerido;
    $fechaFinAnio = helpers::getCurrentYear()."-12-31";
    $fechaInicioAnio = helpers::getCurrentYear()."-01-01";
    
?>
<div id="modal-realizada-medica-ocupacional-{{$evaluacion->id}}" class="reveal" data-reveal>
    <div class="row columns text-center">
        <br/>
        <div class="row columns text-center">
            <h5>Evaluación Médica Ocupacional 
                {{ucwords($evaluacion->empleado->nombre)}} {{ucwords($evaluacion->empleado->apellidos)}}
            </h5>
        </div>
        <br/>
        <form action="{{route('realizar-evaluacion-medica.update',$evaluacion)}}" enctype="multipart/form-data" method="post">
            {{ csrf_field() }}
            {{ method_field('PUT')}}
            <div class="row ">
                <div class="columns small-12">
                    <label for="fecha-programada">¿En qué fecha se realizó esta evaluación?</label>
                </div>
            </div>    
            <div class="row ">    
                <div class="columns small-12 medium-6 medium-centered">
                    <input required="true" type="date" id="fecha-programada" name="fecha" min="{{$fechaInicioAnio}}" max="{{$fechaFinAnio}}"  placeholder="# Fecha de Ingreso" value="{{ $fechaSugerida }}"/>
                </div>    
            </div>
            <div class="row columns text-center">
                <a class="button small alert" aria-label="Close modal" data-close>Cancelar</a>
                <button type="submit" class="button small">Realizada</button>
            </div>    
        </form>
        
    </div>
    <button class="close-button" data-close="" aria-label="Close modal" type="button">
        <span aria-hidden="true">x</span>
    </button> 
</div>