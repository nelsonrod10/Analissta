@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\CapacitacionesHallazgo;
    use App\Hallazgos\Hallazgo;
    use App\Hallazgos\HallazgosCierre;
    if(isset($idHallazgo)){
        $capacitacionesBD = CapacitacionesHallazgo::where('sistema_id',  $sistema->id)
            ->where('hallazgo_id',$idHallazgo)
            ->where('medida','hallazgos')
            ->get();
        $capacitacionesCierreBD = CapacitacionesHallazgo::where('sistema_id',  $sistema->id)
            ->where('hallazgo_id',$idHallazgo)
            ->where('medida','hallazgos')
            ->where('reapertura_id',$idCierre)    
            ->get();
        
        $hallazgoBD = Hallazgo::find($idHallazgo);
        $cierresHallazgo = HallazgosCierre::find($idCierre);
    }
?>
@section('content')
    @section('titulo-encabezado')
        Reapertura de Hallazgo
    @endsection
    <div class="row text-center">
        <div class="columns small-12 small-centered label secondary">
            <h6><b>CAPACITACIONES REAPERTURA</b></h6>
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
                                
                            </div>
                            @endforeach
                        @else
                            <div class="row columns text-center">
                                <h6><i style="color:#cc0000">Ha este hallazgo no se le ha creado ninguna capacitación</i></h6>
                            </div>
                        @endif
                        <br/>
                        <div class="row">
                        <div class="columns small-12 label text-center"><b>Listado Capacitaciones Reapertura Actual</b></div>
                        <hr/>
                    </div>
                    @if(count($capacitacionesCierreBD) > 0)
                        
                        @foreach($capacitacionesCierreBD as $capacitacionCierre)
                        <div class="row text-center">
                            <div class="columns small-4">{{$capacitacionCierre->nombre}}</div>
                            <div class="columns small-4 end"><a class="button tiny alert" href="{{route('eliminar-capacitacion-cierre-hallazgo',['idHallazgo'=>$idHallazgo, 'idCierre'=>$idCierre,'idCapacitacion'=>$capacitacionCierre->id])}}">Eliminar</a></div>
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
                        <form name="frm-crearActividad-hallazgo" method="POST" action="{{route('crear-capacitaciones-cierre-hallazgo',["id"=>$idHallazgo,"idCierre"=>$idCierre])}}">
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
                    @if($cierresHallazgo->optimizar !== "Capacitaciones")
                        <a class="button small" href="{{ route('actividades-cierre-hallazgo',["id"=>$idHallazgo,"idCierre"=>$idCierre]) }}">Anterior</a>
                    @endif
                    <a class="button small alert" data-open="reveal-cancelar-crear">Volver</a>
                    @if(count($capacitacionesCierreBD) > 0)
                    <a class="button small success" href="{{route('finalizar-cierre-hallazgo',["id"=>$idHallazgo])}}">Finalizar</a>
                    @endif
                </div>
            </div>
        </div>    
    </div>
    
    @include('analissta.Hallazgos.cierreHallazgo.modalCancelar')
@endsection

