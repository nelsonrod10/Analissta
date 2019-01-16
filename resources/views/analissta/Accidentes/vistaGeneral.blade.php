@extends('analissta.layouts.appSideBar')
<?php
    use App\Http\Controllers\helpers;
    use App\Accidentes\Accidente;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
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

@include('analissta.Accidentes.menuAccidentes')


@endsection
@section('content')
    @section('titulo-encabezado')
        Accidentes 
    @endsection
    
    @section('buttons-submenus')
        <a class="button small" href="{{route('crear-accidente')}}">Crear Accidente</a>
        <a class="button small warning" href="{{route('accidentes')}}">Listado Accidentes</a>
    @endsection
    
@endsection

