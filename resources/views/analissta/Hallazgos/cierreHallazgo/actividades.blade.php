@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\ActividadesHallazgo;
    use App\Hallazgos\Hallazgo;
    use App\Hallazgos\HallazgosCierre;
    
    if(isset($idHallazgo)){
        $actividadesBD = ActividadesHallazgo::where('sistema_id',  $sistema->id)
            ->where('hallazgo_id',$idHallazgo)
            ->where('medida','hallazgos')
            ->get();
        
        $actividadesCierreBD = ActividadesHallazgo::where('sistema_id',  $sistema->id)
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
            <h6><b>ACTIVIDADES REAPERTURA HALLAZGO</b></h6>
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
                        </div>
                        @endforeach
                    @else
                            <div class="row columns text-center">
                                <h6><i style="color:#cc0000">Ha este hallazgo no se le ha creado ninguna actividad</i></h6>
                            </div>    
                    @endif
                    <br/>
                    <div class="row">
                        <div class="columns small-12 label text-center"><b>Listado Actividades Reapertura Actual</b></div>
                        <hr/>
                    </div>
                    @if(count($actividadesCierreBD) > 0)
                        @foreach($actividadesCierreBD as $actividadCierre)
                        <div class="row text-center">
                            <div class="columns small-4">{{$actividadCierre->nombre}}</div>
                            <div class="columns small-4 end"><a class="button tiny alert" href="{{route('eliminar-actividad-cierre-hallazgo',['idHallazgo'=>$idHallazgo, 'idCierre'=>$idCierre,'idActividad'=>$actividadCierre->id])}}">Eliminar</a></div>
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
                        <form name="frm-crearActividad-hallazgo" method="POST" action="{{route('crear-actividades-cierre-hallazgo',["id"=>$idHallazgo,"idCierre"=>$idCierre])}}">
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
                    <a class="button small alert" data-open="reveal-cancelar-crear">Volver</a>
                    @if(count($actividadesCierreBD) > 0)
                        @if($cierresHallazgo->optimizar == "Actividades")
                            <a class="button small success" href="{{route('finalizar-cierre-hallazgo',["id"=>$idHallazgo])}}">Finalizar</a>
                        @else
                            <a class="button small success" href="{{route('capacitaciones-cierre-hallazgo',["id"=>$idHallazgo,"idCierre"=>$idCierre])}}">Siguiente</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>    
    </div>
    @include('analissta.Hallazgos.cierreHallazgo.modalCancelar')
@endsection

