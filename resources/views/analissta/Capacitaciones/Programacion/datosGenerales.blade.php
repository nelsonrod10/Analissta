@extends('analissta.layouts.appProgramarMedida')

@section('content')
    @section('titulo-encabezado')
        Programación Capacitación
    @endsection
    @section('titulo-programacion')
        {{$capacitacion->nombre}}
    @endsection
    <div class="row text-center">
        <div class="columns small-12 medium-8 small-centered label secondary">
            <h6><b>DATOS GENERALES</b></h6>
        </div>
    </div>
    <br/>
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row text-center">
        <div class="columns small-12 medium-8 small-centered">
            <form method="post" name="frmProgramarCapacitacion" action="{{route('datos-generales-capacitacion')}}">
                {{ csrf_field() }}
                <input type="hidden" name="tipo" value="{{$tipoCapacitacion}}"/>
                <input type="hidden" name="idCapacitacion" value="{{$capacitacion->id}}"/>
                <div class="row">
                    <div class="columns small-12 medium-3">Cargo Responsable: </div>
                    <div class="columns small-12 medium-9">
                        <input type="text" class="input-required-paso-1" name="cargo" required="true" placeholder="Cargo que realiza la capacitacion ejm: Supervisor" value="{{$capacitacion->cargo}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-3">Evidencias: </div>
                    <div class="columns small-12 medium-9">
                        <textarea class="input-required-paso-1" name="evidencias"  required="true" placeholder="ejm de evidencias: Codigo de Documento, Foto, informe etc" style="min-height:60px;">{{$capacitacion->evidencias}}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-3">Observaciones: </div>
                    <div class="columns small-12 medium-9">
                        <textarea class="input-required-paso-1" name="observaciones" required="true" placeholder="Escriba las observaciones que considere se deben tener en cuenta" style="min-height:60px;">{{$capacitacion->observaciones}}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-3">Contenido Temático: </div>
                    <div class="columns small-12 medium-9">
                        <textarea class="input-required-paso-1" name="temario" required="true" placeholder="Escriba un resumen general de los temas a tratar en la capacitación" style="min-height:60px;">{{$capacitacion->observaciones}}</textarea>
                    </div>
                </div>
                <br/>
                <div class="row columns text-center">
                    <a class="button small alert" data-open="reveal-cancelar-programacion-capacitacion">Cancelar</a>
                    <input type="submit" class="button small success" value="Siguiente"/>
                </div>    
            </form>
        </div>
    </div>
    @include('analissta.Capacitaciones.Programacion.modalCancelar')
@endsection