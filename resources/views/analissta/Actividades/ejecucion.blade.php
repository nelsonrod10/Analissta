@extends('analissta.layouts.appSideBar')
<?php
    use App\Http\Controllers\helpers;
    use App\ActividadesCalendario;
    use App\Evidencia;
    use App\Presupuesto;
    
    $paginaOrigen = "";
    switch ($tipoActividad) {
        case 'obligatoria':
            $paginaOrigen = "actividad-obligatoria";
            $origenTable = "ActividadesObligatoriasSugerida";
            $origenPresupuesto = "actividades_obligatorias_sugeridas";
            break;
        case 'sugerida':
            $paginaOrigen = "actividad-sugerida";
            $origenTable = "ActividadesObligatoriasSugerida";
            $origenPresupuesto = "actividades_obligatorias_sugeridas";
            break;
        case 'valoracion':
            $paginaOrigen = "actividad-valoracion";
            $origenTable = "ActividadesValoracione";
            $origenPresupuesto = "actividades_valoraciones";
            break;
        case 'hallazgo':
            $paginaOrigen = "actividad-hallazgo";
            $origenTable = "ActividadesHallazgo";
            $origenPresupuesto = "actividades_hallazgos";
            break;
        //falta meter la opcion de hallazgos
        
    }
    $presupuesto = Presupuesto::where('sistema_id',$sistema->id)
            ->where('tabla_origen',$origenPresupuesto)
            ->where('origen_id',$actividad->id)
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
    @include('analissta.Actividades.menuActividades')
@endsection

@section('content')
    @section('titulo-encabezado')
        Ejecución Actividades
    @endsection
    @section('buttons-submenus')
        <a class="button small" data-open="modal-crear-actividad-obligatoria">Crear Actividad Obligatoria</a>
        <a class="button small" data-open="modal-crear-actividad-sugerida">Crear Actividad Sugerida</a>
        <a class="button small warning" href="{{route('indicadores-actividades')}}">Indicadores Actividades</a>
        <a class="button small alert" href="{{route('actividades')}}">Calendario Actividades</a>
    @endsection
    <div class="row">
        <div class="columns small-12 text-center">
            <div style="background:#0c4d78; color:white"><h5>{{$actividad->nombre}}</h5></div>
        </div>
    </div>
    <div class="row">
        <div class="columns small-12 text-center">
            <div ><h5>Ejecución Actividad</h5></div>
        </div>
    </div>
    <div class="row">
        <div class='columns small-12 text-center'>
            <b>Porcentaje Total de Ejecución</b>
            @if($actividad->ejecucionTotal < 20)
                @php $colorBarra = 'warning' @endphp
            @elseif($actividad->ejecucionTotal > 80)
                @php $colorBarra = 'success' @endphp
            @else
                @php $colorBarra = '' @endphp
            @endif
            <div role="progressbar" tabindex="0" class="progress {{$colorBarra}}">
              <span class="progress-meter" style='width:<?php echo $actividad->ejecucionTotal?>%'>
                <p class="progress-meter-text">{{$actividad->ejecucionTotal}} %</p>
              </span>
            </div>
        </div>
    </div> 
    <div class="row">
        <div class="columns small-12 text-center">
            <div><h6><b>Calendario Programado</b></h6></div>
        </div>
    </div>
    <div class="row">
        <div class="columns small-12">
            @foreach($empresa->centrosTrabajo as $centro)
                @php
                    $calendarioCentro = ActividadesCalendario::where('sistema_id',$sistema->id)  
                        ->where('centroTrabajo_id',$centro->id)
                        ->where('actividad_id',$actividad->id)
                        ->where('tipo',$tipoActividad)
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
                        <div class="columns small-12 medium-12 medium-centered">
                            <div class="columns medium-3"><b style="text-decoration:underline">Mes</b></div>
                            <div class="columns medium-2"><b style="text-decoration:underline">Semana</b></div>
                            <div class="columns medium-3"><b style="text-decoration:underline">Ejecutada</b></div>
                            <div class="columns medium-4"></div>
                        </div>
                    </div>
                    <?php
                        foreach($calendarioCentro as $calendario):
                    ?>        
                        <div class="row text-center">
                            <div class="columns small-12 medium-3"><b>{{$calendario->mes}}</b></div>
                            <div class="columns small-12 medium-2">Semana {{$calendario->semana}} </div>
                            <form method="post" name="frm-ejecucion" action="{{route('guardar-ejecucion-actividad')}}">
                                {{ csrf_field() }}
                                <input type="hidden" name="tipo" value="{{$tipoActividad}}"/>
                                <input type="hidden" name="idActividad" value="{{$actividad->id}}"/>
                                <input type="hidden" name="idJornada" value="{{$calendario->id}}"/>
                                <div class="columns small-12 medium-3">
                                    @if($calendario->ejecutada == 'Si')
                                    <span class="button small success-2">Ejecutado</span>
                                    @else
                                    <div class="columns small-6">
                                        <input type="radio" required="true" name="ejecutada" id="ejecutada-si-{{$calendario->id}}" value="Si" <?php echo ($calendario->ejecutada == "Si")?"checked":""?>/><label for="ejecutada-si-{{$calendario->id}}">Si</label>
                                    </div>
                                    <div class="columns small-6">
                                        <input type="radio" required="true" name="ejecutada" id="ejecutada-no-{{$calendario->id}}" value="No"  <?php echo ($calendario->ejecutada == "No")?"checked":""?>/><label for="ejecutada-no-{{$calendario->id}}">No</label>
                                    </div>
                                    @endif
                                </div>
                                <div class="columns small-12 medium-4 text-left">
                                    @if((helpers::obtenerNumeroMes($calendario->mes) <= getdate()["mon"]-1) && ($calendario->ejecutada == 'No'))
                                        @php $disabled = '' @endphp
                                    @else
                                        @php $disabled = 'disabled' @endphp
                                    @endif
                                    @if($calendario->ejecutada == 'No')
                                        <input type="submit" class="button small {{$disabled}} " value="Guardar">
                                    @endif
                                </div>
                            </form>     
                            <div class="columns small-12 medium-4">
                                @if($calendario->ejecutada == 'Si')
                                    <div class="button-group small">
                                        <a class="button alert btn-ver-evidencias" data-id-calendario="{{$calendario->id}}" data-item-calendario="evidencias">({{$calendario->evidencias()->where('origen_table', $origenTable)->count()}}) Evidencias</a>
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
                                    <a class="button small btn-agregar-actividad " data-open="modal-agregar-evidencia-actividad-{{$calendario->id}}">Agregar Evidencia</a>
                                    <a class="button small alert btn-cerrar-evidencias" data-id-calendario="{{$calendario->id}}"  data-item-calendario="evidencias">Cerrar</a>
                                </div> 
                            </div>
                            <div class="fieldset hide div-evidencias" id="div-presupuesto-calendario-{{$calendario->id}}">
                                <div class="row columns text-center">
                                    <h5>Presupuesto Ejecutado en {{$calendario->mes}} - Semana {{$calendario->semana}} </h5>
                                </div>
                                @if($calendario->presupuestoEjecucion()->where('tabla_calendario', 'ActividadesCalendario')->count() >0 )
                                    <div class="row text-center">
                                        <div class="columns small-12 medium-3"><b>Item Presupuesto</b></div>
                                        <div class="columns small-12 medium-4"><b>Observaciones</b></div>
                                        <div class="columns small-12 medium-3 end"><b>Valor</b></div>
                                    </div>    
                                    @foreach($calendario->PresupuestoEjecucion()->where('tabla_calendario', 'ActividadesCalendario')->get() as $presupuestoEjecucion)
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
                            @include('analissta.Actividades.modalAgregarEvidenciaActividad')
                            @include('analissta.Presupuesto.modalAgregarPresupuestoEjecucion',['medida'=>$actividad,'calendario_table'=>'ActividadesCalendario'])
                        @endif
                    <?php    
                        endforeach;
                    ?>        
                @endif
            @endforeach
        </div>
    </div>
    <div class="row columns text-center">
        <a class="button " href="{{route($paginaOrigen,['id'=>$actividad->id])}}">Volver</a>
    </div>
    
    @include('analissta.Actividades.modalCrearActividadObligatoria')
    @include('analissta.Actividades.modalCrearActividadSugerida')
    
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
