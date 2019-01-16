@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\Http\Controllers\helpers;
    use App\Ausentismos\Ausentismo;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    
    if(count($ausentismo->prorrogas)>0){
        $ultimaProrroga = $ausentismo->prorrogas->last();
        $fechaFinalVigente = $ultimaProrroga->fecha_regreso;
        $horaFinalVigente = $ultimaProrroga->hora_regreso;
    }else{
        $fechaFinalVigente = $ausentismo->fecha_regreso;
        $horaFinalVigente = $ausentismo->hora_regreso;
    }
?>
@section('content')
    @section('titulo-encabezado')
        Reportar Prorroga 
    @endsection
    <style>
            .titulo-origenes{
                font-size: 16px;
                font-weight: bold;
                color: #3c3737;
            }
            .div-descripcion{
                width: auto;
                height: auto;
                max-width: 100%;
                max-height: 25px;
                overflow: hidden;
                
            }
            .div-descripcion a{
                text-decoration: underline;
            }
            .warning-2{
                background: #f29c13;
                color:white;
            }
            .info-costos{
                font-size: 12px;
                background:#ccffcc;    
            }
        </style>
    <div class="row text-center">
        <div class="columns small-12 medium-10 small-centered label secondary">
            <h6><b>CREAR PRORROGA</b></h6>
        </div>
    </div>
    <div class="row">
            <div class="columns small-12  medium-10 small-centered text-center">
                <div class="callout success" style="font-size:14px">
                    <i>
                        <div><b>Es posible crear una prorroga para esta Ausencia, por lo tanto debe tener en cuenta</b></div>
                        <div class="small-centered">
                            <li>La nueva fecha no puede superar en más de 8 días a la última Fecha Final</li>
                            <li>Se debe tratar del MISMO Diagnóstico</li>
                        </div>
                    </i>
                </div>
            </div>
        </div>
    <br/>
    @include('analissta.Asesores.crearEmpresa.errors')
    
    <div class="row">
        <div class="columns small-12 medium-9 small-centered">
            <fieldset class="fieldset">
            <legend class="text-center" style="font-size:18px;"><b>Datos Actuales</b></legend>
            <div style="font-size:14px">
                <form method="POST" id="frm-data-Prorroga" name="frm-data-Prorroga" action="{{route('crear-prorroga')}}">
                    {{ csrf_field() }}
                    <input type="hidden" class="hide" hidden="true" name="idAusentismo" value="{{$ausentismo->id}}"/>
                    <input type="hidden" class="hide" hidden="true" name="identificacion" value="{{$ausentismo->ausentado->identificacion}}"/>
                    <div class="columns small-12">
                        <div class="row">
                            <div class="columns small-12 text-center">
                                <b>{{strtoupper($ausentismo->ausentado->nombre)}} {{strtoupper($ausentismo->ausentado->apellidos)}}</b>
                            </div>
                            <div class="columns small-12 text-center">
                                <b>{{$ausentismo->clasificacion}}</b>
                            </div>
                            <div class="columns small-12" >
                                <div class="row">
                                    <div class="columns small-2"><b>Código:</b></div>
                                    <div class="columns small-9 end" id="codigoDiag">{{$ausentismo->codigo_diagnostico}}</div>
                                </div>
                                <div class="row">
                                    <div class="columns small-2"><b>Código:</b></div>
                                    <div class="columns small-9 end" id="codigoDiag">{{$ausentismo->codigo_diagnostico}}</div>
                                </div>
                                <div class="row">
                                    <div class="columns small-2"><b>Sistema:</b></div>
                                    <div class="columns small-10 end" id="sistema"></div>
                                </div>
                                <div class="row">
                                    <div class="columns small-2"><b>Subsistema:</b></div>
                                    <div class="columns small-10 end" id="subsistema"></div>
                                </div>

                                <div class="row">
                                    <div class="columns small-2"><b>Descripción:</b></div>
                                    <div class="columns small-10 end" id="descripcion"></div>
                                </div>
                                <div class="row">
                                    <div class="columns small-3"><b>Fecha Regreso Vigente:</b></div>
                                    <div class="columns small-3 end">
                                        <input type="date" readonly="true" id="fechaFinalActual" name="fecha_inicio" required="true" placeholder="Fecha de ausentismo" max="{{ $fechaActual }}" value='{{$fechaFinalVigente}}' />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="columns small-3"><b>Hora Regreso Vigente:</b></div>
                                    <div class="columns small-3 end">
                                        <input type="time"  readonly="true" id="horaFinalActual" name="hora_inicio" required="true" placeholder="Hora del ausentismo" value='{{$horaFinalVigente}}'/>
                                    </div>
                                </div>
                                <br/>
                            </div>

                            <div class="columns small-12 medium-3"><b class="middle">Prorroga hasta: </b></div>
                            <div class="columns small-12 medium-9">
                                <div class="columns small-3">
                                    <label for="dias">Dias: </label>
                                    <input type="number" id="dias" name="dias" required="true" placeholder="dias" step="1" min="0" style="width:50%" class="data-fechaFinal" value="0"/>
                                </div>
                                <div class="columns small-3 end">
                                    <label for="horas">Horas: </label>
                                    <input type="number" id="horas" name="horas" required="true" placeholder="horas" step="1" min="0" max="8" style="width:50%" class="data-fechaFinal" value="0"/>
                                </div>
                                <div class="columns small-12" >
                                    <i style="color:red" id="i-error-fecha"></i>
                                </div>
                            </div>

                            <div class="columns small-12 text-center" >
                                <div style="background: lightblue;" id="div-datosFechaFinal">
                                </div>
                                <br/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12">
                                <p><b>Observaciones:</b></p>
                                <textarea name="observaciones" sytle="min-height:100px;" required="true" class="input-required-paso-5" placeholder="Observaciones sobre la prorroga" style="min-height:100px; height:auto"></textarea>
                            </div>
                        </div>

                        <div class="columns small-12 text-center">
                            <br/>
                            <a class="button small alert" data-open="reveal-cancelar-crear-prorroga">Cancelar</a>
                            <input type="submit" class="button small success" value="Agregar Prorroga"/>
                        </div>
                    </div>
                </form>
            </div>
        </fieldset>
            
        </div>
    </div>
    <form method="POST" action="{{route('calcular-fecha-final-ausentismo',['d'=>':dias','h'=>':horas','fi'=>':fechaInicio','hi'=>':horaInicio'])}}" accept-charset="UTF-8" id="form-calcular-fecha-final-prorroga">
        {{ csrf_field() }}
    </form>
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
            
           
            $(".data-fechaFinal").on("change",function(e){
                var dias = $("#dias").val();
                var horas = $("#horas").val();
                var fechaInicial = $("#fechaFinalActual").val();
                var horaInicial = $("#horaFinalActual").val();
                
                
                $("#i-error-fecha").html(""); $("#div-datosFechaFinal").html("");
                if(dias < 0 || horas < 0){$("#i-error-fecha").html("No se aceptan números negativos");return;}
                if((dias % 1 !== 0) || (horas % 1 !== 0)){$("#i-error-fecha").html("No se aceptan números decimales");return;}
                if(horas > 8){$("#i-error-fecha").html("8 horas o más equivalen a 1 día");return;}
                
                var form = $('#form-calcular-fecha-final-prorroga');
                var url = form.attr('action').replace(':dias',dias).replace(':horas',horas).replace(':fechaInicio',fechaInicial).replace(':horaInicio',horaInicial); 
                var data = form.serialize();
                $.post(url,data,function(result){
                    document.getElementById("div-datosFechaFinal").innerHTML = result;
                });
                e.preventDefault();
             });
             
             $("#frm-data-Prorroga").on("submit",function(e){
                var dias = $("#dias").val();
                var horas = $("#horas").val();
                if(dias === "0" && horas === "0"){
                    alert("La prorroga no puede ser de 0 dias y 0 horas");
                    e.preventDefault();
                }
            }); 
            
                    
        });
    </script>
    @include('analissta.Ausentismos.crearProrroga.modalCancelar')
@endsection