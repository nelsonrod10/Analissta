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
    @include('analissta.Inspecciones.menuInspecciones')
@endsection

@section('content')
    @section('titulo-encabezado')
       Hallazgos Inspecciones
    @endsection
    @section('buttons-submenus')
        <a class="button small" data-open="modal-crear-inspeccion-obligatoria">Crear Inspección Obligatoria</a>
        <a class="button small" data-open="modal-crear-inspeccion-sugerida">Crear Inspección Sugerida</a>
        <a class="button small warning" href="{{route('indicadores-inspecciones')}}">Indicadores Inspecciones</a>
        <a class="button small alert" href="{{route('inspecciones')}}">Calendario Inspecciones</a>
    @endsection
    <div class="row">
        <div class="columns small-12 text-center">
            <div style="background:#0c4d78; color:white"><h5>{{$inspeccion->nombre}}</h5></div>
        </div>
    </div>
    <div class="row">
        <div class="columns small-12 text-center">
            <div ><h5>Ejecución Inspeccion</h5></div>
        </div>
    </div>
    <div class="row">
        <div class='columns small-12 text-center'>
            <b>Porcentaje Total de Ejecución</b>
            @if($inspeccion->ejecucionTotal < 20)
                @php $colorBarra = 'warning' @endphp
            @elseif($inspeccion->ejecucionTotal > 80)
                @php $colorBarra = 'success' @endphp
            @else
                @php $colorBarra = '' @endphp
            @endif
            <div role="progressbar" tabindex="0" class="progress {{$colorBarra}}">
              <span class="progress-meter" style='width:<?php echo $inspeccion->ejecucionTotal?>%'>
                <p class="progress-meter-text">{{$inspeccion->ejecucionTotal}} %</p>
              </span>
            </div>
        </div>
    </div> 
    
    <div class="row">
        <div class="columns small-12">
            <div class="row">
                <div class="columns small-12" style="font-size:16px">
                    <div class="columns small-12 text-center" style="background-color:#0c4d78; color:white; font-size: 18px">
                        <b>Hallazgos Reportados</b>
                    </div>
                    <div class="columns small-12 text-center"></div>
                </div>
            </div>
            <br/>
            <div class="row show-for-medium text-center">
                <div class="columns small-12 medium-12 medium-centered">
                    <div class="columns medium-6"><b style="text-decoration:underline">Descripción</b></div>
                    <div class="columns medium-4"><b style="text-decoration:underline">Fecha Creación</b></div>
                    <div class="columns medium-2"></div>
                </div>
            </div>
            <br/>
            @foreach($inspeccion->hallazgos as $hallazgo)
            <div class="row show-for-medium">
                <div class="columns small-12 medium-12 medium-centered">
                    <div class="columns medium-6">{{substr_replace($hallazgo->descripcion,"...",58)}}</div>
                    <div class="columns medium-4 text-center">{{$hallazgo->fechaHallazgo}}</div>
                    <div class="columns medium-2"><a href="{{route('hallazgo',['id'=>$hallazgo->id])}}" class="button small">Ver Hallazgo</a></div>
                </div>
            </div>
            @endforeach
            <br/>
        </div>
    </div>
    <div class="row columns text-center">
        <a class="button small success-2" href="{{route('ejecucion-inspeccion',['id'=>$inspeccion->id,'tipo'=>$tipoInspeccion])}}">Ejecución</a>
    </div>
    @include('analissta.Inspecciones.modalCrearInspeccionObligatoria')
    @include('analissta.Inspecciones.modalCrearInspeccionSugerida')
@endsection
