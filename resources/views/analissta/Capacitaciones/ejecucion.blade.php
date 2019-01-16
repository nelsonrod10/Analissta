@extends('analissta.layouts.appSideBar')
<?php
    use App\Http\Controllers\helpers;
    use App\CapacitacionesCalendario;
    use App\Evidencia;
    use App\Presupuesto;
    
    $paginaOrigen = "";
    switch ($tipoCapacitacion) {
        case 'obligatoria':
            $paginaOrigen = "capacitacion-obligatoria";
            $origenTable = "CapacitacionesObligatoriasSugerida";
            $origenPresupuesto = "capacitaciones_obligatorias_sugeridas";
            break;
        case 'sugerida':
            $paginaOrigen = "capacitacion-sugerida";
            $origenTable = "CapacitacionesObligatoriasSugerida";
            $origenPresupuesto = "capacitaciones_obligatorias_sugeridas";
            break;
        case 'valoracion':
            $paginaOrigen = "capacitacion-valoracion";
            $origenTable = "CapacitacionesValoracione";
            $origenPresupuesto = "capacitaciones_valoraciones";
            break;
        case 'hallazgo':
            $paginaOrigen = "capacitacion-hallazgo";
            $origenTable = "CapacitacionesHallazgo";
            $origenPresupuesto = "capacitaciones_hallazgos";
            break;
    }
    
    $presupuesto = Presupuesto::where('sistema_id',$sistema->id)
            ->where('tabla_origen',$origenPresupuesto)
            ->where('origen_id',$capacitacion->id)
            ->get();
    
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
        Ejecución Capacitaciones
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
    </div>
    <div class="row">
        <div class="columns small-12 text-center">
            <div ><h5>Ejecución Capacitacion</h5></div>
        </div>
    </div>
    <div class="row">
        <div class='columns small-12 text-center'>
            <b>Porcentaje Total de Ejecución</b>
            @if($capacitacion->ejecucionTotal < 20)
                @php $colorBarra = 'warning' @endphp
            @elseif($capacitacion->ejecucionTotal > 80)
                @php $colorBarra = 'success' @endphp
            @else
                @php $colorBarra = '' @endphp
            @endif
            <div role="progressbar" tabindex="0" class="progress {{$colorBarra}}">
              <span class="progress-meter" style='width:<?php echo $capacitacion->ejecucionTotal?>%'>
                <p class="progress-meter-text">{{$capacitacion->ejecucionTotal}} %</p>
              </span>
            </div>
        </div>
    </div> 
    <div class="row">
        <div class="columns small-12 text-center">
            <div><h6><b>Calendario Programado</b></h6></div>
        </div>
    </div>
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row">
        <div class="columns small-12">
            @foreach($empresa->centrosTrabajo as $centro)
                @php
                    $calendarioCentro = CapacitacionesCalendario::where('sistema_id',$sistema->id)  
                        ->where('centroTrabajo_id',$centro->id)
                        ->where('capacitacion_id',$capacitacion->id)
                        ->where('tipo',$tipoCapacitacion)
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
                    </div>
                    <br/>
                    
                    <div class="row show-for-medium text-center">
                        <div class="columns medium-1"><b style="text-decoration:underline">Mes</b></div>
                        <div class="columns medium-2"><b style="text-decoration:underline">Semana</b></div>
                        <div class="columns medium-1"><b style="text-decoration:underline">Invitados</b></div>
                        <div class="columns medium-2"><b style="text-decoration:underline">Asistentes</b></div>
                        <div class="columns medium-1"><b style="text-decoration:underline">Duración</b></div>
                        <div class="columns medium-2"><b style="text-decoration:underline">Ejecutada</b></div>
                        <div class="columns medium-3"></div>
                    </div>
                    <?php
                        $totalInvitados=$totalCapacitados = 0;
                        foreach($calendarioCentro as $calendario):
                            $totalInvitados += $calendario->invitados;
                            $totalCapacitados += $calendario->asistentes;
                    ?>        
                        <div class="row text-center">
                            <div class="columns small-12 medium-1">{{$calendario->mes}}</div>
                            <div class="columns small-12 medium-2">Semana {{$calendario->semana}} </div>
                            <div class="columns small-12 medium-1">{{$calendario->invitados}} </div>
                            <form method="post" name="frm-ejecucion" action="{{route('guardar-ejecucion-capacitacion')}}">
                                {{ csrf_field() }}
                                <input type="hidden" name="tipo" value="{{$tipoCapacitacion}}"/>
                                <input type="hidden" name="idCapacitacion" value="{{$capacitacion->id}}"/>
                                <input type="hidden" name="idJornada" value="{{$calendario->id}}"/>
                                <div class="columns small-12 medium-2">
                                    <input min="1" type="number" name="asistentes" required="true" placeholder="Asistentes" value="{{$calendario->asistentes}}">
                                </div>    
                                <div class="columns small-12 medium-1">
                                    <input min="0.5" max="24" step="0.5" type="number" name="duracion" required="true" placeholder="En horas" value="{{$calendario->duracion}}">
                                </div>    
                                <div class="columns small-12 medium-2">
                                    @if($calendario->ejecutada == 'Si')
                                    <span class="button small success-2">Ejecutado</span>
                                    @else
                                    <span>
                                        <input type="radio" required="true" name="ejecutada" id="ejecutada-si-{{$calendario->id}}" value="Si" <?php echo ($calendario->ejecutada == "Si")?"checked":""?>/><label for="ejecutada-si-{{$calendario->id}}">Si</label>
                                    </span>
                                    <span>
                                        <input type="radio" required="true" name="ejecutada" id="ejecutada-no-{{$calendario->id}}" value="No"  <?php echo ($calendario->ejecutada == "No")?"checked":""?>/><label for="ejecutada-no-{{$calendario->id}}">No</label>
                                    </span>
                                    @endif
                                </div>
                                <div class="columns small-12 medium-3 text-left">
                                    @if((helpers::obtenerNumeroMes($calendario->mes) <= getdate()["mon"]-1) && ($calendario->ejecutada == 'No'))
                                        @php $disabled = '' @endphp
                                    @else
                                        @php $disabled = 'disabled' @endphp
                                    @endif
                                    @if($calendario->ejecutada == 'No')
                                        <input type="submit" class="button small {{$disabled}}" value="Guardar">
                                    @endif
                                </div>
                            </form>    
                            <div class="columns small-12 medium-3">
                                @if($calendario->ejecutada == 'Si')
                                    <div class="button-group small">
                                        <a class="button alert tiny btn-ver-evidencias" data-id-calendario="{{$calendario->id}}" data-item-calendario="evidencias">({{$calendario->evidencias()->where('origen_table', $origenTable)->count()}})  Evidencias</a>
                                        <a class="button success-2 btn-ver-evidencias" data-id-calendario="{{$calendario->id}}" data-item-calendario="presupuesto"><i class="fi-plus"> </i>Presupuesto</a>    
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if($calendario->ejecutada == 'Si')
                            <div class="fieldset hide div-evidencias" id="div-evidencias-calendario-{{$calendario->id}}">
                                <div class="row columns text-center">
                                    <h5>Evidencias {{$calendario->mes}} - Semana {{$calendario->semana}} </h5>
                                </div>    
                                @if($calendario->evidencias()->where('origen_table', $origenTable)->count() >0 )
                                    <div class="row text-center">
                                        <div class="columns small-12 medium-5"><b>Nombre del Archivo</b></div>
                                        <div class="columns small-12 medium-2"><b>Fecha de creación</b></div>
                                        <div class="columns small-12 medium-2 end"></div>
                                    </div>    
                                    @foreach($calendario->evidencias()->where('origen_table', $origenTable)->get() as $evidencia)
                                        <div class="row">
                                            <div class="columns small-12 medium-5">{{pathinfo($evidencia->evidencia,2)}}</div>
                                            <div class="columns small-12 medium-2">{{$evidencia->created_at}}</div>
                                            <div class="columns small-12 medium-2 end"><a class="button tiny" href="{{route('gestionar-evidencia.show',$evidencia)}}">Descargar</a></div>
                                        </div>    
                                    @endforeach
                                @else
                                    <div class="row columns text-center">
                                        <div class="columns small-10 medium-8 small-centered">
                                            <div class="callout success">
                                                <i><b>No se ha subido ninguna evidencia, si desea agregar una seleccione <label style="font-size:12px" class="label">Agregar Evidencia</label></b></i>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="row columns text-center">
                                    <a class="button small btn-agregar-capacitacion" data-open="modal-agregar-evidencia-capacitacion-{{$calendario->id}}" >Agregar Evidencia</a>
                                    <a class="button small alert btn-cerrar-evidencias" data-id-calendario="{{$calendario->id}}">Cerrar</a>
                                </div> 
                            </div>
                            <div class="fieldset hide div-evidencias" id="div-presupuesto-calendario-{{$calendario->id}}">
                                <div class="row columns text-center">
                                    <h5>Presupuesto Ejecutado en {{$calendario->mes}} - Semana {{$calendario->semana}} </h5>
                                </div>
                                @if($calendario->presupuestoEjecucion()->where('tabla_calendario', 'CapacitacionesCalendario')->count() >0 )
                                    <div class="row text-center">
                                        <div class="columns small-12 medium-3"><b>Item Presupuesto</b></div>
                                        <div class="columns small-12 medium-4"><b>Observaciones</b></div>
                                        <div class="columns small-12 medium-3 end"><b>Valor</b></div>
                                    </div>    
                                    @foreach($calendario->PresupuestoEjecucion()->where('tabla_calendario', 'CapacitacionesCalendario')->get() as $presupuestoEjecucion)
                                        <div class="row">
                                            <div class="columns small-12 medium-3">{{$presupuestoEjecucion->presupuestoGeneral->item}}</div>
                                            <div class="columns small-12 medium-4">{{$presupuestoEjecucion->observaciones}}</div>
                                            <div class="columns small-12 medium-3 text-center end">$ {{$presupuestoEjecucion->valor}} </div>
                                        </div>    
                                    @endforeach
                                @else
                                    <div class="row columns text-center">
                                        <div class="columns small-10 medium-8 small-centered">
                                            <div class="callout success">
                                                <i><b>No se creado ningún item de presupesto para esta ejecución, si desea crear uno seleccione <label style="font-size:12px" class="label">Agregar Presupuesto</label></b></i>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <br/>
                                <div class="row columns text-center">
                                    <a class="button small btn-agregar-actividad " data-open="modal-agregar-presupuesto-medida-{{$calendario->id}}">Agregar Presupuesto</a>
                                    <a class="button small alert btn-cerrar-evidencias" data-id-calendario="{{$calendario->id}}" data-item-calendario="presupuesto">Cerrar</a>
                                </div> 
                            </div>
                            @include('analissta.Capacitaciones.modalAgregarEvidenciaCapacitacion')
                            @include('analissta.Presupuesto.modalAgregarPresupuestoEjecucion',['medida'=>$capacitacion,'calendario_table'=>'CapacitacionesCalendario'])
                        @endif
                    <?php    
                        endforeach;
                    ?>        
                    <div class="row">
                        <div class="columns small-12"><b>Población Objetivo: </b>{{$calendarioCentro[0]->poblacion_objetivo}} Personas</div>
                        <div class="columns small-12"><b>Población Invitada: </b>{{$totalInvitados}} Personas</div>
                        <div class="columns small-12"><b>Pendientes por Invitar: </b>{{$calendarioCentro[0]->poblacion_objetivo - $totalInvitados}} Personas</div>
                        <div class="columns small-12"><b>Población Capacitada: </b>{{$totalCapacitados}} Personas</div>
                        <!--<div class="columns small-12"><b>Pendientes por Capacitar: </b><xsl:value-of select="programar/poblacion - sum(programar/jornadas/jornada[ejecucion = 100]/asistentes) "/> Personas</div>-->
                    </div>    
                @endif
            
            @endforeach
        </div>
    </div>
    <div class="row columns text-center">
        <a class="button " href="{{route($paginaOrigen,['id'=>$capacitacion->id])}}">Volver</a>
    </div>
    
    @include('analissta.Capacitaciones.modalCrearCapacitacionObligatoria')
    @include('analissta.Capacitaciones.modalCrearCapacitacionSugerida')
    <script>
        $(document).ready(function(){
            
            $(".btn-ver-evidencias").on("click", function(e){
                var item = $(this).attr("data-item-calendario");
                var id = $(this).attr("data-id-calendario");
                $(".div-evidencias").each(function(){
                   $(this).addClass("hide");
                });
                $("#div-"+item+"-calendario-"+id).removeClass('hide');
                e.preventDefault();
            });
            
            $(".btn-cerrar-evidencias").on("click", function(e){
                var item = $(this).attr("data-item-calendario");
                var id = $(this).attr("data-id-calendario");
                $("#div-"+item+"-calendario-"+id).addClass('hide');
                e.preventDefault();
            });
        });
    </script>
@endsection
