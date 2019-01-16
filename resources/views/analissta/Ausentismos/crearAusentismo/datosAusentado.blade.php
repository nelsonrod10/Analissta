@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\Http\Controllers\helpers;
    use App\Ausentismos\Ausentismo;
    use App\Empleado;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    
    if(isset($idAusentismo)){
        $ausentismoBD = Ausentismo::find($idAusentismo);
        if($ausentismoBD->ausentado_id !== 0){
            $ausentado = Empleado::where('empresaCliente_id',session('idEmpresaCliente'))
            ->where('identificacion',$ausentismoBD->ausentado_id)
            ->get();
        }
        
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
            <h6><b>DATOS AUSENTISMO</b></h6>
        </div>
    </div>
    <br/>
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row">
        <div class="columns small-12 medium-10 small-centered">
            <form method="post" name="datosAusentismo" action="{{route('crear-datos-ausentado',["id"=>$idAusentismo])}}">
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
                            <input type="text" id="empleadoBuscado" list="datalist-empleados" name="empleadoBuscado" placeholder="Digite Cedula o Nombre del Empleado" required="true" value="<?php echo isset($ausentismoBD)?$ausentismoBD->ausentado_id:''?>"/> 
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
                            <a class="button tiny" href="{{ route('agregar-nuevo-empleado',['origen'=>'datos-ausentismo','idOrigen'=>$idAusentismo])}}">Agregar Nuevo Empleado</a>
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
                                <input type="number" id="identificacion" name="identificacion" placeholder="Cédula/Pasaporte" required="true" readonly="true" value='<?php echo isset($ausentado[0])?$ausentado[0]->identificacion:''?>'/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b>Nombres Trabajador: </b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <input type="text" id="nombres" name="nombres" placeholder="Nombres" required="true" readonly="true" value='<?php echo isset($ausentado[0])?$ausentado[0]->nombre:''?>'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">Apellidos Trabajador: </b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos" required="true" readonly="true" value='<?php echo isset($ausentado[0])?$ausentado[0]->apellidos:''?>'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="rolUsu" class="text-right middle"><b>Fecha Nacimiento:</b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <input type="date" id="nacimiento" name="nacimiento" placeholder="Fecha de Nacimiento" required="true" readonly="true" value='<?php echo isset($ausentado[0])?$ausentado[0]->fechaNacimiento:''?>'/>
                            </div>
                        </div>
                    </div>
                    <div class="columns small-12 medium-6">
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">Género(Hombre/Mujer): </b></label>
                            </div>
                            <div class="columns small-12 medium-8 end">
                                <input type="text" id="genero" name="genero" placeholder="Género" required="true" readonly="true" value='<?php echo isset($ausentado[0])?$ausentado[0]->genero:''?>'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">Cargo Trabajador: </b></label>
                            </div>
                            <div class="columns small-12 medium-8 end">
                                <input type="text" id="cargo" name="cargo" placeholder="Cargo del Trabajador" required="true" readonly="true" value='<?php echo isset($ausentado[0])?$ausentado[0]->cargo:''?>'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label class="text-right middle"><b class="middle">Salario Día (COP): </b></label>
                            </div>
                            <div class="columns small-12 medium-8 end">
                                <input type="text" id="salario" name="salario" placeholder="Salario día en COP" required="true" readonly="true" value='<?php echo isset($ausentado[0])?round($ausentado[0]->salarioMes/30,2):''?>'/>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="row text-center">
                    <div class="columns small-12" data-tabs="">
                        <a class="button small" href="{{ route('crear-ausentismo',["id"=>$idAusentismo]) }}">Anterior</a>
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
            //location.assign("?crearAusentismo=true&paso=2&idEmp="+valorDigitado);
         });
    });
    
</script>
    @include('analissta.Ausentismos.crearAusentismo.modalCancelar')
@endsection