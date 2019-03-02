@extends('analissta.layouts.app')
<?php
    use App\Http\Controllers\helpers;
    $anioActual = helpers::getCurrentYear();
    $empleadosSinFechaIngreso = $empresa->empleadosSinFechaDeIngreso;
?>
@section('content')
<div class="row">
    <div class="columns small-12 callout">
        <div class="row columns">
            @include('analissta.layouts.encabezadoEmpresaCliente')
            <div class="row columns text-center" style="background:#0c4d78; color: white; font-size: 20px">
                <div><b>EVALUACIONES MEDICAS OCUPACIONALES</b></div>
            </div>
            <div class="expanded button-group">
                <a class="button" href="{{ route('ver-empresa-cliente',['id'=>$empresa->id])}}">Datos Empresa</a>
                <a class="button" href="{{route('mostrar-empleados')}}">Base Datos Empleados</a>
                @if($empresa->tipoValoracion === 'Matriz General')
                <a class="button" href="{{ route('procesos-actividades',['sistema'=>$empresa->sistemaGestion[0]])}}">Procesos y Actividades</a>
                @elseif($empresa->tipoValoracion === 'Matriz por Centro')
                <a class="button" data-open='modal-sistemas-creados'>Procesos y Actividades</a>
                @endif
            </div>
        </div>
        <div class="row">
            <fieldset class="fieldset">
                @if($empleadosSinFechaIngreso->count() > 0)
                    <div class="row columns text-center">
                        <h5>Empleados sin fecha de ingreso</h5>
                        <i>Indique la fecha de ingreso a la empresa de cada empleado</i>
                    </div>
                    <div class="row text-center">
                        <div class="columns small-12 medium-9 medium-centered">
                            <div class="columns small-2 medium-4"><b>Empleado</b></div>
                            <div class="columns small-2 medium-4"><b>Cargo</b></div>
                            <div class="columns small-2 medium-4"></div>
                        </div>
                        
                    </div>
                    @foreach($empleadosSinFechaIngreso as $empleadoSinFecha)
                        @include('analissta.Empleados.modal-frmAsignarFechaIngreso',['empleado'=>$empleadoSinFecha])
                        <div class="row">
                            <div class="columns small-12 medium-9 medium-centered">
                                <div class="columns small-2 medium-4">{{$empleadoSinFecha->nombre}} {{$empleadoSinFecha->apellidos}}</div>
                                <div class="columns small-2 medium-4 text-center">{{$empleadoSinFecha->cargo}}</div>
                                <div class="columns small-2 medium-4">
                                    <a class="button small alert" style="font-size:10px; border-radius: 5px" data-open="modal-asignar-fecha-ingreso-{{$empleadoSinFecha->id}}">Asignar Fecha Ingreso</a>
                                </div>
                            </div>
                            
                        </div>
                    @endforeach
                @endif
                
                
                <div class="row columns text-center">
                    <h5>Evaluaciones Médicas para el {{$anioActual}}</h5>
                </div>
                <div class="row text-center">
                    <div class="columns small-12 medium-9 medium-centered">
                        <div class="columns small-2 medium-3"><b>Empleado</b></div>
                        <div class="columns small-2 medium-2"><b>Cargo</b></div>
                        <div class="columns small-2 medium-2"><b>Fecha Sugerida y/o Programada</b></div>
                        <div class="columns small-2 medium-2"><b>Fecha Realizada</b></div>
                        <div class="columns small-2 medium-3"></div>
                    </div>
                </div>
                @foreach($empresa->evaluacionesMedicasAnioActual as $evaluacion)
                    <div class="row">
                        <div class="columns small-12 medium-9 medium-centered">
                            <div class="columns small-2 medium-3">{{$evaluacion->empleado->nombre}} {{$evaluacion->empleado->apellidos}}</div>
                            <div class="columns small-2 medium-2 text-center">{{$evaluacion->empleado->cargo}}</div>
                            <div class="columns small-2 medium-2 text-center">{{$evaluacion->dia_sugerido}}/{{$evaluacion->mes_sugerido}}/{{$evaluacion->anio_sugerido}}</div>
                            <div class="columns small-2 medium-2 text-center">{{$evaluacion->dia_realizado}}/{{$evaluacion->mes_realizado}}/{{$evaluacion->anio_realizado}}</div>
                            <div class="columns small-2 medium-3 text-center">
                                <div class="expanded button-group tiny">
                                    <a class="button">Programar</a>
                                    <a class="button success-2">Realizada</a>
                                    <a class="button alert">Eliminar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </fieldset>
        </div>  
    </div>
</div>    
  
@endsection