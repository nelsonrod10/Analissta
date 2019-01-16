@extends('analissta.layouts.appSideBar')
<?php
    use App\InspeccionesCalendario;
    
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
    @include('analissta.Inspecciones.menuInspecciones')
@endsection

@section('content')
    @section('titulo-encabezado')
        Inspecciones Sugeridas
    @endsection
    @section('buttons-submenus')
        <a class="button small" data-open="modal-crear-inspeccion-obligatoria">Crear Inspección Obligatoria</a>
        <a class="button small" data-open="modal-crear-inspeccion-sugerida">Crear Inspección Sugerida</a>
        <a class="button small warning" href="{{route('indicadores-inspecciones')}}">Indicadores Inspecciones</a>
        <a class="button small alert" href="{{route('inspecciones')}}">Calendario Inspecciones</a>
    @endsection
    <div class="row">
        <div class="columns small-12 text-center">
            <div style="background:#0c4d78; color:white"><h5>{{$inspeccion->nombre}}</h5></div>
        </div>
        @if($inspeccion->estado === '')
            <div class="columns small-12 text-center" >
                <div ><i><b>No existe ninguna programación</b></i></div>
                <br/>
                <div class="columns small-12">
                    <a class="button small" data-open="reveal-programar-inspeccion">Programar</a>
                    <a class="button small alert" data-open="reveal-eliminar-inspeccion">Eliminar</a>
                </div>
                <div class="small reveal" data-reveal="" id="reveal-programar-inspeccion">
                    <div class="row columns text-center">
                        <i class="fi-alert" style="font-size:32px; color:red"></i>
                    </div>
                    <div class="row columns text-center">
                        <div><b>Al finalizar la programación de la  Inspección, está no se podrá eliminar del Plan Anual</b></div>
                        <div><b>¿Desea continuar con la programación?</b></div>
                    </div>
                    <br/>
                    <div class="row columns text-center">
                        <a class="button small success-2" href="{{route('programar-inspeccion',['id'=>$inspeccion->id,'tipo'=>'sugerida'])}}">Continuar</a>
                        <a class="button small alert" data-close="">Cancelar</a>
                    </div>
                </div>
                
                <div class="small reveal" data-reveal="" id="reveal-eliminar-inspeccion">
                    <div class="row columns text-center">
                        <i class="fi-alert" style="font-size:32px; color:red"></i>
                    </div>
                    <div class="row columns text-center">
                        <div style="font-size:14px"><b>¿Confirma que desea eliminar la inspección {{$inspeccion->nombre}}?</b></div>
                    </div>
                    <br/>
                    <div class="row columns text-center">
                        <a class="button small success-2" href="{{route('eliminar-inspeccion-sugerida',['id'=>$inspeccion->id])}}" >Confirmar</a>
                        <a class="button small alert" data-close="">Cancelar</a>
                    </div>
                </div>
            </div>
        @else
            <!--Quiere decir que la inspeccion esta programada-->
        <div class="row">
            <div class='columns small-12 text-center'>
                <br/>
                <b>Porcentaje Total de Ejecución</b>
                @if($inspeccion->ejecucionTotal < 20)
                    @php $colorBarra = 'warning' @endphp
                @elseif($inspeccion->ejecucionTotal > 80)
                    @php $colorBarra = 'success' @endphp
                @else
                    @php $colorBarra = '' @endphp
                @endif
                <div role="progressbar" tabindex="0" class="progress <?php echo $colorBarra ?>">
                  <span class="progress-meter" style='width:<?php echo $inspeccion->ejecucionTotal?>%'>
                    <p class="progress-meter-text">{{$inspeccion->ejecucionTotal}} %</p>
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
                    <div class="columns small-12"><b>Cargo Responsable: </b>{{$inspeccion->cargo}}</div>
                    <div class="columns small-12"></div>
                </div>
                <div class="row">
                    <div class="columns small-12"><b>Evidencias</b></div>
                    <div class="columns small-12">
                        <textarea style="min-height: 80px" disabled="true">{{$inspeccion->evidencias}}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12"><b>Observaciones</b></div>
                    <div class="columns small-12">
                        <textarea style="min-height: 80px" disabled="true">{{$inspeccion->observaciones}}</textarea>
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
                    <div class="columns small-2 end">{{ucfirst($inspeccion->frecuencia)}}</div>
                </div>
                <div class="row">
                    <div class="columns small-5"><b>% Total de ejecución</b></div>
                    <div class="columns small-3 end">{{$inspeccion->ejecucionTotal}} %</div>
                </div>
                <br/>
                <!--esto es lo que debe salir si la valoracion es una matriz general-->
                <div class="row">
                    <div class="columns small-12">
                        @foreach($empresa->centrosTrabajo as $centro)
                            @php
                                $calendarioCentro = InspeccionesCalendario::where('sistema_id',$sistema->id)  
                                    ->where('centroTrabajo_id',$centro->id)
                                    ->where('inspeccion_id',$inspeccion->id)
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
                                        <div class="columns small-12 text-center" style="background-color:#666666; color:white"><b>Fechas de Ejecución</b></div>
                                        <div class="columns small-12" style="font-size:16px">
                                            <br/>
                                            <b>Responsable: </b>{{$calendarioCentro[0]->responsable}}
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <?php
                                    $arr=$arrMeses=[];  
                                    foreach($calendarioCentro as $calendario):
                                        array_push($arr,$calendario->mes);
                                    endforeach;
                                    $arrMeses = array_unique($arr);    
                                    foreach($arrMeses as $mes):
                                ?>        
                                    <div class="row">
                                        <div class="columns small-12 medium-2"><a href="{{route('calendario-inspecciones-mes',['mes'=>$mes])}}"><b>{{$mes}}</b></a></div>
                                        <?php
                                            $semanas = InspeccionesCalendario::where('sistema_id',$sistema->id)  
                                            ->where('centroTrabajo_id',$centro->id)
                                            ->where('inspeccion_id',$inspeccion->id)
                                            ->where('tipo','sugerida')
                                            ->where('mes',$mes)
                                            ->get();

                                            foreach ($semanas as $semana):
                                        ?>
                                        <div class="columns small-12 medium-2 end">
                                            <a href="{{route('calendario-inspecciones-semana',['mes'=>$mes,'semana'=>$semana->semana])}}" style="text-decoration:underline" title="Ver toda la programacion de esta fecha">
                                                Semana {{$semana->semana}}
                                                @if($semana->ejecutada == "Si")
                                                <i class="fi-check" style="color:#007a00"></i>
                                                @else
                                                <i class="fi-x" style="color:#c12e2a"></i>
                                                @endif
                                            </a>
                                        </div>
                                        <?php
                                            endforeach;
                                        ?>
                                    </div>

                                <?php
                                    endforeach;
                                ?>
                                <hr/>
                            @endif
                        @endforeach
                    </div>
                </div>
                <!--esto es lo que debe salir si la valoracion es una matriz por centro-->
            </div>
            <div id="div-presupuesto" class="columns small-12" >
                @include('analissta.Inspecciones.presupuestoInspecciones',['tabla_origen'=>'inspecciones_obligatorias_sugeridas','tipo'=>'sugerida','origen'=>'inspeccion-sugerida'])
                
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
                        <a class="button small success-2" href="{{route('programar-inspeccion',['id'=>$inspeccion->id,'tipo'=>'sugerida'])}}">Continuar</a>
                        <a class="button small alert" data-close="">Cancelar</a>
                    </div>
                    <button class="close-button" data-close="" aria-label="Close modal" type="button">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <a data-idDiv="0" class="button small" data-open="Reveal-reprogramar2">Reprogramar</a>
                <a data-idDiv="0" class="button small success-2" href="{{route('ejecucion-inspeccion',['id'=>$inspeccion->id,'tipo'=>'sugerida'])}}">Ejecucion</a>
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
    @include('analissta.Inspecciones.modalCrearInspeccionObligatoria')
    @include('analissta.Inspecciones.modalCrearInspeccionSugerida')
@endsection

