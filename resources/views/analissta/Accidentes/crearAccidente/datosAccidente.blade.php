@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\Http\Controllers\helpers;
    use App\Accidentes\Accidente;
    use App\Empleado;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    
    if(isset($idAccidente)){
        $accidenteBD = Accidente::find($idAccidente);
        if($accidenteBD->accidentado_id !== 0){
            $accidentado = Empleado::where('sistema_id',$sistema->id)
            ->where('identificacion',$accidenteBD->accidentado_id)
            ->get();
        }
        
    }
?>
@section('content')
    @section('titulo-encabezado')
        Reportar Nuevo Accidente 
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
            <h6><b>DATOS ACCIDENTE</b></h6>
        </div>
    </div>
    <br/>
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row">
        <div class="columns small-12 medium-10 small-centered">
            <form method="post" name="datosAccidente" action="{{route('crear-datos-accidentado-accidente',["id"=>$idAccidente])}}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="columns small-12 medium-12 text-center"><b class="middle">Digite la cédula o nombre del empleado</b></div>
                    <div class="columns small-12 text-center">
                        <i style="color:red">Automáticamente se mostrarán los demás datos del empleado</i>
                    </div>
                </div>    
                <div class="row">
                    <div class="columns small-12 medium-6 small-centered">
                        <div class="columns small-11">
                            <div id="div-example"></div>
                            <input type="text" id="empleadoBuscado" list="datalist-empleados" name="empleadoBuscado" placeholder="Digite Cedula o Nombre del Empleado" required="true" value="<?php echo isset($accidenteBD)?$accidenteBD->accidentado_id:''?>"/> 
                            <datalist id="datalist-empleados"></datalist>
                        </div>
                        <div class="columns small-1">
                            <a class="button small seleccionar-empleado">Seleccionar</a>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="columns small-12 medium-8 small-centered text-center">
                        <div>
                            <i class="fi-info" style="font-size:24px; color:red"></i>
                        </div>
                        <div>
                            <i style="font-size:12px"><b>Si no encuentra el empleado, agregue uno nuevo a la Base de datos</b></i>
                        </div>
                        <div>
                            <br/>
                            <a class="button tiny" href="{{ route('agregar-nuevo-empleado',['origen'=>'datos-accidente','idOrigen'=>$idAccidente])}}">Agregar Nuevo Empleado</a>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row columns text-center" style="background:lightgray;">
                    <b>Datos del Empleado Accidentado</b>
                </div>
                <br/>
                <div class="row">
                    <div class="columns small-12 medium-6">

                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b>Número Identificación: </b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <input type="number" id="identificacion" name="identificacion" placeholder="Cédula/Pasaporte" required="true" readonly="true" value='<?php echo isset($accidentado)?$accidentado[0]->identificacion:''?>'/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b>Nombres Trabajador: </b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <input type="text" id="nombres" name="nombres" placeholder="Nombres" required="true" readonly="true" value='<?php echo isset($accidentado)?$accidentado[0]->nombre:''?>'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">Apellidos Trabajador: </b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos" required="true" readonly="true" value='<?php echo isset($accidentado)?$accidentado[0]->apellidos:''?>'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="rolUsu" class="text-right middle"><b>Fecha Nacimiento:</b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <input type="date" id="nacimiento" name="nacimiento" placeholder="Fecha de Nacimiento" required="true" readonly="true" value='<?php echo isset($accidentado)?$accidentado[0]->fechaNacimiento:''?>'/>
                            </div>
                        </div>
                    </div>
                    <div class="columns small-12 medium-6">
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">Género(Hombre/Mujer): </b></label>
                            </div>
                            <div class="columns small-12 medium-8 end">
                                <input type="text" id="genero" name="genero" placeholder="Género" required="true" readonly="true" value='<?php echo isset($accidentado)?$accidentado[0]->genero:''?>'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">Cargo Trabajador: </b></label>
                            </div>
                            <div class="columns small-12 medium-8 end">
                                <input type="text" id="cargo" name="cargo" placeholder="Cargo del Trabajador" required="true" readonly="true" value='<?php echo isset($accidentado)?$accidentado[0]->cargo:''?>'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">Salario Día (COP): </b></label>
                            </div>
                            <div class="columns small-12 medium-8 end">
                                <input type="text" id="salario" name="salario" placeholder="Salario día en COP" required="true" readonly="true" value='<?php echo isset($accidentado)?round($accidentado[0]->salarioMes/30,2):''?>'/>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row columns text-center" style="background:lightgray;">
                    <b>Datos Generales del Accidente</b>
                </div>
                <br/>
                <div class="row">
                    <div class="columns small-12 medium-6">
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">Tipo de Evento: </b></label>
                            </div>
                            <div class="columns small-12 medium-8 end">
                                <select name="tipoEvento" required="true" >
                                    @if($accidenteBD->clasificacion == 'Casi Accidente' || $accidenteBD->clasificacion == 'Enfermedad Laboral')
                                        <option value="{{$accidenteBD->clasificacion}}">{{$accidenteBD->clasificacion}}</option>
                                    @elseif($accidenteBD->clasificacion == 'Accidente')
                                        <option value="">Seleccione...</option>
                                        <option value="Muerte" <?php echo (isset($accidenteBD))?($accidenteBD->tipo_evento == 'Muerte')?"selected":"":""?>>Muerte</option>
                                        <option value="Dias Perdidos" <?php echo (isset($accidenteBD))?($accidenteBD->tipo_evento == 'Dias Perdidos')?"selected":"":""?>>Dias perdidos</option>
                                        <option value="Trabajo Restringido" <?php echo (isset($accidenteBD))?($accidenteBD->tipo_evento == 'Trabajo Restringido')?"selected":"":""?>>Trabajo Restringido</option>
                                        <option value="Tratamiento Medico" <?php echo (isset($accidenteBD))?($accidenteBD->tipo_evento == 'Tratamiento Medico')?"selected":"":""?>>Tratamiento Médico</option>
                                        <option value="Primeros Auxilios"  <?php echo (isset($accidenteBD))?($accidenteBD->tipo_evento == 'Primeros Auxilios')?"selected":"":""?>>Primeros Auxilios</option>
                                    @endif
                                </select>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"/><b class="middle">¿El accidente generó incapacidad? </b></label>
                            </div>
                            <div class="columns small-12 medium-8 end">
                                <select name="incapacidad" required="true">
                                    <option value="">Seleccione...</option>
                                    <option value="Si"  <?php echo (isset($accidenteBD))?($accidenteBD->incapacidad == 'Si')?"selected":"":""?>>SI</option>
                                    <option value="No" <?php echo (isset($accidenteBD))?($accidenteBD->incapacidad == 'No')?"selected":"":""?>>NO</option>
                                </select>
                            </div>
                        </div>
                        @if($accidenteBD->clasificacion == 'Accidente')
                            <div class="row">
                                <div class="columns small-12 medium-4">
                                    <label class="text-right middle"><b class="middle">¿Accidente Grave?</b></label>
                                </div>
                                <div class="columns small-12 medium-8 end">
                                    <input type="radio" id="graveSI" required="true" name="accidenteGrave" value="Si"  <?php echo (isset($accidenteBD))?($accidenteBD->accidente_grave == 'Si')?"checked":"":""?>/> 
                                    <label for="graveSI"> SI </label>
                                    <input type="radio" id="graveNO" required="true" name="accidenteGrave" value="No" <?php echo (isset($accidenteBD))?($accidenteBD->accidente_grave == 'No')?"checked":"":""?>/>
                                    <label for="graveNO"> NO </label>
                                </div>
                            </div>    
                        @endif
                        
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">Afectación a: </b></label>
                            </div>
                            <div class="columns small-12 medium-8 end">
                                <select name="afectacion" required="true">
                                    <option value="">Seleccione...</option>
                                    <option value="Personas"  <?php echo (isset($accidenteBD))?($accidenteBD->afectacion == 'Personas')?"selected":"":""?>>Personas</option>
                                    <option value="Medio Ambiente" <?php echo (isset($accidenteBD))?($accidenteBD->afectacion == 'Medio Ambiente')?"selected":"":""?>>Medio Ambiente</option>
                                    <option value="Equipos" <?php echo (isset($accidenteBD))?($accidenteBD->afectacion == 'Equipos')?"selected":"":""?>>Equipos</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">Nombre Empresa Involucrada: </b></label>
                            </div>
                            <div class="columns small-12 medium-8 end">
                                <input type="text" name="empresa" placeholder="Empresa involucrada" required="true" value=" <?php echo (isset($accidenteBD))?$accidenteBD->nombre_empresa:""?>"/>
                            </div>
                        </div>
                    </div>  
                    <div class="columns small-12 medium-6">
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">Tipo de Empleado: </b></label>
                            </div>
                            <div class="columns small-12 medium-8 end">
                                <select name="tipoEmpleado" required="true">
                                    <option value="">Seleccione...</option>
                                    <option value="Contratista"  <?php echo (isset($accidenteBD))?($accidenteBD->empleado_tipo == 'Contratista')?"selected":"":""?>>Contratista</option>
                                    <option value="Directo" <?php echo (isset($accidenteBD))?($accidenteBD->empleado_tipo == 'Directo')?"selected":"":""?>>Directo</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">Jornada de trabajo: </b></label>
                            </div>
                            <div class="columns small-12 medium-8 end">
                                <select name="jornada" required="true">
                                    <option value="">Seleccione...</option>
                                    <option value="Diurna" <?php echo (isset($accidenteBD))?($accidenteBD->jornada == 'Diurna')?"selected":"":""?>>Diurna</option>
                                    <option value="Nocturna" <?php echo (isset($accidenteBD))?($accidenteBD->jornada == 'Nocturna')?"selected":"":""?>>Nocturna</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">¿Se estaba realizando una labor habitual? </b></label>
                            </div>
                            <div class="columns small-12 medium-8 end">
                                <select name="laborHabitual" required="true">
                                    <option value="">Seleccione...</option>
                                    <option value="Si" <?php echo (isset($accidenteBD))?($accidenteBD->labor_habitual == 'Si')?"selected":"":""?>>SI</option>
                                    <option value="No" <?php echo (isset($accidenteBD))?($accidenteBD->labor_habitual == 'No')?"selected":"":""?>>NO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="columns small-12" data-tabs="">
                        <a class="button small" href="{{ route('crear-accidente',["id"=>$idAccidente]) }}">Anterior</a>
                        <a class="button small alert" data-open="reveal-cancelar-crear">Cancelar</a>
                        <input type="submit" value="Siguiente" class="button small success"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <form method="POST" action="{{url('/empleados/buscar-empleado/:q')}}" accept-charset="UTF-8" id="form-buscar-empleado">
        {{ csrf_field() }}
    </form>

    <form method="POST" action="{{url('/empleados/cargar-datos-empleado/:id')}}" accept-charset="UTF-8" id="form-cargar-datos-empleado">
        {{ csrf_field() }}
    </form>
    <script>
    $(document).ready(function(){
        
        $("#empleadoBuscado").keyup(function(e){
            e.preventDefault();
            var string = $(this).val();
            var form = $('#form-buscar-empleado');
            var url = form.attr('action').replace(':q',string); 
            var data = form.serialize();
            
            $.get(url,data,function(result){
               $('#datalist-empleados').html(result);
            });
        });

        $(".seleccionar-empleado").click(function(e){
            var idEmpleado = $("#empleadoBuscado").val();
            
            if(idEmpleado === ""){
                e.preventDefault();
                return;
            }
            var form = $('#form-cargar-datos-empleado');
            var url = form.attr('action').replace(':id',idEmpleado); 
            var data = form.serialize();
            $.post(url,data,function(result){
                $('#identificacion').val(result.empleado.identificacion);
                $('#nombres').val(result.empleado.nombre);
                $('#apellidos').val(result.empleado.apellidos);
                $('#nacimiento').val(result.empleado.fechaNacimiento);
                $('#genero').val(result.empleado.genero);
                $('#cargo').val(result.empleado.cargo);
                $('#salario').val((result.empleado.salarioMes/30).toFixed(2));
            });
            //location.assign("?crearAccidente=true&paso=2&idEmp="+valorDigitado);
         });
    });
    
</script>
    @include('analissta.Accidentes.crearAccidente.modalCancelar')
@endsection