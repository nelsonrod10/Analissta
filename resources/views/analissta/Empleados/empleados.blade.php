@extends('analissta.layouts.app')

@section('content')
<?php 
    use App\Http\Controllers\helpers;
?>
<br/>

<div class="row">
    <div class="columns small-12 callout">
        
        <div class="columns small-12">
            @include('analissta.layouts.encabezadoEmpresaCliente')
            <div class="row columns text-center" style="background:#0c4d78; color: white; font-size: 20px">
                <div><b>EMPLEADOS </b></div>
            </div>
            
            <div class="expanded button-group">
                <a class="button" href="{{ route('ver-empresa-cliente',['id'=>$empresa->id])}}">Datos Empresa</a>
                @if($empresa->tipoValoracion === 'Matriz General')
                <a class="button" href="{{ route('procesos-actividades',['sistema'=>$empresa->sistemaGestion[0]])}}">Procesos y Actividades</a>
                @elseif($empresa->tipoValoracion === 'Matriz por Centro')
                <a class="button" data-open='modal-sistemas-creados'>Procesos y Actividades</a>
                @endif
            </div>
            
            @if($empresa->tipoValoracion === 'Matriz por Centro')
            <div id="modal-sistemas-creados" class="reveal" data-reveal>
                <div class="row columns text-center">
                    <h5>Sistemas Disponibles</h5>
                </div>
                <div class='row'>
                    <div class="columns small-12 medium-8 small-centered">
                        @foreach($empresa->centrosTrabajo as $centro)
                        <div class="columns small-6 medium-8"><b>{{$centro->nombre}}</b></div>
                        <div class="columns small-6 medium-4"><a href='{{route('procesos-actividades',['sistema'=>$centro->sistemaGestion[0]])}}' class='button small'>Ver Sistema</a></div>
                        @endforeach
                    </div>

                </div>
                <button class="close-button" data-close="" aria-label="Close modal" type="button">
                    <span aria-hidden="true">x</span>
                </button> 
            </div>
            @endif
        </div>

        <?php 
            //if($flagNewEmp === null):
        ?>
            <div class="row">
                <fieldset class="fieldset">
                    <legend class="text-center" style="font-size:18px;"><b>Base de Datos Empleados</b></legend>
                    @if($empresa->empleados->count() > 30)
                        <div class="columns small-12 text-center" id="div-btnBdEmpleados">
                            <a  class="button alert small" href="{{ route('ver-empresa-cliente',['id'=>$empresa->id])}}"><i class="fi-arrow-left" ></i> Voler a Datos Empresa</a>
                            <a  class="button success-2 small" href="{{ route('agregar-nuevo-empleado',['origen'=>'mostrar-empleados'])}}"><i class="fi-plus" ></i> Agregar Empleado</a>
                            <a  class="button small" href="{{ route('cargue-masivo-empleados.index')}}" title><i class="fi-upload" style="font-size: px" ></i> Subir Archivo Base Datos</a>
                        </div>
                    @endif
                    <div style="font-size:14px">
                        <div class="row columns text-center">
                            <h5>Listado Empleados</h5>
                        </div>
                        <div class="row text-center">
                            <div class="columns small-2 medium-2"><b>Nombres</b></div>
                            <div class="columns small-2 medium-2"><b>Apellidos</b></div>
                            <div class="columns small-2 medium-2"><b>Identificación</b></div>
                            <div class="columns small-2 medium-1"><b>Nacimiento</b></div>
                            <div class="columns small-2 medium-2"><b>Centro Trabajo</b></div>
                            <div class="columns small-2 medium-2"><b>Cargo</b></div>
                            <div class="columns small-2 medium-1"></div>
                            
                        </div>
                        <div class="row text-center" style="font-size: 14px;">
                            @if($empresa->empleados->count() > 0)
                                @foreach($empresa->empleados as $empleado)
                                    @include('analissta.Empleados.modal-frmUpdateCentroTrabajo')
                                    @include('analissta.Empleados.modal-frmAsignarFechaIngreso')
                                    <?php 
                                        $centroTrabajoEmpleado = App\CentrosTrabajo::find($empleado->centrosTrabajos_id);
                                        $edadEmpleado = helpers::calcularEdad($empleado->fechaNacimiento);
                                        $rangoEdad = helpers::calcularRangoEdad($edadEmpleado);

                                    ?>
                                    <div class="row">
                                        <div class="columns small-12">
                                            <div class="columns small-2 medium-2">{{$empleado->nombre}}</div>
                                            <div class="columns small-2 medium-2">{{$empleado->apellidos}}</div>
                                            <div class="columns small-2 medium-2">{{$empleado->identificacion}}</div>
                                            <div class="columns small-2 medium-1">{{$empleado->fechaNacimiento}}</div>
                                            <div class="columns small-2 medium-2">
                                                {{$centroTrabajoEmpleado->nombre}} 
                                                @if($empresa->centrosTrabajo->count() > 1)
                                                <a class="label secondary" style="font-size:10px; border-radius: 5px" data-open="modal-cambiar-centro-trabajo-{{$empleado->id}}">Cambiar</a>
                                                @endif
                                            </div>
                                            <div class="columns small-2 medium-2">{{$empleado->cargo}}</div>
                                            <div class="columns small-2 medium-1">
                                                <a onclick="mostrarDetallesEmpleado('detalles','{{$empleado->id}}','mostrar')" class="button tiny">Ver Detalles</a>
                                            </div>
                                        </div>

                                        <div class="columns small-2 medium-2"></div>
                                        <div  class=" columns small-12 hide detalles-empleado" id="detalles-{{$empleado->id}}">
                                            <div class="columns small-12 medium-8 small-centered" style="border: 1px solid lightgrey" >
                                                <div class="columns small-12">
                                                    <h4>Datos Generales</h4>
                                                    <h5>{{ucwords($empleado->nombre)}} {{ucwords($empleado->apellidos)}}</h5>
                                                    <p><i>{{strtoupper($empleado->cargo)}}</i></p>
                                                </div>
                                                <div class="columns small-12">
                                                    <div class="columns small-12 medium-6 text-left">
                                                        <div class="columns small-12">
                                                            <b>Identificación: </b>{{$empleado->identificacion}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Fecha de Nacimiento: </b>{{$empleado->fechaNacimiento}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Genero: </b>{{$empleado->genero}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Edad: </b>{{ $edadEmpleado }} años
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Rango Edad: </b>{{ $rangoEdad }} edad
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Ingreso a la Empresa: </b>{{ $empleado->fecha_ingreso }}
                                                            <?php if($empleado->fecha_ingreso === "" || $empleado->fecha_ingreso === null): ?>
                                                                <a class="label alert" style="font-size:10px; border-radius: 5px" data-open="modal-asignar-fecha-ingreso-{{$empleado->id}}">Asignar Fecha</a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    <div class="columns small-12 medium-6 text-left">
                                                        
                                                        <div class="columns small-12">
                                                            <b>Centro de Trabajo: </b>{{ $centroTrabajoEmpleado->nombre }}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Cargo: </b>{{$empleado->cargo}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Salario Mensual: </b>$ {{$empleado->salarioMes}} COP
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Email: </b>{{$empleado->email}} 
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Teléfono: </b>{{$empleado->telefono}}
                                                        </div>
                                                    </div>
                                                    <div class="columns small-12">
                                                        <br/>
                                                        <form method="GET" name="frm-editar-empleado" action="{{ route('editar-datos-empleado',['origen'=>'mostrar-empleados']) }}">
                                                            <input type="hidden" name="idEmpleado" value="{{$empleado->id}}"/>
                                                            <input type="submit" class="button tiny" value="Editar Datos Generales"/>
                                                        </form>
                                                        <a onclick="mostrarDetallesEmpleado('demografico','{{$empleado->id}}','mostrar')" class="button tiny success-2">Perfil Sociodemográfico</a>
                                                        <a class="button tiny warning" data-open="modal-eliminar-{{$empleado->id}}">Eliminar Empleado</a>
                                                        <a class="button tiny alert" onclick="mostrarDetallesEmpleado('detalles','{{$empleado->id}}')">Cerrar</a>
                                                    </div>
                                                </div>
                                                <hr/>
                                            </div>

                                        </div>

                                        <!--Perfil Socio Demografico-->
                                        <div  class=" columns small-12 hide detalles-empleado" id="demografico-{{$empleado->id}}">
                                            <div class="columns small-12 medium-8 small-centered" style="border: 1px solid lightgrey" >
                                                <div class="columns small-12">
                                                    <h4>Perfil Socio Demográfico</h4>
                                                    <h5>{{ucwords($empleado->nombre)}} {{ucwords($empleado->apellidos)}}</h5>
                                                    <br/>
                                                </div>
                                                <div class="columns small-12">
                                                    <!--No tiene datos de perfil sociodemografico-->
                                                    @if($empleado->estadoCivil === 'na')
                                                        <div class="columns small-12 text-center">
                                                            <div class="columns small-12">
                                                                <p><i>No existe un perfil sociodemográfico para este usuario</i></p>
                                                            </div>
                                                            <div class="columns small-12">
                                                                <a href="{{route('perfil-socio-demografico',['id'=>$empleado->id])}}" class="button secondary">Crear Perfil Sociodemográfico</a>
                                                            </div>
                                                        </div>
                                                    @else:

                                                    <div class="columns small-12 medium-6 text-left">
                                                        <div class="columns small-12">
                                                            <b>Estado Civíl: </b> {{$empleado->estadoCivil}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Personas a Cargo: </b> {{$empleado->personasAcargo}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Escolaridad: </b> {{$empleado->escolaridad}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Tipo Vivienda: </b> {{$empleado->tipoVivienda}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Uso Tiempo Libre: </b> {{$empleado->tiempoLibre}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Promedio Ingresos: </b>{{helpers::calcularRangoSalario($empleado->salarioMes)}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Antiguedad Empresa: </b> {{$empleado->antiguedadEmpresa}} años
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Antiguedad Cargo Actual: </b>{{$empleado->antiguedadCargo}} años
                                                        </div>
                                                    </div>

                                                    <div class="columns small-12 medium-6 text-left">
                                                        <div class="columns small-12">
                                                            <b>Tipo Contrato: </b>{{$empleado->tipoContrato}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Ha participado en: </b>{{$empleado->actividades}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Diagnostico de enfermedades: </b>{{$empleado->diagnosticoEnfermedad}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Fumador: </b>{{$empleado->fumador}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Consume Alcohol: </b>{{$empleado->consumoAlcohol}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Practica algún deporte: </b>{{$empleado->deportista}}
                                                        </div>
                                                        <div class="columns small-12">
                                                            <b>Firma de Consentimiento: </b>{{$empleado->firmaConsentimiento}}
                                                        </div>
                                                    </div>
                                                    <div class="columns small-12">
                                                        <br/>
                                                        <a class="button tiny" href="{{ route('perfil-socio-demografico',['id'=>$empleado->id])}}">Editar Datos Sociodemográficos</a>

                                                        <a class="button tiny alert" onclick="mostrarDetallesEmpleado('demografico','{{$empleado->id}}')">Cerrar</a>
                                                    </div>

                                                    @endif
                                                </div>
                                                <hr/>
                                            </div>

                                        </div>

                                        <div class="reveal small" id="modal-eliminar-{{$empleado->id}}" data-reveal>
                                            <br/>
                                            <div class="row columns">
                                                <h5>¿Esta seguro de eliminar a {{ucwords($empleado->nombre)}} {{ucwords($empleado->apellidos)}}?</h5>
                                            </div>


                                            <div class="row columns">
                                                <br/>
                                                <a class="button tiny" href="{{ route('eliminar-empleado',['id'=>$empleado->id])}}">Eliminar</a>
                                                <a class="button tiny alert" data-close>Cancelar</a>
                                            </div>
                                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                @endforeach  
                            @else
                                <br/>
                                <div class="row columns small text-center">
                                    <h6>No existe ningún empleado en la Base de Datos</h6>
                                </div>
                            @endif
                        </div>
                        <br/>
                        <div class="columns small-12 text-center" id="div-btnBdEmpleados">
                            <a  class="button alert small" href="{{ route('ver-empresa-cliente',['id'=>$empresa->id])}}"><i class="fi-arrow-left" ></i> Voler a Datos Empresa</a>
                            <a  class="button success-2 small" href="{{ route('agregar-nuevo-empleado',['origen'=>'mostrar-empleados'])}}"><i class="fi-plus" ></i> Agregar Empleado</a>
                            <div>
                                <a  class="button " href="{{ route('cargue-masivo-empleados.index')}}" title><i class="fi-upload" style="font-size: 24px" ></i> Subir Archivo Base Datos</a>
                            </div>
                        </div>
                        
                        <!-- aca va el frm del empleado -->
                        <br/>
                    </div>
                </fieldset>
            </div>
        <?php
           /* elseif($flagNewEmp === "Empleados"):
                include 'vista/crearEmpleado/frmCrearEmpleado.php';
            elseif($flagNewEmp === "Demograficos"):
                include 'vista/crearEmpleado/frmPerfilSocioDemografico.php';
            endif;*/
        ?>


    </div>
</div>  
<script>
    $(document).ready(function(){
       $(document).foundation();

       $("#codigoDiag").on("keyup",function(e){
           var valorDigitado = $(this).val();
           if(valorDigitado === ""){
               $("#datalist-codigos").html("");
               return;
           }

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    document.getElementById("datalist-codigos").innerHTML = xmlhttp.responseText;
                }
            };
            xmlhttp.open("GET", "index.php?flagGET=codigoDiagnostico&q="+valorDigitado, true);
            xmlhttp.send();
           e.preventDefault();
       });

       $("#codigoDiag").on("change",function(e){
            var valorDigitado = $(this).val();
           if(valorDigitado === ""){
               e.preventDefault();
               return;
           }
           location.assign("?crearAusencia=true&paso=3&codigo="+valorDigitado);
        });

    });

    function mostrarDetallesEmpleado(tipoInfo,idempleado, accion){
        $(".detalles-empleado").each(function(){
           $(this).addClass("hide");
        });

        if(accion === "mostrar"){
            $("#"+tipoInfo+"-"+idempleado).removeClass("hide");
        }

    }

</script>
@endsection
