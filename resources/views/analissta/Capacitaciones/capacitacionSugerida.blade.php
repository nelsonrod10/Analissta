@extends('analissta.layouts.appSideBar')
<?php
    use App\CapacitacionesCalendario;
    use App\Presupuesto;
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
        Capacitaciones Sugeridas
    @endsection
    @section('buttons-submenus')
        <a class="button small" data-open="modal-crear-capacitacion-obligatoria">Crear Capacitación Obligatoria</a>
        <a class="button small" data-open="modal-crear-capacitacion-sugerida">Crear Capacitación Sugerida</a>
        <a class="button small warning" href="{{route('indicadores-capacitaciones')}}">Indicadores Capacitaciones</a>
        <a class="button small alert" href="{{route('capacitaciones')}}">Calendario Capacitaciones</a>
    @endsection
    <div class="row">
        <div class="columns small-12 text-center">
            <div style="background:#0c4d78; color:white"><h5>{{$capacitacion->nombre}}</h5></div>
        </div>
        @if($capacitacion->estado === '')
            <div class="columns small-12 text-center" >
                <div ><i><b>No existe ninguna programación</b></i></div>
                <br/>
                <div class="columns small-12">
                    <a class="button small" data-open="reveal-programar-capacitacion">Programar</a>
                    <a class="button small alert" data-open="reveal-eliminar-capacitacion">Eliminar</a>
                </div>
                <div class="small reveal" data-reveal="" id="reveal-programar-capacitacion">
                    <div class="row columns text-center">
                        <i class="fi-alert" style="font-size:32px; color:red"></i>
                    </div>
                    <div class="row columns text-center">
                        <div><b>Al finalizar la programación de la  Capacitacion, está no se podrá eliminar del Plan Anual</b></div>
                        <div><b>¿Desea continuar con la programación?</b></div>
                    </div>
                    <br/>
                    <div class="row columns text-center">
                        <a class="button small success-2" href="{{route('programar-capacitacion',['id'=>$capacitacion->id,'tipo'=>'sugerida'])}}">Continuar</a>
                        <a class="button small alert" data-close="">Cancelar</a>
                    </div>
                </div>
                
                <div class="small reveal" data-reveal="" id="reveal-eliminar-capacitacion">
                    <div class="row columns text-center">
                        <i class="fi-alert" style="font-size:32px; color:red"></i>
                    </div>
                    <div class="row columns text-center">
                        <div style="font-size:14px"><b>¿Confirma que desea eliminar la capacitacion {{$capacitacion->nombre}}?</b></div>
                    </div>
                    <br/>
                    <div class="row columns text-center">
                        <a class="button small success-2" href="{{route('eliminar-capacitacion-sugerida',['id'=>$capacitacion->id])}}" >Confirmar</a>
                        <a class="button small alert" data-close="">Cancelar</a>
                    </div>
                </div>
            </div>
        @else
            <!--Quiere decir que la capacitacion esta programada-->
        <div class="row">
            <div class='columns small-12 text-center'>
                <br/>
                <b>Porcentaje Total de Ejecución</b>
                @if($capacitacion->ejecucionTotal < 20)
                    @php $colorBarra = 'warning' @endphp
                @elseif($capacitacion->ejecucionTotal > 80)
                    @php $colorBarra = 'success' @endphp
                @else
                    @php $colorBarra = '' @endphp
                @endif
                <div role="progressbar" tabindex="0" class="progress <?php echo $colorBarra ?>">
                  <span class="progress-meter" style='width:<?php echo $capacitacion->ejecucionTotal?>%'>
                    <p class="progress-meter-text">{{$capacitacion->ejecucionTotal}} %</p>
                  </span>
                </div>
            </div>
        </div>    
        <div class="row">
            <div class="columns small-12" style="border-right: 3px solid lightgrey">
                <div class="row columns text-center">
                    <h5  style="text-decoration: underline"><b>Datos Generales</b></h5>
                </div>
                <div class="row">
                    <div class="columns small-12"><b>Cargo Responsable: </b>{{$capacitacion->cargo}}</div>
                    <div class="columns small-12"></div>
                </div>
                <div class="row">
                    <div class="columns small-12"><b>Evidencias</b></div>
                    <div class="columns small-12">
                        <textarea style="min-height: 80px" disabled="true">{{$capacitacion->evidencias}}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12"><b>Observaciones</b></div>
                    <div class="columns small-12">
                        <textarea style="min-height: 80px" disabled="true">{{$capacitacion->observaciones}}</textarea>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="columns small-12 medium-12">
                <div class="row columns text-center ">
                    <h5 style="text-decoration: underline"><b>Programación</b></h5>
                </div>
                <div class="row">
                    <div class="columns small-5"><b>Frecuencia de ejecución</b></div>
                    <div class="columns small-2 end">{{ucfirst($capacitacion->frecuencia)}}</div>
                </div>
                <div class="row">
                    <div class="columns small-5"><b>% Total de ejecución</b></div>
                    <div class="columns small-3 end">{{$capacitacion->ejecucionTotal}} %</div>
                </div>
                <br/>
                <!--esto es lo que debe salir si la valoracion es una matriz general-->
                <div class="row">
                    <div class="columns small-12">
                        @foreach($empresa->centrosTrabajo as $centro)
                            @php
                                $calendarioCentro = CapacitacionesCalendario::where('sistema_id',$sistema->id)  
                                    ->where('centroTrabajo_id',$centro->id)
                                    ->where('capacitacion_id',$capacitacion->id)
                                    ->where('tipo','sugerida')
                                    ->get();
                            @endphp
                            @if(count($calendarioCentro) > 0)
                                <div class="row">
                                    <div class="columns small-12" style="font-size:16px">
                                        <div class="columns small-12 text-center" style="background-color:#0c4d78; color:white; font-size: 18px">
                                            <b>{{$centro->nombre}}</b>
                                        </div>
                                        <div class="columns small-12 text-center"></div>
                                    </div>
                                    <div class="columns small-12">
                                        <div class="columns small-12 text-center" style="background-color:#666666; color:white"><b>Jornadas de Capacitación</b></div>
                                        <div class="columns small-12" style="font-size:16px">
                                            <br/>
                                            <b>Responsable: </b>{{$calendarioCentro[0]->responsable}}
                                        </div>
                                        <div class="columns small-12" style="font-size:16px">
                                            <b>Población Objetivo: </b>{{$calendarioCentro[0]->poblacion_objetivo}} Personas
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="row text-center">
                                    <div class="columns small-12 medium-2"><b>Mes</b></div>
                                    <div class="columns small-12 medium-2"><b>Semana</b></div>
                                    <div class="columns small-12 medium-2"><b>Invitados</b></div>
                                    <div class="columns small-12 medium-2 end"><b>Duración (horas)</b></div>
                                </div>
                                @foreach($calendarioCentro as $calendario)
                                    <div class="row">
                                        <div class="columns small-12 medium-2"><a href="{{route('calendario-capacitaciones-mes',['mes'=>$calendario->mes])}}"><b>{{$calendario->mes}}</b></a></div>
                                        <div class="columns small-12 medium-2  text-center">
                                            <a href="{{route('calendario-capacitaciones-semana',['mes'=>$calendario->mes,'semana'=>$calendario->semana])}}" style="text-decoration:underline" title="Ver toda la programacion de esta fecha">
                                                Semana {{$calendario->semana}} 
                                                @if($calendario->ejecutada == "Si")
                                                <i class="fi-check" style="color:#007a00"></i>
                                                @else
                                                <i class="fi-x" style="color:#c12e2a"></i>
                                                @endif
                                            </a>
                                        </div>
                                        <div class="columns small-12 medium-2 text-center">{{$calendario->invitados}}</div>
                                        <div class="columns small-12 medium-2 text-center end">{{$calendario->duracion}}</div>
                                    </div>
                                @endforeach
                                <hr/>
                            @endif
                        @endforeach
                    </div>
                </div>
                <!--esto es lo que debe salir si la valoracion es una matriz por centro-->
            </div>
            <div id="div-presupuesto" class="columns small-12" >
                @include('analissta.Capacitaciones.presupuestoCapacitaciones',['tabla_origen'=>'capacitaciones_obligatorias_sugeridas','tipo'=>'sugerida','origen'=>'capacitacion-sugerida'])
            </div>
            <div class="columns small-12 text-center">
                <br/><br/>
                <div class="reveal small" data-reveal="" id="Reveal-reprogramar2">
                    <div class="row columns text-center">
                        <i class="fi-alert" style="font-size:32px; color:red"></i>
                    </div>
                    <div class="row columns text-center">
                        <div><b>Al realizar una "Reprogramación" perderá toda la información de programación y ejecución actual</b></div>
                        <div><b>¿Desea Continuar?</b></div>
                    </div>
                    <br/>
                    <div class="row columns text-center">
                        <a class="button small success-2" href="{{route('programar-capacitacion',['id'=>$capacitacion->id,'tipo'=>'sugerida'])}}">Continuar</a>
                        <a class="button small alert" data-close="">Cancelar</a>
                    </div>
                    <button class="close-button" data-close="" aria-label="Close modal" type="button">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <a data-idDiv="0" class="button small" data-open="Reveal-reprogramar2">Reprogramar</a>
                <a data-idDiv="0" class="button small success-2" href="{{route('ejecucion-capacitacion',['id'=>$capacitacion->id,'tipo'=>'sugerida'])}}">Ejecucion</a>
            </div>
        </div>
        @endif
    </div>
    <br/>
    <script>
        $(document).ready(function(){
           $("#btn-agregar-presupuesto").on("click",function(){
              $("#frm-presupuesto").removeClass("hide");
              $("#items-presupuesto").addClass("hide");
           }); 
           
           $("#btn-cancelar-presupuesto").on("click",function(){
              $("#frm-presupuesto").addClass("hide");
              $("#items-presupuesto").removeClass("hide");
           }); 
        });
    </script>
    @include('analissta.Capacitaciones.modalCrearCapacitacionObligatoria')
    @include('analissta.Capacitaciones.modalCrearCapacitacionSugerida')
@endsection

