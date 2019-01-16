@extends('analissta.layouts.appSideBar')
<?php
use App\CapacitacionesCalendario;
use App\CentrosTrabajo;
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
    @include('analissta.Capacitaciones.menuCapacitaciones')
@endsection
<?php
use App\CapacitacionesValoracione;
use App\CapacitacionesObligatoriasSugerida;
use App\CapacitacionesHallazgo;
?>
@section('content')
    @section('titulo-encabezado')
        Calendario Mensual
    @endsection
    @section('buttons-submenus')
        <a class="button small" data-open="modal-crear-capacitacion-obligatoria">Crear Capacitación Obligatoria</a>
        <a class="button small" data-open="modal-crear-capacitacion-sugerida">Crear Capacitación Sugerida</a>
        <a class="button small warning" href="{{route('indicadores-capacitaciones')}}">Indicadores Capacitaciones</a>
        <a class="button small alert" href="{{route('capacitaciones')}}">Calendario Capacitaciones</a>
    @endsection
    <div class="row columns text-center" >
        <h5 style="background:#acafaf;color: white">Calendario de Capacitaciones {{$mes}}</h5>
    </div>
    <div class="row text-center">
        <div class="columns medium-3"><b>Capacitacion</b></div>
        <div class="columns medium-2"><b>Centro Trabajo</b></div>
        <div class="columns medium-2"><b>Responsable</b></div>
        <div class="columns medium-2"><b>Estado Ejecución</b></div>
        <div class="columns medium-1"><b>Semana</b></div>
        <div class="columns medium-2"><b></b></div>
    </div>
    <div class="row text-center"  style="min-height:200px">
    <?php
        foreach($capacitaciones as $capacitacion):
            switch ($capacitacion->tipo) {
                case ($capacitacion->tipo == 'obligatoria' || $capacitacion->tipo == 'sugerida'):
                    $data = CapacitacionesObligatoriasSugerida::where('id',$capacitacion->capacitacion_id)
                        ->where('medida',$capacitacion->tipo)
                        ->get();
                    $dataCapacitacion = $data[0];
                break;
                case 'valoracion':
                    $data = CapacitacionesValoracione::find($capacitacion->capacitacion_id);
                    $dataCapacitacion = $data;
                break;
                case 'hallazgo':
                    $data = CapacitacionesHallazgo::find($capacitacion->capacitacion_id);
                    $dataCapacitacion = $data;
                break;
            }
            
            if($dataCapacitacion):
                $centroTrabajo = CentrosTrabajo::find($capacitacion->centroTrabajo_id);
                $colorEjecutada = ($capacitacion->ejecutada == "Si")?"#46e64b":"#f21e54";
                $textoEjecutada = ($capacitacion->ejecutada == "Si")?"Ejecutada":"Sin Ejecutar";
                $textoRuta = "capacitacion-$capacitacion->tipo";
    ?>
            <div class="row text-center">
                <div class="columns medium-3">{{$dataCapacitacion->nombre}}</div>
                <div class="columns medium-2">{{ $centroTrabajo->nombre}}</div>
                <div class="columns medium-2">{{ $capacitacion->responsable}}</div>
                <div class="columns medium-2" style="background:{{$colorEjecutada}};color:white">{{ $textoEjecutada}}</div>
                <div class="columns medium-1">{{$capacitacion->semana}}</div>
                <div class="columns medium-2"><a class="button tiny" href="{{route($textoRuta, ['id'=>$dataCapacitacion->id])}}">Ver Capacitacion</a></div>
            </div>
            
    <?php
            endif;
        endforeach;
    ?>    
    </div>    
    <div class="row text-center"><b><i>**Si no encuentra alguna Capacitación, quiere decir que NO ha sido programada**</i></b></div>
    <div class="row text-center">
        <a href="{{route('capacitaciones')}}" class="button small alert">Volver al Calendario</a>
    </div>
    @include('analissta.Capacitaciones.modalCrearCapacitacionObligatoria')
    @include('analissta.Capacitaciones.modalCrearCapacitacionSugerida')
@endsection

