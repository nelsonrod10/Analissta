@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\ActividadesHallazgo;
    use App\Hallazgos\Hallazgo;
    
    if(isset($accidente)){
        $idHallazgo = $accidente->hallazgo->hallazgo_id;
        $hallazgoBD = Hallazgo::find($accidente->hallazgo->hallazgo_id);
        $actividadesBD = ActividadesHallazgo::where('sistema_id',  $sistema->id)
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
                            <div class="columns small-4 end">
                                <form method="POST" name="frm-eliminar-actividad-hallazgo-accidente" action="{{route('eliminar-actividad-hallazgo-accidente',['idAccidente'=>$accidente->id])}}">
                                    {{ csrf_field() }}
                                    <input type="hidden" class="hide" hidden="true" name="idHallazgo"  value="{{$idHallazgo}}"/>
                                    <input type="hidden" class="hide" hidden="true" name="idActividad"  value="{{$actividad->id}}"/>
                                    <input type="submit" class="button tiny alert" value="Eliminar"/>
                                </form>
                            </div>
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
                        <form name="frm-crearActividad-hallazgo-accidente" method="POST" action="{{route('crear-actividades-hallazgo-accidente',["idAccidente"=>$accidente->id])}}">
                            {{ csrf_field() }}
                            <input type="hidden" class="hide" hidden="true" name="idHallazgo"  value="{{$idHallazgo}}"/>
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
                    <a class="button small" href="{{ route('acciones-hallazgo-accidente',["idAccidente"=>$accidente->id]) }}">Anterior</a>
                    <a class="button small alert" data-open="reveal-cancelar-crear-hallazgo-accidente">Cancelar</a>
                    @if(count($actividadesBD) > 0)
                        @if($hallazgoBD->planAccion == "Actividades")
                            <a class="button small success" href="{{route('hallazgo',["id"=>$idHallazgo])}}">Finalizar</a>
                        @else
                            <a class="button small success" href="{{route('capacitaciones-hallazgo-accidente',["idAccidente"=>$accidente->id])}}">Siguiente</a>
                        @endif
                    @endif    
                </div>
            </div>
        </div>    
    </div>
    
    @include('analissta.Accidentes.crearHallazgo.modalCancelar')
@endsection

