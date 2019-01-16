@extends('analissta.layouts.appSideBar')
<?php
use App\InspeccionesCalendario;
use App\InspeccionesValoracione;
use App\InspeccionesObligatoriasSugerida;
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
    @include('analissta.Inspecciones.menuInspecciones')
@endsection

@section('content')
    @section('titulo-encabezado')
        Calendario Semanal
    @endsection    
    @section('buttons-submenus')
        <a class="button small" data-open="modal-crear-inspeccion-obligatoria">Crear Inspección Obligatoria</a>
        <a class="button small" data-open="modal-crear-inspeccion-sugerida">Crear Inspección Sugerida</a>
        <a class="button small warning" href="{{route('indicadores-inspecciones')}}">Indicadores Inspecciones</a>
        <a class="button small alert" href="{{route('inspecciones')}}">Calendario Inspecciones</a>
    @endsection
    <div class="row columns text-center" >
        <h5 style="background:#acafaf;color: white">Calendario de Inspecciones {{$mes}} - Semana {{$semana}}</h5>
    </div>
    <div class="row text-center">
        <div class="columns medium-3"><b>Inspeccion</b></div>
        <div class="columns medium-3"><b>Centro Trabajo</b></div>
        <div class="columns medium-2"><b>Responsable</b></div>
        <div class="columns medium-2"><b>Estado Ejecución</b></div>
        <div class="columns medium-2"><b></b></div>
    </div>
    <div class="row text-center"  style="min-height:200px">
    <?php
        foreach($inspecciones as $inspeccion):
            switch ($inspeccion->tipo) {
                case ($inspeccion->tipo == 'obligatoria' || $inspeccion->tipo == 'sugerida'):
                    $data = InspeccionesObligatoriasSugerida::where('id',$inspeccion->inspeccion_id)
                        ->where('medida',$inspeccion->tipo)
                        ->get();
                    $dataInspeccion = $data[0];
                break;
                default:
                    $data= InspeccionesValoracione::find($inspeccion->inspeccion_id);
                    $dataInspeccion = $data;
                break;
            }
            
            if($dataInspeccion):
                $centroTrabajo = CentrosTrabajo::find($inspeccion->centroTrabajo_id);
                $colorEjecutada = ($inspeccion->ejecutada == "Si")?"#46e64b":"#f21e54";
                $textoEjecutada = ($inspeccion->ejecutada == "Si")?"Ejecutada":"Sin Ejecutar";
                $textoRuta = "inspeccion-$inspeccion->tipo";
    ?>
            <div class="row text-center">
                <div class="columns medium-3">{{$dataInspeccion->nombre}}</div>
                <div class="columns medium-3">{{ $centroTrabajo->nombre}}</div>
                <div class="columns medium-2">{{ $inspeccion->responsable}}</div>
                <div class="columns medium-2" style="background:{{$colorEjecutada}};color:white">{{ $textoEjecutada}}</div>
                <div class="columns medium-2"><a class="button tiny" href="{{route($textoRuta, ['id'=>$dataInspeccion->id])}}">Ver Inspeccion</a></div>
            </div>
            
    <?php
            endif;
        endforeach;
    ?>    
    </div>    
    <div class="row text-center"><b><i>**Si no encuentra alguna Inspección, quiere decir que NO ha sido programada**</i></b></div>
    <div class="row text-center">
        <a href="{{route('calendario-inspecciones-mes',['mes'=>$mes])}}" class="button small">Calendario {{$mes}}</a>
        <a href="{{route('inspecciones')}}" class="button small alert">Volver al Calendario</a>
    </div>
    @include('analissta.Inspecciones.modalCrearInspeccionObligatoria')
    @include('analissta.Inspecciones.modalCrearInspeccionSugerida')
@endsection

