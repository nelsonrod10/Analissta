@extends('analissta.layouts.app')

@section('content')
<?php
    use Illuminate\Support\Facades\Auth;
    
    $totalEmpleadosCentros=0;
    foreach($empresa->centrostrabajo as $centro):
        $totalEmpleadosCentros += $centro->totalEmpleados;
    endforeach
?>
<br/>
<div class="row">
    @include('analissta.layouts.encabezadoEmpresaCliente')
    <div class="expanded button-group">
        <a class="button" href="{{route('mostrar-empleados')}}">Base Datos Empleados</a>
        <a class="button" href="{{url('/files')}}">Archivos de ayuda</a>
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
    <br/>
    
    <div class="columns small-12" >
        <div class="text-center" style="border-bottom:1px solid gray"><h4><b>Datos Generales</b></h4></div>
        <div class="row" style="font-size: 14px">
            <div class="columns small-12 medium-4">
                <div class="columns small-12 medium-4"><b>Razón Social:</b></div>
                <div class="columns small-12 medium-8">{{$empresa->nombre}}</div>
                <div class="columns small-12 medium-4"><b>NIT:</b></div>
                <div class="columns small-12 medium-8">{{$empresa->nit}}</div>
                <div class="columns small-12 medium-4"><b>Teléfono</b></div>
                <div class="columns small-12 medium-8">{{$empresa->telefono}}</div>
                <div class="columns small-12 medium-4"><b>Dirección</b></div>
                <div class="columns small-12 medium-8">{{ $empresa->direccion}}</div>
                <div class="columns small-12 medium-4"><b>Ciudad</b></div>
                <div class="columns small-12 medium-8">{{ $empresa->ciudad }}</div>
                <div class="columns small-12 medium-4"><b>Tipo Valoración </b></div>
                <div class="columns small-12 medium-8">{{ $empresa->tipoValoracion }}</div>
            </div>
            <div class="columns small-12 medium-4">
                <div class="columns small-12 medium-5"><b>Código CIIU</b></div>
                <div class="columns small-12 medium-7">{{ $empresa->ciiu }}</div>
                <div class="columns small-12 medium-5"><b>Actividad Económica</b></div>
                <div class="columns small-12 medium-7">{{ $empresa->activEconomica }}</div>
                <div class="columns small-12 medium-5"><b>Sector Económico</b></div>
                <div class="columns small-12 medium-7">{{ $empresa->sector }}</div>
                <div class="columns small-12 medium-5"><b>Jornada Laboral</b></div>
                <div class="columns small-12 medium-7">{{ $empresa->jornadaTrabajo }}</div>
                <div class="columns small-12 medium-5"><b>Hora entrada</b></div>
                <div class="columns small-12 medium-7">{{ $empresa->horaLlegada }}</div>
            </div>
            <div class="columns small-12 medium-4">
                <div class="columns small-12 medium-5"><b>Hora salida</b></div>
                <div class="columns small-12 medium-7">{{ $empresa->horaSalida }}</div>
                <div class="columns small-12 medium-5"><b>Total Empleados</b></div>
                <div class="columns small-12 medium-7">{{ $empresa->totalEmpleados }} personas</div>
                <div class="columns small-12 medium-5"><b>Empleados Directos</b></div>
                <div class="columns small-12 medium-7">{{ $empresa->totalEmpleadosDirectos }} personas</div>
                <div class="columns small-12 medium-5"><b>Temporales</b></div>
                <div class="columns small-12 medium-7">{{ $empresa->totalEmpleadosTemporales }} personas</div>
                <div class="columns small-12 medium-5"><b>Prestación Servicios</b></div>
                <div class="columns small-12 medium-7">{{ $empresa->totalEmpleadosPrestServicios }} personas</div>
            </div>
        </div>
        <br/>
        <div class="row columns">
            <b>Descripción Actividad Económica</b>
            <fieldset class="fieldset" style="margin:0px">{{ $empresa->descActivEconomica }}</fieldset>
        </div>
        <br/>
        <div class="row columns text-center small-centered">
            <a class="button small a-editar-datos-empresa" href="{{ route('update-datos-empresa') }}"><i class="fi-save"></i> Editar Datos Generales</a>
            <a class="button success-2 small" href="{{route('mostrar-empleados')}}"><i class="fi-plus"></i> Base Datos Empleados</a>
        </div>
        <div class="row">
            <div class="columns small-12 medium-6 small-centered text-center">
                <div class="callout success">
                    <div class="row columns"><i class="fi-info" style="font-size: 28px; color:#ff6600"></i></div>
                    <div><i>Inicie la valoración del sistema.</i></div>
                    <div>
                        @if($empresa->tipoValoracion === 'Matriz General')
                        <a class="button small" href="{{ route('procesos-actividades',['sistema'=>$empresa->sistemaGestion[0]])}}">Procesos y Actividades</a>
                        @elseif($empresa->tipoValoracion === 'Matriz por Centro')
                        <a class="button" data-open='modal-sistemas-creados'>Procesos y Actividades</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>
<div class="row">
    <div class="columns small-12" >
        <div class="text-center"style="border-bottom:1px solid gray"><h4><b>Centros de Trabajo</b></h4></div>
        @if($empresa->totalEmpleados != $totalEmpleadosCentros)
            <br/>
            <div class="columns small-5 small-centered">
                <div class="alert callout">
                    <div class="row columns text-center"><i class="fi-alert" style="font-size: 28px; color:#ff6600"></i></div>
                    <div><i>Existe una diferencia entre el total de empleados de la empresa y la suma de los empleados de los centros de trabajo:</i></div>
                    <br/>
                    <div><i><b>Total Empleados Empresa: </b>{{ $empresa->totalEmpleados }}</i></div>
                    <div><i><b>Total Empleados Centros Trabajo: </b>{{ $totalEmpleadosCentros }}</i></div>
                    <br/>
                    <div class="text-center"><i>Por favor actualice el total de empleados en los centros de trabajo, o modifique el total de empleados de la empresa</i></div>
                </div>
            </div>
        @endif
        
        <br/>
        @foreach($empresa->centrostrabajo as $centro)
        
        <div class="row" style="font-size: 14px">
            <div class="columns small-12 medium-4">
                <div class="columns small-12 medium-4"><b>Nombre Centro:</b></div>
                <div class="columns small-12 medium-8">{{$centro->nombre}}</div>
                <div class="columns small-12 medium-4"><b>Teléfono</b></div>
                <div class="columns small-12 medium-8">{{$centro->telefono}}</div>
                <div class="columns small-12 medium-4"><b>Dirección</b></div>
                <div class="columns small-12 medium-8">{{$centro->direccion}}</div>

            </div>
            <div class="columns small-12 medium-4">
                <div class="columns small-12 medium-5"><b>Ciudad</b></div>
                <div class="columns small-12 medium-7">{{$centro->ciudad}}</div>
                <div class="columns small-12 medium-5"><b>Nivel Riesgo ARL</b></div>
                <div class="columns small-12 medium-7">{{$centro->nivelRiesgo}}</div>
                <div class="columns small-12 medium-5"><b>Total Empleados </b></div>
                <div class="columns small-12 medium-7">{{$centro->totalEmpleados}}</div>
            </div>
            <div class="columns small-12 medium-4 end">
                <br/>
                <a class="button small" href="{{ route('update-datos-centro',['id'=>$centro->id])}}"><i class="fi-save"></i> Editar Datos Centro</a>
            </div>
        </div>
        <hr style="border: 3px solid lightgrey;"/>
            
        @endforeach
        
    </div>
    <div class="columns small-12 text-center">
        <a class="button small success-2" href="{{ route('crear-nuevo-centro')}}">Crear Nuevo Centro de Trabajo</a>
    </div>
</div> 


<div class="row">
    <div  id="div-usuarios-Sistema" class="columns small-12" >
        <br/>
        <div class="text-center" style="border-bottom:1px solid gray"><h4><b>Usuarios Autorizados</b></h4></div>
        <div style="font-size:14px">
            
            @if(count($empresa->usuarios) > 0)
                <div class="row text-center">
                    <div class="columns small-2 medium-2"><b>Nombre</b></div>
                    <div class="columns small-2 medium-2"><b>Identificación</b></div>
                    <div class="columns small-2 medium-2"><b>Cargo</b></div>
                    <div class="columns small-2 medium-1"><b>Rol</b></div>
                    <div class="columns small-2 medium-2"><b>Email</b></div>
                    <div class="columns small-2 medium-2"><b>Teléfono</b></div>
                    <div class="columns small-2 medium-1"><b></b></div>
                </div>
                @foreach($empresa->usuarios as $usuario)
                    <?php $empleadoUsuario = App\Empleado::find($usuario->empleados_id)?>
                    <div class="row text-center">
                        <div class="columns small-2 medium-2">{{ $empleadoUsuario->nombre}} {{ $empleadoUsuario->apellidos}}</div>
                        <div class="columns small-2 medium-2">{{ $empleadoUsuario->identificacion}}</div>
                        <div class="columns small-2 medium-2">{{ $empleadoUsuario->cargo}}</div>
                        <div class="columns small-2 medium-1">{{ $usuario->role_usuario}}</div>
                        <div class="columns small-2 medium-2">{{ $empleadoUsuario->email}}</div>
                        <div class="columns small-2 medium-2">{{ $empleadoUsuario->telefono}}</div>
                        <div class="columns small-2 medium-1"><a class="button tiny alert" data-open="eliminar-usuario-{{$usuario->id}}">Eliminar</a></div>
                    </div>
                
                <div class="reveal small" data-reveal id="eliminar-usuario-{{$usuario->id}}">
                    <div class="row columns text-center">
                        <h5>Eliminar Usuario</h5>
                    </div>
                    <div class="row columns text-center">
                        <b>¿Está seguro de eliminar a {{ $empleadoUsuario->nombre}} {{ $empleadoUsuario->apellidos}}, como usuario autorizado?</b>
                    </div>
                    <br/>
                    <div class="row columns text-center">
                        <a class="button small success-2" href="{{ route('eliminar-usuario',['id'=>$usuario->id])}}">Confirmar</a>
                        <a class="button small alert" data-close aria-label="Close modal">Cancelar</a>
                    </div>
                    <button class="close-button" data-close aria-label="Close modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endforeach
            @else
                <div class="row text-center">
                    <b><i>No se ha creado ningún usuario</i></b>
                </div>
            @endif
            <br/>
            
            <div class="row columns text-center small-centered">
                <a class="button success-2 small"  href="{{ route('agregar-nuevo-usuario')}}"><i class="fi-plus"></i> Agregar Usuario</a>
            </div>
            
        </div>
    </div>
</div>    

    

@endsection
