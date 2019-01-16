@extends('analissta.layouts.appSideBar')

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

@section('content')
    @section('titulo-encabezado')
       Evidencias Capacitaciones
    @endsection
    @section('buttons-submenus')
        <a class="button small" data-open="modal-crear-capacitacion-obligatoria">Crear Capacitación Obligatoria</a>
        <a class="button small" data-open="modal-crear-capacitacion-sugerida">Crear Capacitación Sugerida</a>
        <a class="button small warning" href="{{route('indicadores-capacitaciones')}}">Indicadores Capacitaciones</a>
        <a class="button small alert" href="{{route('capacitaciones')}}">Calendario Capacitaciones</a>
    @endsection
    <div class="row">
        <div class="columns small-12 text-center">
            <div style="background:#0c4d78; color:white"><h5>{{$capacitacion->nombre}}</h5></div>
        </div>
    </div>
    <div class="row">
        <div class="columns small-12 text-center">
            <div ><h5>Ejecución Capacitacion</h5></div>
        </div>
    </div>
    <div class="row">
        <div class='columns small-12 text-center'>
            <b>Porcentaje Total de Ejecución</b>
            @if($capacitacion->ejecucionTotal < 20)
                @php $colorBarra = 'warning' @endphp
            @elseif($capacitacion->ejecucionTotal > 80)
                @php $colorBarra = 'success' @endphp
            @else
                @php $colorBarra = '' @endphp
            @endif
            <div role="progressbar" tabindex="0" class="progress {{$colorBarra}}">
              <span class="progress-meter" style='width:<?php echo $capacitacion->ejecucionTotal?>%'>
                <p class="progress-meter-text">{{$capacitacion->ejecucionTotal}} %</p>
              </span>
            </div>
        </div>
    </div> 
    
    <div class="row">
        <div class="columns small-12">
            <div class="row">
                <div class="columns small-12" style="font-size:16px">
                    <div class="columns small-12 text-center" style="background-color:#0c4d78; color:white; font-size: 18px">
                        <b>Evidencias</b>
                    </div>
                    <div class="columns small-12 text-center"></div>
                </div>
            </div>
            <br/>
            <div class="row show-for-medium text-center">
                <div class="columns small-12 medium-12 medium-centered">
                    <div class="columns medium-8"><b style="text-decoration:underline">Ubicación</b></div>
                    <div class="columns medium-4"><b style="text-decoration:underline">Fecha Creación</b></div>
                </div>
            </div>
            <br/>
            @foreach($evidencias as $evidencia)
            <div class="row show-for-medium">
                <div class="columns small-12 medium-12 medium-centered">
                    <div class="columns medium-8">{{$evidencia->evidencia}}</div>
                    <div class="columns medium-4 text-center">{{$evidencia->created_at}}</div>
                </div>
            </div>
            @endforeach
            <br/>
        </div>
    </div>
    <div class="row columns text-center">
        <a class="button small success-2" href="{{route('ejecucion-capacitacion',['id'=>$capacitacion->id,'tipo'=>$tipoCapacitacion])}}">Ejecución</a>
    </div>
    @include('analissta.Capacitaciones.modalCrearCapacitacionObligatoria')
    @include('analissta.Capacitaciones.modalCrearCapacitacionSugerida')
@endsection
