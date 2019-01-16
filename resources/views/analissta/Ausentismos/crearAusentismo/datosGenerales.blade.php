@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\Http\Controllers\helpers;
    use App\Ausentismos\Ausentismo;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    //$xml_origenes = simplexml_load_file(base_path("archivosXML/Ausentismos/xml_Origenes.xml"));
    //$origenes = $xml_origenes->xpath("//origenes/origen[@id != 2]");
    
    if(isset($idAusentismo)){
        $ausentismoBD = Ausentismo::find($idAusentismo);
    }
?>
@section('content')
    @section('titulo-encabezado')
        Reportar Nuevo Ausentismo 
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
            <h6><b>DATOS GENERALES</b></h6>
        </div>
    </div>
    <br/>
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row">
        <div class="columns small-12 medium-9 small-centered">
            <form method="post" id="datosGeneralesAusentismo" name="datosGeneralesAusentismo" action="{{route('crear-datos-generales-ausentismo',["id"=>$idAusentismo])}}">
                {{ csrf_field() }}
                <div class="columns small-12 ">
                    <div class="row">
                        <div class="columns small-12 medium-3"><b>Centro Trabajo:</b></div>
                        <div class="columns small-12 medium-6 end">
                            <select name="centro" required="true">
                                <option value="">Seleccione un centro...</option>
                                @foreach ($sistema->centrosTrabajo as $centro)
                                    <option value="{{$centro->id}}" <?php echo (isset($ausentismoBD))?($ausentismoBD->centrosTrabajo_id == $centro->id)?"selected":"":""?> >
                                        {{$centro->nombre}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                            <div class="columns small-12 medium-3"><b>Origen Ausencia: </b></div>
                            <div class="columns small-12 medium-9 end">
                                <!--<div class="columns small-12 medium-4 end">
                                    <input type="radio" id="origenAcc" name="clasificacion" value="Accidente Trabajo" required="true"/>
                                    <label for="origenAcc">Accidente de Trabajo</label>
                                </div>-->
                                <div class="columns small-12 medium-4 end">
                                    <input type="radio" id="origen1" name="clasificacion" value="Enfermedad General" required="true"/>
                                    <label for="origen1">Enfermedad General</label>
                                </div>
                                <div class="columns small-12 medium-4 end">
                                    <input type="radio" id="origen2" name="clasificacion" value="Accidente Comun" required="true"/>
                                    <label for="origen2">Accidente Comun</label>
                                </div>
                                <div class="columns small-12 medium-4 end">
                                    <input type="radio" id="origen3" name="clasificacion" value="Enfermedad Laboral" />
                                    <label for="origen3">Enfermedad Laboral</label>
                                </div>
                                <div class="columns small-12 medium-4 end">
                                    <input type="radio" id="origen5" name="clasificacion" value="Licencia Paternidad" required="true"/>
                                    <label for="origen5">Licencia Paternidad</label>
                                </div>
                                <div class="columns small-12 medium-4 end">
                                    <input type="radio" id="origen6" name="clasificacion" value="Licencia Maternidad" required="true"/>
                                    <label for="origen6">Licencia Maternidad</label>
                                </div>
                                <div class="columns small-12 medium-4 end">
                                    <input type="radio" id="origen7" name="clasificacion" value="Permiso no Remunerado" required="true"/>
                                    <label for="origen7">Permiso no Remunerado</label>
                                </div>
                                <div class="columns small-12 medium-4 end">
                                    <input type="radio" id="origen8" name="clasificacion" value="Cita Medica" required="true"/>
                                    <label for="origen8">Cita Medica</label>
                                </div>
                                <div class="columns small-12 medium-4 end">
                                    <input type="radio" id="origen9" name="clasificacion" value="Permiso Particular" required="true"/>
                                    <label for="origen9">Permiso Particular</label>
                                </div>
                                <div class="columns small-12 medium-4 end">
                                    <input type="radio" id="origen10" name="clasificacion" value="Ausencia Injustificada" required="true"/>
                                    <label for="origen10">Ausencia Injustificada</label>
                                </div>
                                <div class="columns small-12 medium-4 end">
                                    <input type="radio" id="origen11" name="clasificacion" value="Licencia Luto" required="true"/>
                                    <label for="origen11">Licencia Luto</label>
                                </div>
                                <div class="columns small-12 medium-4 end">
                                    <input type="radio" id="origen12" name="clasificacion" value="Calamidad Familiar" required="true"/>
                                    <label for="origen12">Calamidad Familiar *</label>
                                </div>
                                <div class="columns small-12 medium-4 end">
                                    <input type="radio" id="origen13" name="clasificacion" value="Calamidad Domestica" required="true"/>
                                    <label for="origen13">Calamidad Doméstica **</label>
                                </div>
                                <div class="columns small-12 end" style="font-size:13px">
                                    <div><i>* Calamidad Familiar(Muertes, accidentes, enfermedades súbitas de familiares)</i></div>
                                    <div><i>** Calamidad Domestica(Inundaciones, Incendios, Sismos, Derrumbes)</i></div>
                                </div>
                            </div>
                        </div>
                    <div class="row">
                        <br/>
                        <div class="columns small-12 medium-3"><b class="middle">Fecha Inicio: </b></div>
                        <div class="columns small-12 medium-3 end">
                            <input type="date" id="fechaInicial" name="fecha_inicio" required="true" placeholder="Fecha de ausentismo" max="{{ $fechaActual }}" value='<?php echo (isset($ausentismoBD))? $ausentismoBD->fecha_inicio:$fechaActual?>'/>
                        </div>
                    </div>
                    <div class="row">
                        <br/>
                        <div class="columns small-12 medium-3"><b class="middle">Hora Inicio: </b></div>
                        <div class="columns small-12 medium-3 end">
                            <input type="time"  id="horaInicial" name="hora_inicio" required="true" placeholder="Hora del ausentismo" value='<?php echo (isset($ausentismoBD))? $ausentismoBD->hora_inicio:"07:00:00"?>'/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><b class="middle">La ausencia termina en: </b></div>
                        <div class="columns small-12 medium-9">
                            <div class="columns small-3">
                                <label for="dias">Dias: </label>
                                <input type="number" id="dias" name="dias" required="true" placeholder="dias" step="1" min="0" style="width:50%" class="data-fechaFinal" value='<?php echo (isset($ausentismoBD))? $ausentismoBD->dias_ausentismo:"0"?>'/>
                            </div>
                            <div class="columns small-3 end">
                                <label for="horas">Horas: </label>
                                <input type="number" id="horas" name="horas" required="true" placeholder="horas" step="1" min="0" max="8" style="width:50%" class="data-fechaFinal" value='<?php echo (isset($ausentismoBD))? $ausentismoBD->horas_ausentismo:"0"?>'/>
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
                        <div class="columns small-12 text-left">
                            <p><b>Observaciones</b></p>
                            <textarea name="observaciones" sytle="min-height:100px;" required="true"  placeholder="Describa Brevemente el ausentismo" style="min-height:100px; height:auto"><?php echo (isset($ausentismoBD))? $ausentismoBD->observaciones :""?></textarea>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row columns text-center">
                    <a class="button small alert" data-open="reveal-cancelar-crear">Cancelar</a>
                    <input type="submit" class="button small success" value="Siguiente"/>
                </div>    
            </form>
        </div>
    </div>
    <form method="POST" action="{{route('calcular-fecha-final-ausentismo',['d'=>':dias','h'=>':horas','fi'=>':fechaInicio','hi'=>':horaInicio'])}}" accept-charset="UTF-8" id="form-calcular-fecha-final">
        {{ csrf_field() }}
    </form>
    <script>
        $(document).ready(function(){
           $("#datosGeneralesAusentismo").on("submit",function(e){
                var dias = $("#dias").val();
                var horas = $("#horas").val();
                if(dias === "0" && horas === "0"){
                    alert("La ausencia no puede ser de 0 dias y 0 horas");
                    e.preventDefault();
                }
            }); 
            
            $(".data-fechaFinal").on("change",function(e){
                var dias = $("#dias").val();
                var horas = $("#horas").val();
                var fechaInicial = $("#fechaInicial").val();
                var horaInicial = $("#horaInicial").val();
                
                $("#i-error-fecha").html(""); $("#div-datosFechaFinal").html("");
                if(dias < 0 || horas < 0){$("#i-error-fecha").html("No se aceptan números negativos");return;}
                if((dias % 1 !== 0) || (horas % 1 !== 0)){$("#i-error-fecha").html("No se aceptan números decimales");return;}
                if(horas > 8){$("#i-error-fecha").html("8 horas o más equivalen a 1 día");return;}
                
                
                
                var form = $('#form-calcular-fecha-final');
                var url = form.attr('action').replace(':dias',dias).replace(':horas',horas).replace(':fechaInicio',fechaInicial).replace(':horaInicio',horaInicial); 
                var data = form.serialize();
                $.post(url,data,function(result){
                    document.getElementById("div-datosFechaFinal").innerHTML = result;
                });
                e.preventDefault();
             });
                    
        });
    </script>
    @include('analissta.Ausentismos.crearAusentismo.modalCancelar')
@endsection