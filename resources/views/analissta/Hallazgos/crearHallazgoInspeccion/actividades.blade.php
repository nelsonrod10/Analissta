@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\ActividadesHallazgo;
    use App\Hallazgos\Hallazgo;
    
    if(isset($idHallazgo)){
        $actividadesBD = ActividadesHallazgo::where('sistema_id',  $sistema->id)
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
            <h6><b>ACTIVIDADES</b></h6>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="columns small-12 medium-8 small-centered">
            <div class="row">
                <div class="columns small-12 medium-10 small-centered">
                    <div class="row">
                        <div class="columns small-12 label secondary text-center">Listado Actividades</div>
                        <hr/>
                    </div>
                    
                    @if(count($actividadesBD) > 0)
                        @foreach($actividadesBD as $actividad)
                        <div class="row text-center">
                            <div class="columns small-4">{{$actividad->nombre}}</div>
                            <div class="columns small-4 end"><a class="button tiny alert" href="{{route('eliminar-actividad-hallazgo-inspeccion',['idInspeccion'=>$inspeccion->id,'tipoInspeccion'=>$inspeccion->medida,'idHallazgo'=>$idHallazgo, 'idActividad'=>$actividad->id])}}">Eliminar</a></div>
                        </div>
                        @endforeach
                    @else
                            <div class="row columns text-center">
                                <h6><i style="color:#cc0000">No se ha creado ninguna actividad</i></h6>
                            </div>    
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="columns small-12 medium-8 small-centered">
                    <fieldset class="fieldset">
                        <form name="frm-crearActividad-hallazgo" method="POST" action="{{route('crear-actividades-hallazgo-inspeccion',['idInspeccion'=>$inspeccion->id,'tipoInspeccion'=>$inspeccion->medida,"idHallazgo"=>$idHallazgo])}}">
                            {{ csrf_field() }}
                            <div class="columns small-12">
                                <div class="row">
                                    <div class="columns small-12 text-center"><b>AGREGAR ACTIVIDAD</b></div>
                                    <br/>
                                    <div class="columns small-12 text-center"><b>Nombre Actividad</b></div>
                                    <div class="columns small-12">
                                        <input type="text" name="nombre" required="true" placeholder="Nombre de la Actividad"/>
                                    </div>
                                    <div class="columns small-12 text-center">
                                        <input type="submit" class="button success tiny" value="Agregar Actividad"/>
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
                    <a class="button small" href="{{ route('acciones-hallazgo-inspeccion',["idInspeccion"=>$inspeccion->id,"tipoInspeccion"=>$inspeccion->medida,"idHallazgo"=>$idHallazgo]) }}">Anterior</a>
                    <a class="button small alert" data-open="reveal-cancelar-crear">Cancelar</a>
                    @if(count($actividadesBD) > 0)
                        @if($hallazgoBD->planAccion == "Actividades")
                            <a class="button small success" href="{{route('hallazgo',["idHallazgo"=>$idHallazgo])}}">Finalizar</a>
                        @else
                            <a class="button small success" href="{{route('capacitaciones-hallazgo-inspeccion',["idInspeccion"=>$inspeccion->id,"tipoInspeccion"=>$inspeccion->medida,"idHallazgo"=>$idHallazgo])}}">Siguiente</a>
                        @endif
                    @endif    
                </div>
            </div>
        </div>    
    </div>
    
    @include('analissta.Hallazgos.crearHallazgoInspeccion.modalCancelar')
@endsection

