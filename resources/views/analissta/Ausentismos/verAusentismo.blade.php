@extends('analissta.layouts.appSideBar')
<?php
    use App\Http\Controllers\helpers;
    use App\Ausentismos\Ausentismo;
    use App\Ausentismos\AusentismosCalculo;
    use App\Accidentes\AccidentesAusentismo;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    
    if(count($ausentismo->prorrogas)>0){
        $ultimaProrroga = $ausentismo->prorrogas->last();
        $fechaFinalVigente = $ultimaProrroga->fecha_regreso;
    }else{
        $fechaFinalVigente = $ausentismo->fecha_regreso;
    }
    
    $numProrroga = 0;
    $objFechaFinalVigente = new DateTime($fechaFinalVigente);
    $difFechas = $objFechaActual->diff($objFechaFinalVigente);
    
    
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

@include('analissta.Ausentismos.menuAusentismos')


@endsection
@section('content')
    @section('titulo-encabezado')
        Ausentismos 
    @endsection
    
    @section('buttons-submenus')
        <a class="button small" href="{{route('crear-ausentismo')}}">Crear Ausentismo</a>
        <a class="button small warning" href="{{route('ausentismos')}}">Listado Ausentismos</a>
    @endsection
    
    <div class="row columns text-center">
        <h5><b>Reporte Ausentismo</b></h5>
        <i>Este reporte se creó el {{$ausentismo->created_at}}</i>
    </div>
    <br/>
    <fieldset class="fieldset">
        <div class="row">
            <div class="columns small-12 text-center">
                <div style="background:gray;color:white"><b>Datos Generales</b></div>
            </div>
            <div class="columns small-6" style="font-size:14px">
                <div class="row">
                    <div class="columns small-5"><b>Origen Ausencia:</b></div>
                    <div class="columns small-7 end">{{$ausentismo->clasificacion}}</div>
                </div>
                <div class="row">
                    <div class="columns small-5"><b>Fecha Inicial:</b></div>
                    <div class="columns small-7 end">{{$ausentismo->fecha_inicio}}</div>
                </div>
                @if($ausentismo->prorroga == 'Si' && count($ausentismo->prorrogas) > 0)
                    <div class="row">
                        <div class="columns small-5"><b>1ra Fecha de Regreso:</b></div>
                        <div class="columns small-7 end">{{$ausentismo->fecha_regreso}}</div>
                    </div>
                    <div class="row">
                        <div class="columns small-5"><b>Fecha Regreso Vigente:</b></div>
                        <div class="columns small-7 end">{{$ausentismo->prorrogas->last()->fecha_regreso}}</div>
                    </div>
                    <div class="row">
                        <div class="columns small-5"><b>Numero de Prorrogas: </b></div>
                        <div class="columns small-7 end">{{count($ausentismo->prorrogas)}}</div>
                    </div>
                @else
                    <div class="row">
                        <div class="columns small-5"><b>Fecha de Regreso:</b></div>
                        <div class="columns small-7 end">{{$ausentismo->fecha_regreso}}</div>
                    </div>
                @endif
            </div>
            <div class="columns small-6" style="font-size:14px">
                <div class="row">
                    <div class="columns small-5"><b>Centro Trabajo:</b></div>
                    <div class="columns small-7 end">{{$ausentismo->centroTrabajo->nombre}}</div>
                </div>
                <div class="row">
                    <div class="columns small-5"><b>Hora Inicial:</b></div>
                    <div class="columns small-7 end">{{$ausentismo->hora_inicio}}</div>
                </div>
                @if($ausentismo->prorroga == 'Si' && count($ausentismo->prorrogas) > 0)
                    <div class="row">
                        <div class="columns small-5"><b>1ra Hora de Regreso:</b></div>
                        <div class="columns small-7 end">{{$ausentismo->hora_regreso}}</div>
                    </div>
                    <div class="row">
                        <div class="columns small-5"><b>Hora Regreso Vigente:</b></div>
                        <div class="columns small-7 end">{{$ausentismo->prorrogas->last()->hora_regreso}}</div>
                    </div>
                    
                @else
                    <div class="row">
                        <div class="columns small-5"><b>Hora de Regreso:</b></div>
                        <div class="columns small-7 end">{{$ausentismo->hora_regreso}}</div>
                    </div>
                @endif
            </div>
            @if($ausentismo->clasificacion == 'Accidente Trabajo')
                <?php
                    $accidente = AccidentesAusentismo::where('sistema_id',$sistema->id)
                            ->where('ausentismo_id',$ausentismo->id)
                            ->get();
                ?>
                <br/>
                <div class="columns small-12 callout success text-center">
                    <div><b>Esta Ausencia esta relacionada con un Accidente</b></div>
                    <a class="button tiny" href="{{route('accidente',['id'=>$accidente[0]->accidente_id])}}">Ver Accidente</a>
                </div>
            @endif
            <div class="columns small-12">
                <br/>
                <div><b>Observaciones:</b></div>
                <fieldset class="fieldset">{{$ausentismo->observaciones}}</fieldset>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12 text-center">
                <div style="background:gray;color:white"><b>Evidencias</b></div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="columns small-12">
                @if($ausentismo->evidencias->where('origen_table','Ausentismo')->count() > 0)
                    <div class="row text-center">
                        <div class="columns small-12 medium-5"><b>Nombre del Archivo</b></div>
                        <div class="columns small-12 medium-3"><b>Fecha de creación</b></div>
                        <div class="columns small-12 medium-2 end"></div>
                    </div>    
                    @foreach($ausentismo->evidencias()->where('origen_table','Ausentismo')->get() as $evidencia)
                        <div class="row">
                            <div class="columns small-12 medium-5">{{pathinfo($evidencia->evidencia,2)}}</div>
                            <div class="columns small-12 medium-3">{{$evidencia->created_at}}</div>
                            <div class="columns small-12 medium-2 end"><a class="button tiny" href="{{route('gestionar-evidencia.show',$evidencia)}}">Descargar</a></div>
                        </div>    
                    @endforeach
                @else
                    <div class="callout success text-center">
                        <div class="row columns"><i class="fi-info" style="font-size: 28px; color:#ff6600"></i></div>
                        <div><i>No se ha agregado ninguna evidencia.</i></div>
                    </div>
                @endif
            </div>
            <div class="columns small-12 text-center">
                <a class="button success-2" data-open="reveal-agregar-evidencia-ausentismo">Agregar Evidencia</a>
                @include('analissta.Ausentismos.modal-agregar-evidencia');
            </div>
        </div>
    @if($ausentismo->prorroga == 'Si')
        <div class="row">
            <div class="columns small-12 text-center">
                <div style="background:gray;color:white"><b>Prorrogas</b></div>
                <br/>
            </div>
            <div class="columns small-12 text-center">
                <div class="callout success" style="font-size:14px">
                    <i>
                        <div><b>Debido al origen "{{$ausentismo->clasificacion}}", es posible crear una prorroga para esta Ausencia, por lo tanto debe tener en cuenta que</b></div>
                        <div class="small-centered">
                            <b>se debe tratar del MISMO Diagnóstico</b>
                        </div>
                    </i>
                </div>
            </div>
            @foreach($ausentismo->prorrogas as $prorroga)
                @php
                    $numProrroga++;
                @endphp
                <div class="columns small-12">
                    <div class="text-center" style="border-bottom:1px solid gray;">
                        <div>
                            <b>Prorroga No {{$numProrroga}}</b>
                        </div>
                        <i>Esta prorroga se creó el {{$prorroga->created_at}}</i>
                    </div>
                    <div>
                        <b>Fecha y Hora Final: </b>
                        {{$prorroga->fecha_regreso}} - {{$prorroga->hora_regreso}}
                    </div>
                </div>
                <div class="columns small-12">
                    <b>Observaciones:</b>
                    <fieldset class="fieldset">{{$prorroga->observaciones}}</fieldset>
                </div>
                <br/>
            @endforeach

        </div>
        <div class="row columns text-center">
            <br/>
            <!-- Si la fechaFinalViegente es menor a la fechaActual se deshabilita el boton de crear Prorrogas-->
            @if($objFechaFinalVigente < $objFechaActual)
                <!--No se puede crear prorroga-->
                <div class="callout alert">
                    <div><i>Este reporte venció el {{$fechaFinalVigente}}, ya han pasado más de 48 horas, por lo tanto <b>no es posible crear más prorrogas</b></i></div>
                    <div><i><b>Si es necesario debe reportar una nueva Ausencia</b></i></div>
                </div>
            @elseif(($objFechaFinalVigente >= $objFechaActual)&& ((int)$difFechas->format("%H") < 48))
                <!--Se puede crear prorroga-->
                <form method="post" name="agregar-prorroga" action="{{route('agregar-prorroga')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="idAusentismo" value="{{$ausentismo->id}}" class="hide" hidden="true"/>
                    <input type="submit" class="button warning small" value="Agregar Prorroga"/>
                </form>
                
            @endif
        </div>
        <br/><br/>
    @endif
        <div class="row">
            <br/>
            <div class="columns small-12 text-center">
                <div style="background:gray;color:white"><b>Datos Trabajador</b></div>
            </div>
            <div class="columns small-6">
                <div class="row">
                    <div class="columns small-5"><b>Nombre Trabajador:</b></div>
                    <div class="columns small-7 end">{{$ausentismo->ausentado->nombre}} {{$ausentismo->ausentado->apellidos}}</div>
                </div>
                <div class="row">
                    <div class="columns small-5"><b>Genero:</b></div>
                    <div class="columns small-7 end">{{$ausentismo->ausentado->genero}}</div>
                </div>
                <div class="row">
                    <div class="columns small-5"><b>Fecha Nacimiento:</b></div>
                    <div class="columns small-7 end">{{$ausentismo->ausentado->fechaNacimiento}}</div>
                </div>
                <div class="row">
                    <div class="columns small-5"><b>Identificación:</b></div>
                    <div class="columns small-7 end">{{$ausentismo->ausentado->identificacion}}</div>
                </div>
            </div>
            <div class="columns small-6">
                <div class="row">
                    <div class="columns small-3"><b>Edad:</b></div>
                    <div class="columns small-7 end">{{helpers::calcularEdad($ausentismo->ausentado->fechaNacimiento)}} años</div>
                </div>
                <div class="row">
                    <div class="columns small-3"><b>Rango:</b></div>
                    <div class="columns small-7 end">{{helpers::calcularRangoEdad(helpers::calcularEdad($ausentismo->ausentado->fechaNacimiento))}} años </div>
                </div>
                <div class="row">
                    <div class="columns small-3"><b>Cargo:</b></div>
                    <div class="columns small-7 end">{{$ausentismo->ausentado->cargo}}</div>
                </div>
                <div class="row">
                    <div class="columns small-3"><b>Salario Dia:</b></div>
                    <div class="columns small-7 end">$ {{round($ausentismo->ausentado->salarioMes/30,2)}} COP</div>
                </div>
            </div>
        </div>
        <div class="row">
            <br/>
            <div class="columns small-12 text-center">
                <div style="background:gray;color:white"><b>Datos Ausencia</b></div>
            </div>
            
            <div class="columns small-6 ">
                <div class="row">
                    <div class="columns small-5"><b>Rango Días:</b></div>
                    <div class="columns small-7 end">{{helpers::calcularRangoDias($ausentismo->dias_ausentismo)}} Días</div>
                </div>
            </div>
            <div class="columns small-6 ">
                <div class="row">
                    <div class="columns small-3"><b>EPS:</b></div>
                    <div class="columns small-7 end">{{$ausentismo->eps}}</div>
                </div>
            </div>
            
            <div class="columns small-12">
                <br/>
                <div class="row">
                    <div class="columns small-12 text-center" >
                        <div style="border-bottom:1px solid gray"><b>Datos Diagnóstico</b></div>
                    </div>
                    <div class="columns small-12" >
                        <div class="row">
                            <div class="columns small-2"><b>Sistema:</b></div>
                            <div class="columns small-10 end" id="sistema"></div>
                        </div>
                        <div class="row">
                            <div class="columns small-2"><b>Subsistema:</b></div>
                            <div class="columns small-10 end" id="subsistema"></div>
                        </div>
                        <div class="row">
                            <div class="columns small-2"><b>Código:</b></div>
                            <div class="columns small-9 end" id="codigoDiag">{{$ausentismo->codigo_diagnostico}}</div>
                        </div>
                        <div class="row">
                        <div class="columns small-2"><b>Descripción:</b></div>
                        <div class="columns small-10 end" id="descripcion"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <br/>
            <div class="columns small-12 text-center">
                <div style="background:gray;color:white"><b>Valor Ausencia</b></div>
            </div>
            <div class="columns small-6">
                <div class="row">
                    <div class="columns small-7"><b>Dias de Ausencia Laborales:</b></div>
                    <div class="columns small-3 end">{{$ausentismo->dias_ausentismo}} Días</div>
                </div>
               <div class="row">
                    <div class="columns small-4"><b>Horas Ausencia:</b></div>
                    <div class="columns small-7 end">{{$ausentismo->horas_ausentismo}} horas</div>
                </div>
               <div class="row">
                    <div class="columns small-4"><b>Salario Dia:</b></div>
                    <div class="columns small-7 end">$ {{round($ausentismo->ausentado->salarioMes/30,2)}} COP</div>
                </div>
           </div>
           <div class="columns small-6">
               <div class="row">
                   <div class="columns small-5"><b>Dias Incapacidad:</b></div>
                    <div class="columns small-7 end">{{$ausentismo->dias_totales}} días</div>
               </div>
           </div>
        </div>
        <?php
            $valorAusentismoEmpresa = AusentismosCalculo::where('sistema_id',$sistema->id)
                    ->where('ausentismo_id',$ausentismo->id)
                    ->where('pagador','Empresa')
                    ->get();
            $valorAusentismoOtros = AusentismosCalculo::where('sistema_id',$sistema->id)
                    ->where('ausentismo_id',$ausentismo->id)
                    ->get();
            $valorTotalAusentismo = AusentismosCalculo::where('sistema_id',$sistema->id)
                    ->where('ausentismo_id',$ausentismo->id)
                    ->sum('valor');
        ?>
        <div class="row">
            <div class="columns small-12 text-center">
                <br/>
                <i><b>Este Ausentismo le cuesta a la Empresa: $ <?php echo isset($valorAusentismoEmpresa[0])?$valorAusentismoEmpresa[0]->valor:0 ?> COP</b></i>
            </div>
            <div class="columns small-12 text-center" >
                <br/>
                <div style="border-bottom:1px solid gray"><b>Discriminación Pagos</b></div>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12" style="border-bottom:1px solid gray">
                <div class="columns small-6 medium-3 text-center"><b>Pagador</b></div>
                <div class="columns small-6 medium-2 text-center"><b>Días a Pagar</b></div>
                <div class="columns small-6 medium-2 text-center"><b>Horas a Pagar</b></div>
                <div class="columns small-6 medium-3 text-center"><b>Valor a Pagar</b></div>
                <div class="columns small-6 medium-2 text-center"><b>Porcentaje</b></div>
            </div>
            @foreach($valorAusentismoOtros as $valor)
                <div class="columns small-12">
                    <div class="columns small-6 medium-3 text-center">{{$valor->pagador}}</div>
                    <div class="columns small-6 medium-2 text-center">{{$valor->dias_cobrados}}</div>
                    <div class="columns small-6 medium-2 text-center">{{$valor->horas_cobradas}}</div>
                    <div class="columns small-6 medium-3 text-center">$ {{$valor->valor}} COP</div>
                    <div class="columns small-6 medium-2 text-center">{{$valor->porcentaje}} %</div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="columns small-12 text-center" style="border-top:1px solid gray;">
                <div style=" font-size:14px"><i>Valor = (Dias a Pagar * Valor Dia Salario) * Porcentaje </i></div>
                <br/>
                <div style=" font-size:18px">
                    <b>Valor Total: $ {{$valorTotalAusentismo}} COP</b>
                </div>
            </div>
        </div>
        
    </fieldset>        
    
    <form method="POST" action="{{route('cargar-datos-diagnostico',['diagnostico'=>':diagnostico'])}}" accept-charset="UTF-8" id="form-cargar-datos-diagnostico">
        {{ csrf_field() }}
    </form>
    <script>
        $(document).ready(function(){
            var valorDigitado = $("#codigoDiag").html();
            var form = $('#form-cargar-datos-diagnostico');
            var url = form.attr('action').replace(':diagnostico',valorDigitado); 
            var data = form.serialize();

            $.post(url,data,function(result){
                $('#sistema').html(result.diagnostico.lineaSistemas);
                $('#subsistema').html(result.diagnostico.lineaSubsistema);
                $('#descripcion').html(result.diagnostico.lineaDescripcion);
            }); 
        });
    </script>
@endsection

