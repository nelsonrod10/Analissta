@extends('analissta.layouts.appSideBar')
<?php
use App\Http\Controllers\helpers;
use App\CapacitacionesCalendario;
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

@section('content')
    @section('titulo-encabezado')
        Capacitaciones
    @endsection
    @section('buttons-submenus')
        <a class="button small" data-open="modal-crear-capacitacion-obligatoria">Crear Capacitación Obligatoria</a>
        <a class="button small" data-open="modal-crear-capacitacion-sugerida">Crear Capacitación Sugerida</a>
        <a class="button small warning" href="{{route('indicadores-capacitaciones')}}">Indicadores Capacitaciones</a>
        <a class="button small alert" href="{{route('capacitaciones')}}">Calendario Capacitaciones</a>
    @endsection
    @if(auth()->user()->email == 'gaspar.diego@gmail.com')
        @include('analissta.Capacitaciones.updateDuplicidadCapacitaciones')
    @endif
    <div class="columns small-12 text-center" style="background:grey; color:white"><b>CALENDARIO ANUAL DE CAPACITACIONES</b></div>
    <div class="columns small-6 text-center" style="background:#f29c13; color:white">PROGRAMADAS</div>
    <div class="columns small-6 text-center" style="background:#3adb76; color:white">EJECUTADAS</div>
    
    <div class="columns small-2">
        <div class="row" style="height:40px">
            <div class="columns small-12"></div>
        </div>
        <div class="row" style="height:40px; border-bottom: 1px solid lightgray">
            <div class="columns small-12">Semana 1</div>
        </div>
        <div class="row" style="height:40px; border-bottom: 1px solid lightgray">
            <div class="columns small-12">Semana 2</div>
        </div>
        <div class="row" style="height:40px; border-bottom: 1px solid lightgray">
            <div class="columns small-12">Semana 3</div>
        </div>
        <div class="row" style="height:40px; border-bottom: 1px solid lightgray">
            <div class="columns small-12">Semana 4</div>
        </div>
        <div class="row" style="height:40px; border-bottom: 1px solid lightgray">
            <div class="columns small-12">Total por Mes</div>
        </div>
    </div>
    <div class="columns small-10">
        <div class="row text-center">
            <?php
                for($i=0;$i<=11;$i++):
                    $textMes = helpers::meses_de_numero_a_texto($i);
                    $subText = substr($textMes, 0, 3);                
            ?>
            <div class="columns small-1" style="border-right: 1px solid lightgray;">
                <div style="height:40px;"><a href="{{route('calendario-capacitaciones-mes',['mes'=>$textMes])}}">{{strtoupper($subText)}}</a></div>
                <?php
                    for($i1=1;$i1<=4;$i1++):
                ?>
                    <div class="row" style="height:40px;border-bottom: 1px solid lightgray">
                        <a href="{{route('calendario-capacitaciones-semana',['mes'=>$textMes,'semana'=>$i1])}}">
                            <div class="columns small-6" style="background:#f29c13">
                                <?php
                                    $programadas = CapacitacionesCalendario::where('sistema_id',$sistema->id)
                                            ->where('anio', helpers::getCurrentYear())
                                            ->where('mes',$textMes)
                                            ->where('semana',$i1)
                                            ->get();
                                    echo $programadas->count();
                                ?>
                            </div>
                            <div class="columns small-6" style="background:#3adb76">
                                <?php
                                    $ejecutadas = CapacitacionesCalendario::where('sistema_id',$sistema->id)
                                            ->where('anio', helpers::getCurrentYear())
                                            ->where('mes',$textMes)
                                            ->where('semana',$i1)
                                            ->where('ejecutada','Si')
                                            ->get();
                                    echo count($ejecutadas);
                                ?>
                            </div>    

                        </a>
                    </div>
                <?php
                    endfor;
                ?>
                <div class="row" style="height:40px;border-bottom: 1px solid lightgray">
                    <a href="{{route('calendario-capacitaciones-mes',['mes'=>$textMes])}}">
                        <div class="columns small-6" style="background:#f29c13">
                            <?php
                                $totProg = CapacitacionesCalendario::where('sistema_id',$sistema->id)
                                    ->where('anio', helpers::getCurrentYear())
                                    ->where('mes',$textMes)
                                    ->get();
                                echo count($totProg);
                            ?>
                        </div>
                        <div class="columns small-6" style="background:#3adb76">
                            <?php
                                $totEjec = CapacitacionesCalendario::where('sistema_id',$sistema->id)
                                    ->where('anio', helpers::getCurrentYear())
                                    ->where('mes',$textMes)
                                    ->where('ejecutada','Si')
                                    ->get();
                                echo count($totEjec);
                            ?>
                        </div>    
                    </a>
                </div>
            </div>
            <?php
            endfor;
            ?>
        </div>  
    </div>  
    @include('analissta.Capacitaciones.modalCrearCapacitacionObligatoria')
    @include('analissta.Capacitaciones.modalCrearCapacitacionSugerida')
    @include('analissta.Capacitaciones.calendarioPGRP')
    @include('analissta.Capacitaciones.calendarioPVE')
@endsection

