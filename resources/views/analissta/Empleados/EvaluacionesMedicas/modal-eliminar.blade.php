<?php
    use App\Http\Controllers\helpers;

    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    (string)$fechaActual = $objFechaActual->format("Y-m-d");
    
    $fechaSugerida = $evaluacion->anio_sugerido."-".$evaluacion->mes_sugerido."-".$evaluacion->dia_sugerido;
    $fechaFinAnio = helpers::getCurrentYear()."-12-31";
    
?>
<div id="modal-eliminar-medica-ocupacional-{{$evaluacion->id}}" class="reveal" data-reveal>
    <div class="row columns text-center">
        <br/>
        <div class="row columns text-center">
            <h5>Eliminar Evaluación Médica Ocupacional para 
                {{ucwords($evaluacion->empleado->nombre)}} {{ucwords($evaluacion->empleado->apellidos)}}
            </h5>
        </div>
        <br/>
        <form action="{{route('evaluaciones-medicas.destroy',$evaluacion)}}" enctype="multipart/form-data" method="post">
            {{ csrf_field() }}
            {{ method_field('DELETE')}}
            <div class="row ">
                <div class="columns small-12">
                    <p>¿Está seguro de eliminar esta evaluación médica?</p>
                    <i>Una vez eliminada no podrá reversar el cambio</i>
                </div>
            </div>    
            <div class="row columns text-center">
                <a class="button small alert" aria-label="Close modal" data-close>Cancelar</a>
                <button type="submit" class="button small">Eliminar</button>
            </div>    
        </form>
        
    </div>
    <button class="close-button" data-close="" aria-label="Close modal" type="button">
        <span aria-hidden="true">x</span>
    </button> 
</div>