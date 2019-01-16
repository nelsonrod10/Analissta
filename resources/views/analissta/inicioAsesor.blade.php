@extends('analissta.layouts.app')

@section('content')
<br/>
<div class="row">
    <div class="columns small-12 text-center">
        <a class="button small success-2" href="{{ route('contexto-general')}}">Crear Nuevo Sistema SST</a>
        @can('administrar-comunidad')
        <a class="button small alert" href="{{ route('comunidad-analissta.index')}}">Comunidad Profesionales SST</a>
        @endcan
    </div>
    <div class="text-center">
        <span id="span-infoFrmIngresoUsu" style="color:#ff4d4d; font-weight: bold; font-size: 12px"></span>
    </div>
</div>
<div class="row columns text-center">
    <h4>Empresas Asesoradas</h4>
</div>


<div class="columns small-12 medium-6 small-centered text-center">
    @if ($errors->any())
    <div class="callout alert"  style="color:#ff4d4d; font-weight: bold;">
        <h5>Se han presentado los siguientes errores</h5>
        @foreach ($errors->all() as $error)
            <div>* {{ $error }}</div>
        @endforeach
    </div>    
    @endif
</div>
<div class="row">
    <ol>
        @foreach($empresasCliente as $cliente)
        <fieldset class="fieldset">
            <div class="row columns text-center" style="background:#0c4d78;//#778899; color: white"><b>{{ $cliente->nombre }}</b></div>
            <br/>
            <div class="row" style="font-size: 14px">
                <div class="columns small-12 medium-6">
                    <div class="columns small-12 medium-4"><b>NIT:</b></div>
                    <div class="columns small-12 medium-8">{{ $cliente->nit }}</div>
                    <div class="columns small-12 medium-4"><b>Teléfono</b></div>
                    <div class="columns small-12 medium-8">{{ $cliente->telefono }}</div>
                    <div class="columns small-12 medium-4"><b>Dirección</b></div>
                    <div class="columns small-12 medium-8">{{ $cliente->direccion }}</div>
                </div>

                <div class="columns small-12 medium-6">
                    <div class="columns small-12 medium-5"><b>Total Empleados</b></div>
                    <div class="columns small-12 medium-7">{{ $cliente->totalEmpleados }}</div>
                    <div class="columns small-12 medium-5"><b>Jornada Laboral</b></div>
                    <div class="columns small-12 medium-7">{{ $cliente->jornadaTrabajo }}</div>
                    <div class="columns small-12 medium-5"><b>Ciudad</b></div>
                    <div class="columns small-12 medium-7">{{ $cliente->ciudad }}</div>
                </div>
            </div>
            <hr/>
            <div class="row" style="font-size: 14px">
                <div class="columns small-12 medium-8">
                    <div><b>Fecha Creación del Sistema: </b>{{ $cliente->created_at }}</div>
                    <div><b>Tipo de Valoración: </b>{{ $cliente->tipoValoracion }}</div>
                </div>
                <div class="columns small-8 medium-4">
                    <a href="{{ route('ver-empresa-cliente',['id'=>$cliente->id]) }}" class="button small expanded" style="cursor: pointer">VER EMPRESA</a>
                </div>
            </div>
        </fieldset>
        @endforeach
    </ol>
    
</div>

@endsection