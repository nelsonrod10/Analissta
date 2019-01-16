@extends('analissta.layouts.app')

@section('content')
<br/>
<div class="row">
    <fieldset class="fieldset">
        <div class="row columns text-center" style="background:#0c4d78; color: white; font-size: 20px">
            <b>{{$empresa->nombre}}</b>
        </div>
        <form method="POST" name="frm-crearNuevo-Usuario" enctype="multipart/form-data" action="{{ route('crear-nuevo-usuario') }}">
            {{ csrf_field() }}
            <div class="row columns text-center">
                <h5 style="text-decoration:underline">Crear Nuevo Usuario</h5>
            </div>
            
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
                        <input type="text" id="empleadoBuscado" list="datalist-empleados" name="empleadoBuscado" placeholder="Digite Cedula o Nombre del Empleado" required="true"/> 
                        <datalist id="datalist-empleados"></datalist>
                    </div>
                    <div class="columns small-1">
                        <a class="button small seleccionar-empleado">Seleccionar</a>
                    </div>
                </div>
            </div>
            <div class="row columns text-center" style="background:lightgray;">
                <h6><b>Datos del Nuevo Usuario</b></h6>
            </div>
            <br/>
            @include('analissta.Asesores.crearEmpresa.errors')
            <div class="row">
                <div class="columns small-12 medium-4 small-centered text-center">
                    <div class="callout warning">
                        <div>
                            <i class="fi-info" style="font-size:24px; color:red"></i>
                        </div>
                        <div>
                            <i style="font-size:12px"><b>Si no encuentra el empleado, agregue uno nuevo a la Base de datos</b></i>
                        </div>
                        <div>
                            <br/>
                            <a class="button tiny" href="{{ route('agregar-nuevo-empleado',['origen'=>'agregar-nuevo-usuario'])}}">Agregar Nuevo Empleado</a>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="columns small-12 medium-6">

                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label class="text-right middle"><b>Número Identificación: </b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input type="text" id="identificacion" name="identificacion" placeholder="Cédula/Pasaporte" required="true" readonly="true"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label class="text-right middle"><b>Nombres del Usuario: </b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input type="text" id="nombres" name="nombres" placeholder="Nombres" required="true" readonly="true"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label class="text-right middle"><b class="middle">Apellidos del Usuario: </b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos" required="true" readonly="true"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="rolUsu" class="text-right middle"><b>Rol Usuario:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <span id="span-infoFrmIngresoUsu" style="color:#ff4d4d; font-weight: bold; font-size: 12px"><?php //echo $nombEmpErr ?></span>
                            <select class="input-required" required="true" id="rolUsu" name="roleUsuario">
                                <option value="">Rol Usuario...</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Digitador">Digitador</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label class="text-right middle"><b class="middle">Cargo Actual: </b></label>
                        </div>
                        <div class="columns small-12 medium-8 end">
                            <input type="text" id="cargo" name="cargo" placeholder="Nombre del Cargo" required="true" readonly="true"/>
                        </div>
                    </div>
                </div>
                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label class="text-right middle"><b class="middle">Email Usuario: </b></label>
                        </div>
                        <div class="columns small-12 medium-8 end">
                            <input type="text" id="email" name="email" placeholder="email del Usuario" required="true" readonly="true"/>
                        </div>
                    </div>
                </div>
                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label class="text-right middle"><b class="middle">Teléfono Usuario: </b></label>
                        </div>
                        <div class="columns small-12 medium-8 end">
                            <input type="text" id="telefono" name="telefono" placeholder="Teléfono del Usuario" required="true" readonly="true"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns small-12 text-center">
                <input type="submit" class="button success small" value="Agregar Usuario"/>
                <a class="button alert small" href="{{ route('ver-empresa-cliente',['id'=>$empresa->id]) }}">Cancelar</a>
            </div>
        </form>
    </fieldset>
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
                $('#cargo').val(result.empleado.cargo);
                $('#email').val(result.empleado.email);
                $('#telefono').val(result.empleado.telefono);
            });
            //location.assign("?crearAccidente=true&paso=2&idEmp="+valorDigitado);
         });
    });
    
</script>


@endsection
