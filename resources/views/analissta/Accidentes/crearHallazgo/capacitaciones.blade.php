@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\CapacitacionesHallazgo;
    use App\Hallazgos\Hallazgo;
    
    if(isset($accidente)){
        $idHallazgo = $accidente->hallazgo->hallazgo_id;
        $hallazgoBD = Hallazgo::find($accidente->hallazgo->hallazgo_id);
        $capacitacionesBD = CapacitacionesHallazgo::where('sistema_id',  $sistema->id)
            ->where('hallazgo_id',$idHallazgo)
            ->where('medida','hallazgos')
            ->get();
    }
    
?>
@section('content')
    @section('titulo-encabezado')
        Crear Hallazgo para Accidente 
    @endsection
    <div class="row text-center">
        <div class="columns small-12 medium-8 small-centered">
            <h5>Se esta configurando un hallazgo para el Accidente sufrido por: </h5>
            <h5>{{ucwords(strtolower($accidente->accidentado->nombre))}} {{ucwords(strtolower($accidente->accidentado->apellidos))}}</h5>
        </div>
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
                                <div class="columns small-4 end">
                                    <form method="POST" name="frm-eliminar-capacitacion-hallazgo-accidente" action="{{route('eliminar-capacitacion-hallazgo-accidente',['idAccidente'=>$accidente->id])}}">
                                        {{ csrf_field() }}
                                        <input type="hidden" class="hide" hidden="true" name="idHallazgo"  value="{{$idHallazgo}}"/>
                                        <input type="hidden" class="hide" hidden="true" name="idCapacitacion"  value="{{$capacitacion->id}}"/>
                                        <input type="submit" class="button tiny alert" value="Eliminar"/>
                                    </form>
                                </div>
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
                        <form name="frm-crearActividad-hallazgo-accidente" method="POST" action="{{route('crear-capacitaciones-hallazgo-accidente',["idAccidente"=>$accidente->id])}}">
                            {{ csrf_field() }}
                            <input type="hidden" class="hide" hidden="true" name="idHallazgo"  value="{{$idHallazgo}}"/>
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
                        <a class="button small" href="{{ route('acciones-hallazgo-accidente',["idAccidente"=>$accidente->id]) }}">Anterior</a>
                    @else
                        <a class="button small" href="{{ route('actividades-hallazgo-accidente',["idAccidente"=>$accidente->id]) }}">Anterior</a>
                    @endif
                    
                    <a class="button small alert" data-open="reveal-cancelar-crear-hallazgo-accidente">Cancelar</a>
                    @if(count($capacitacionesBD) > 0)
                    <a class="button small success" href="{{route('hallazgo',["id"=>$idHallazgo])}}">Finalizar</a>
                    @endif
                </div>
            </div>
        </div>    
    </div>
    
    @include('analissta.Accidentes.crearHallazgo.modalCancelar')
@endsection

