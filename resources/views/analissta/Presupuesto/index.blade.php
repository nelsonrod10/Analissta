@extends('analissta.layouts.app2')

<?php
    use App\Http\Controllers\PresupuestoController;
    
    $presupuesto = $sistema->presupuesto;
    $presupuestoActividades = $presupuesto->whereIn('tabla_origen',['actividades_valoraciones','actividades_obligatorias_sugeridas','actividades_hallazgos'])->all();
    $presupuestoCapacitaciones = $presupuesto->whereIn('tabla_origen',['capacitaciones_valoraciones','capacitaciones_obligatorias_sugeridas','capacitaciones_hallazgos'])->all();
    $presupuestoInspecciones = $presupuesto->whereIn('tabla_origen',['inspecciones_valoraciones','inspecciones_obligatorias_sugeridas'])->all();
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

@section('sistem-menu')

@include('analissta.layouts.appTopMenu')

@endsection
@section('content')
<div class="row">
    
    @include('analissta.layouts.encabezadoEmpresaCliente')
    <div class="row columns text-center">
        <h5><b>Presupuesto</b></h5>
    </div>
    <br/>
    
    @if($presupuesto->count() > 0)
        <div class="row text-center">
            <div class="columns small-12">
                <h5 style="background:#666666; color:white">Actividades</h5>
            </div>
        </div>    
        
        @if(count($presupuestoActividades) > 0)
        <div class="row">
            <div class="columns small-2 text-center"><b>Origen</b></div>
            <div class="columns small-2 text-center"><b>Descripción</b></div>
            <div class="columns small-3 text-center"><b>Observaciones</b></div>
            <div class="columns small-2"><b>Valor Presupuestado</b></div>
            <div class="columns small-1"><b>Valor Real</b></div>
            <div class="columns small-2"><b></b></div>
        </div>
            @foreach($presupuestoActividades as $actividades)
                <hr/>
                <div class="row">
                    <div class="columns small-2"><a href="#" title="Click para ver">{{PresupuestoController::getNombreOrigen($actividades->id)}}</a></div>
                    <div class="columns small-2">{{$actividades->item}}</div>
                    <div class="columns small-3 text-justify">{{$actividades->observaciones}}</div>
                    <div class="columns small-2">$ {{$actividades->valor}}</div>
                    <div class="columns small-1">$ <?php echo $actividades->valor_real !== null? $actividades->valor_real : 0 ?></div>
                    <div class="columns small-2">
                        <div class="tiny button-group">
                            <a class="success-2 button btn-detalles-presupuesto" data-medida="actividad" data-id="{{$actividades->id}}">Detalles</a>
                            <a class="button" href="{{route('ver-medida-intervencion',['presupuesto'=>$actividades->id,'tipo'=>'actividad'])}}">Ver Actividad</a>
                        </div>
                    </div>
                </div>
                @include('analissta.Presupuesto.detalles-presupuesto',['medida'=>$actividades,'tipo'=>'actividad'])
                <!--include('analissta.Presupuesto.modal-valor-real',['item'=>$actividades])-->
            @endforeach
            <div class="row" style="border-top: 2px solid lightgrey">
                <br/>
                <div class="columns small-3 small-offset-4 text-center"><h5><b>Total Presupuesto Actividades</b></h5></div>
                <div class="columns small-2"><h6><b>$ {{ $presupuesto->whereIn('tabla_origen',['actividades_valoraciones','actividades_obligatorias_sugeridas','actividades_hallazgos'])->sum('valor')}}</b></h6></div>
                <div class="columns small-1"><h6><b>$ {{ $presupuestoActividades = $presupuesto->whereIn('tabla_origen',['actividades_valoraciones','actividades_obligatorias_sugeridas','actividades_hallazgos'])->sum('valor_real')}}</b></h6></div>
                <div class="columns small-2"><b></b></div>
            </div>
        @else
            @include('analissta.Presupuesto.callout-sin-presupuesto-parte',['parte'=>'Actividades'])
        @endif
        
        <div class="row text-center">
            <div class="row  text-center">
                <div class="columns small-12" style="padding-top:15px;">
                    <h5 style="background:#666666; color:white">Capacitaciones</h5>
                </div>
            </div>
        </div>    
            @if(count($presupuestoCapacitaciones) > 0)
            <div class="row">
                <div class="columns small-2 text-center"><b>Origen</b></div>
                <div class="columns small-2 text-center"><b>Descripción</b></div>
                <div class="columns small-3 text-center"><b>Observaciones</b></div>
                <div class="columns small-2"><b>Valor Presupuestado</b></div>
                <div class="columns small-1"><b>Valor Real</b></div>
                <div class="columns small-2"><b></b></div>
            </div>
            
                @foreach($presupuestoCapacitaciones as $capacitaciones)
                    <hr/>
                    <div class="row  text-center">
                        <div class="columns small-2">{{PresupuestoController::getNombreOrigen($capacitaciones->id)}}</div>
                        <div class="columns small-2">{{$capacitaciones->item}}</div>
                        <div class="columns small-3 text-justify">{{$capacitaciones->observaciones}}</div>
                        <div class="columns small-2">$ {{$capacitaciones->valor}}</div>
                        <div class="columns small-1">$ <?php echo $capacitaciones->valor_real !== null? $capacitaciones->valor_real : 0 ?></div>
                        <div class="columns small-2">
                            <div class="tiny button-group">
                                <a class="success-2 button btn-detalles-presupuesto" data-medida="capacitacion" data-id="{{$capacitaciones->id}}">Detalles</a>
                                <a class="button" href="{{route('ver-medida-intervencion',['presupuesto'=>$capacitaciones->id,'tipo'=>'capacitacion'])}}">Ver Capacitación</a>
                            </div>
                        </div>
                    </div>
                    @include('analissta.Presupuesto.detalles-presupuesto',['medida'=>$capacitaciones,'tipo'=>'capacitacion'])
                    <!--include('analissta.Presupuesto.modal-valor-real',['item'=>$capacitaciones])-->
                @endforeach
                <div class="row" style="border-top: 2px solid lightgrey">
                    <br/>
                    <div class="columns small-3 small-offset-4 text-center"><h5><b>Total Presupuesto Capacitaciones</b></h5></div>
                    <div class="columns small-2"><h6><b>$ {{ $presupuesto->whereIn('tabla_origen',['capacitaciones_valoraciones','capacitaciones_obligatorias_sugeridas','capacitaciones_hallazgos'])->sum('valor')}}</b></h6></div>
                    <div class="columns small-1"><h6><b>$ {{ $presupuesto->whereIn('tabla_origen',['capacitaciones_valoraciones','capacitaciones_obligatorias_sugeridas','capacitaciones_hallazgos'])->sum('valor_real')}}</b></h6></div>
                    <div class="columns small-2"><b></b></div>
                </div>
            @else
                @include('analissta.Presupuesto.callout-sin-presupuesto-parte',['parte'=>'Capacitaciones'])
            @endif
        </div>
        <div class="row text-center">
            <div class="row  text-center">
                <div class="columns small-12" style="padding-top:15px;">
                    <h5 style="background:#666666; color:white">Inspecciones</h5>
                </div>
            </div>
            
            @if(count($presupuestoInspecciones) > 0)
            <div class="row">
                <div class="columns small-2 text-center"><b>Origen</b></div>
                <div class="columns small-2 text-center"><b>Descripción</b></div>
                <div class="columns small-3 text-center"><b>Observaciones</b></div>
                <div class="columns small-2"><b>Valor Presupuestado</b></div>
                <div class="columns small-1"><b>Valor Real</b></div>
                <div class="columns small-2"><b></b></div>
            </div>
            
                @foreach($presupuestoInspecciones as $inspecciones)
                    <hr/>
                    <div class="row  text-center">
                        <div class="columns small-2">{{PresupuestoController::getNombreOrigen($inspecciones->id)}}</div>
                        <div class="columns small-2">{{$inspecciones->item}}</div>
                        <div class="columns small-3 text-justify">{{$inspecciones->observaciones}}</div>
                        <div class="columns small-2">$ {{$inspecciones->valor}}</div>
                        <div class="columns small-1">$ <?php echo $inspecciones->valor_real !== null? $inspecciones->valor_real : 0 ?></div>
                        <div class="columns small-2">
                            <div class="tiny button-group">
                                <a class="success-2 button btn-detalles-presupuesto" data-medida="inspeccion" data-id="{{$inspecciones->id}}">Detalles</a>
                                <a class="button" href="{{route('ver-medida-intervencion',['presupuesto'=>$inspecciones->id,'tipo'=>'inspeccion'])}}">Ver Inspección</a>
                            </div>
                        </div>
                    </div>
                    @include('analissta.Presupuesto.detalles-presupuesto',['medida'=>$inspecciones,'tipo'=>'inspeccion'])
                    <!--include('analissta.Presupuesto.modal-valor-real',['item'=>$inspecciones])-->
                @endforeach
                <div class="row" style="border-top: 2px solid lightgrey">
                    <br/>
                    <div class="columns small-3 small-offset-4 text-center"><h5><b>Total Presupuesto Inspecciones</b></h5></div>
                    <div class="columns small-2"><h6><b>$ {{ $presupuesto->whereIn('tabla_origen',['inspecciones_valoraciones','inspecciones_obligatorias_sugeridas'])->sum('valor')}}</b></h6></div>
                    <div class="columns small-1"><h6><b>$ {{ $presupuesto->whereIn('tabla_origen',['inspecciones_valoraciones','inspecciones_obligatorias_sugeridas'])->sum('valor_real')}}</b></h6></div>
                    <div class="columns small-2"><b></b></div>
                </div>
            @else
                @include('analissta.Presupuesto.callout-sin-presupuesto-parte',['parte'=>'Inspecciones'])
            @endif
        </div>
        <div class="row">
            <div class="columns small-12">
                <h5><b>Total Presupuesto General: $ {{$presupuesto->sum('valor')}}</b></h5>
                <h5><b>Total Presupuesto Real: $ {{$presupuesto->sum('valor_real')}}</b></h5>
            </div>
        </div>
        
    @else
        @include('analissta.Presupuesto.callout-sin-presupuesto-general')
    @endif
</div>    
<script>
    $(document).ready(function(){
        $(".btn-detalles-presupuesto").on("click",function(e){
           var medida = $(this).attr("data-medida"); 
           var id = $(this).attr("data-id"); 
           $(".div-presupuesto").each(function(){
                $(this).addClass("hide");
            });
            $("#div-presupuesto-"+medida+"-"+id).removeClass('hide');
            e.preventDefault();
        });
        
        $(".btn-cerrar-detalles-presupuesto").on("click", function(e){
            var medida = $(this).attr("data-medida");
            var id = $(this).attr("data-id");
            $("#div-presupuesto-"+medida+"-"+id).addClass('hide');
            e.preventDefault();
        });
    });
</script>
@endsection