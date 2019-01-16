@extends('analissta.layouts.app')

@section('content')
<?php 
    use App\Http\Controllers\helpers;
?>
<br/>

<div class="row">
    <div class="columns small-12 callout">
        
        <div class="columns small-12">
            @include('analissta.layouts.encabezadoEmpresaCliente')
            <div class="row columns text-center" style="background:#0c4d78; color: white; font-size: 20px">
                <div><b>COMUNIDAD ANALISSTA </b></div>
            </div>
            
            <div class="expanded button-group">
                <a class="button" href="{{ route('inicio')}}">Volver a Inicio</a>
                <a class="button warning" href="{{ route('comunidad-profesionales.index')}}">Comunidad Profesionales</a>
                <a class="button success-2" href="{{route('analissta-empresas.index')}}">Comunidad Empresas</a>
            </div>
        </div>
        <div class="columns small-12">
            <h4><b>Total Profesionales Incritos: </b>{{$profesionales->count()}}</h4>
            <h4><b>Total Empresas Incritas: </b>{{$empresas->count()}}</h4>
            <h4><b>Total Invitados: </b>{{$invitados->count()}}</h4>
        </div>
        <div class="columns small-12">
            <h4 class="text-center" style="color:white;background: lightslategray">Profesionales</h4>
            @foreach($profesionales as $profesional)
            <div class="row" style="margin-bottom:10px; border-bottom:1px solid lightgray">
                <div class="columns small-12 medium-3">{{$profesional->nombre}}</div>
                <div class="columns small-12 medium-3">{{$profesional->profesion}}</div>
                <div class="columns small-12 medium-1">{{$profesional->ciudad}}</div>
                <div class="columns small-12 medium-2">{{$profesional->email}}</div>
                <div class="columns small-12 medium-1"><?php echo ($profesional->telefono == null )?"No tiene": $profesional->telefono?></div>
                <div class="columns small-12 medium-2"><!--<a class="button small primary">Ver Detalles</a>--></div>
            </div>
            @endforeach
            <br/>
            <div class="row">
                <div class="columns small-12 text-center">
                    <a class="button warning" href="{{route('analissta-profesionales.index')}}">Resumen General Profesionales</a>
                </div>
            </div>
            
            
        </div>
        
        <div class="columns small-12">
            <h4 class="text-center" style="color:white;background: lightslategray">Empresas</h4>
            @foreach($empresas as $empresa)
            <div class="row" style="margin-bottom:10px; border-bottom:1px solid lightgray">
                <div class="columns small-12 medium-3">{{$empresa->nombre}}</div>
                <div class="columns small-12 medium-3">{{$empresa->identificacion}}</div>
                <div class="columns small-12 medium-1">{{$empresa->ciudad}}</div>
                <div class="columns small-12 medium-2">{{$empresa->email}}</div>
                <div class="columns small-12 medium-1"><?php echo ($empresa->telefono == null )?"No tiene": $empresa->telefono?></div>
                <div class="columns small-12 medium-2"><!--<a class="button small primary">Ver Detalles</a>--></div>
            </div>
            @endforeach
            <br/>
            <div class="row">
                <div class="columns small-12 text-center">
                    <a class="button alert" href="{{route('analissta-empresas.index')}}">Resumen General Empresas</a>
                </div>
            </div>
        </div>
        
        <div class="columns small-12">
            <h4 class="text-center" style="color:white;background: lightslategray">Invitados</h4>
            @foreach($invitados as $invitado)
            <div class="row">
                <div class="columns small-12"><p>{{$invitado->email}}</p></div>
            </div>
            @endforeach
        </div>
    </div>
</div> 
@endsection