<?php
    use App\Http\Controllers\helpers;

    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    (string)$fechaActual = $objFechaActual->format("Y-m-d");
?>
<div id="modal-asignar-fecha-ingreso-{{$empleado->id}}" class="reveal" data-reveal>
    <div class="row columns text-center">
        <br/>
        <div class="row columns text-center">
            <h5>Asignar Fecha de ingreso para </h5>
            <h5>{{ucwords($empleado->nombre)}} {{ucwords($empleado->apellidos)}}?</h5>
        </div>
        <br/>
        <form action="{{route('actualizar-fecha-ingreso',$empleado)}}" enctype="multipart/form-data" method="post">
            {{ csrf_field() }}
            <div class="row ">
                <div class="columns small-12">
                    <label for="ingreso">¿En qué fecha ingresó el empleado a la empresa?</label>
                </div>
            </div>    
            <div class="row ">    
                <div class="columns small-12 medium-6 medium-centered">
                    <input required="true" type="date" id="ingreso" name="ingreso" min="{{$empresa->fechaFundacion}}" max="{{$fechaActual}}"  placeholder="# Fecha de Ingreso" value="{{ isset($empleado)?$empleado->fecha_ingreso:"" }}"/>
                </div>    
            </div>
            <div class="row columns text-center">
                <a class="button small alert" aria-label="Close modal" data-close>Cancelar</a>
                <button type="submit" class="button small">Actualizar Fecha</button>
            </div>    
        </form>
        
    </div>
    <button class="close-button" data-close="" aria-label="Close modal" type="button">
        <span aria-hidden="true">x</span>
    </button> 
</div>