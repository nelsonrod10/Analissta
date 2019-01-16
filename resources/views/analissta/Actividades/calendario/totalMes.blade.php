@extends('analissta.layouts.appSideBar')
<?php
use App\ActividadesCalendario;
use App\ActividadesHallazgo;
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
    @include('analissta.Actividades.menuActividades')
@endsection
<?php
use App\ActividadesValoracione;
use App\ActividadesObligatoriasSugerida;
?>
@section('content')
    @section('titulo-encabezado')
        Calendario Mensual
    @endsection
    @section('buttons-submenus')
        <a class="button small" data-open="modal-crear-actividad-obligatoria">Crear Actividad Obligatoria</a>
        <a class="button small" data-open="modal-crear-actividad-sugerida">Crear Actividad Sugerida</a>
        <a class="button small warning" href="{{route('indicadores-actividades')}}">Indicadores Actividades</a>
        <a class="button small alert" href="{{route('actividades')}}">Calendario Actividades</a>
    @endsection
    <div class="row columns text-center" >
        <h5 style="background:#acafaf;color: white">Calendario de Actividades {{$mes}}</h5>
    </div>
    <div class="row text-center">
        <div class="columns medium-3"><b>Actividad</b></div>
        <div class="columns medium-2"><b>Centro Trabajo</b></div>
        <div class="columns medium-2"><b>Responsable</b></div>
        <div class="columns medium-2"><b>Estado Ejecución</b></div>
        <div class="columns medium-1"><b>Semana</b></div>
        <div class="columns medium-2"><b></b></div>
    </div>
    <div class="row text-center"  style="min-height:200px">
    <?php
        foreach($actividades as $actividad):
            switch ($actividad->tipo) {
                case ($actividad->tipo == 'obligatoria' || $actividad->tipo == 'sugerida'):
                    $data = ActividadesObligatoriasSugerida::where('id',$actividad->actividad_id)
                        ->where('medida',$actividad->tipo)
                        ->get();
                    $dataActividad = $data[0];
                break;
                case 'valoracion':
                    $data = ActividadesValoracione::find($actividad->actividad_id);
                    $dataActividad = $data;
                break;
                case 'hallazgo':
                    $data = ActividadesHallazgo::find($actividad->actividad_id);
                    $dataActividad = $data;
                break;
            }
            
            if($dataActividad):
                $centroTrabajo = CentrosTrabajo::find($actividad->centroTrabajo_id);
                $colorEjecutada = ($actividad->ejecutada == "Si")?"#46e64b":"#f21e54";
                $textoEjecutada = ($actividad->ejecutada == "Si")?"Ejecutada":"Sin Ejecutar";
                $textoRuta = "actividad-$actividad->tipo";
    ?>
            <div class="row text-center">
                <div class="columns medium-3">{{$dataActividad->nombre}}</div>
                <div class="columns medium-2">{{ $centroTrabajo->nombre}}</div>
                <div class="columns medium-2">{{ $actividad->responsable}}</div>
                <div class="columns medium-2" style="background:{{$colorEjecutada}};color:white">{{ $textoEjecutada}}</div>
                <div class="columns medium-1">{{$actividad->semana}}</div>
                <div class="columns medium-2"><a class="button tiny" href="{{route($textoRuta, ['id'=>$dataActividad->id])}}">Ver Actividad</a></div>
            </div>
            
    <?php
            endif;
        endforeach;
    ?>    
    </div>    
    <div class="row text-center"><b><i>**Si no encuentra alguna Actividad, quiere decir que NO ha sido programada**</i></b></div>
    <div class="row text-center">
        <a href="{{route('actividades')}}" class="button small alert">Volver al Calendario</a>
    </div>
@include('analissta.Actividades.modalCrearActividadObligatoria')
@include('analissta.Actividades.modalCrearActividadSugerida')
@endsection

