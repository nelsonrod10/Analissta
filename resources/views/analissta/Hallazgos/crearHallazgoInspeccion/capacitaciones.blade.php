@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\CapacitacionesHallazgo;
    use App\Hallazgos\Hallazgo;
    
    if(isset($idHallazgo)){
        $capacitacionesBD = CapacitacionesHallazgo::where('sistema_id',  $sistema->id)
            ->where('hallazgo_id',$idHallazgo)
            ->where('medida','hallazgos')
            ->get();
        
        $hallazgoBD = Hallazgo::find($idHallazgo);
    }
?>
@section('content')
    @section('titulo-encabezado')
        Crear Nuevo Hallazgo 
    @endsection
    <div class="row columns text-center">
        <h5 style="color:white; background:#66cc00">Hallazgo para Inspección - {{$inspeccion->nombre}}</h5>
    </div>
    <div class="row text-center">
        <div class="columns small-12 small-centered label secondary">
            <h6><b>CAPACITACIONES</b></h6>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="columns small-12 medium-8 small-centered">
            <div class="row">
                <div class="columns small-12 medium-10 small-centered">
                    <div class="columns small-12 label secondary text-center">Listado Capacitaciones</div>
                        <hr/>
                        @if(count($capacitacionesBD) > 0)
                            @foreach($capacitacionesBD as $capacitacion)
                            <div class="row text-center">
                                <div class="columns small-4">{{$capacitacion->nombre}}</div>
                                <div class="columns small-4 end"><a class="button tiny alert" href="{{route('eliminar-capacitacion-hallazgo-inspeccion',["idInspeccion"=>$inspeccion->id,"tipoInspeccion"=>$inspeccion->medida,'idHallazgo'=>$idHallazgo, 'idActividad'=>$capacitacion->id])}}">Eliminar</a></div>
                            </div>
                            @endforeach
                        @else
                            <div class="row columns text-center">
                                <h6><i style="color:#cc0000">No se ha creado ninguna capacitación</i></h6>
                            </div>
                        @endif
                </div>
            </div>
            <div class="row">
                <div class="columns small-12 medium-8 small-centered">
                    <fieldset class="fieldset">
                        <form name="frm-crearActividad-hallazgo" method="POST" action="{{route('crear-capacitaciones-hallazgo-inspeccion',["idInspeccion"=>$inspeccion->id,"tipoInspeccion"=>$inspeccion->medida,"idHallazgo"=>$idHallazgo])}}">
                            {{ csrf_field() }}
                            <div class="columns small-12">
                                <div class="row">
                                    <div class="columns small-12 text-center"><b>AGREGAR CAPACITACION</b></div>
                                    <br/>
                                    <div class="columns small-12 text-center"><b>Nombre Capacitación</b></div>
                                    <div class="columns small-12">
                                        <input type="text" name="nombre" required="true" placeholder="Nombre de la Capacitación"/>
                                    </div>
                                    <div class="columns small-12 text-center">
                                        <input type="submit" class="button success tiny" value="Agregar Capacitación"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </fieldset>
                    
                </div>
            </div>    
            
            <br/>
            <div class="row text-center">
                <div class="columns small-12" data-tabs="">
                    @if($hallazgoBD->planAccion == "Capacitaciones")
                        <a class="button small" href="{{ route('acciones-hallazgo-inspeccion',["idInspeccion"=>$inspeccion->id,"tipoInspeccion"=>$inspeccion->medida,"idHallazgo"=>$idHallazgo]) }}">Anterior</a>
                    @else
                        <a class="button small" href="{{ route('actividades-hallazgo-inspeccion',["idInspeccion"=>$inspeccion->id,"tipoInspeccion"=>$inspeccion->medida,"idHallazgo"=>$idHallazgo]) }}">Anterior</a>
                    @endif
                    
                    <a class="button small alert" data-open="reveal-cancelar-crear">Cancelar</a>
                    @if(count($capacitacionesBD) > 0)
                    <a class="button small success" href="{{route('hallazgo',["id"=>$idHallazgo])}}">Finalizar</a>
                    @endif
                </div>
            </div>
        </div>    
    </div>
    
    @include('analissta.Hallazgos.crearHallazgoInspeccion.modalCancelar')
@endsection

