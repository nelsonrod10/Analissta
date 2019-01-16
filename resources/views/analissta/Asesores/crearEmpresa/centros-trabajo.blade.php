@extends('analissta.layouts.appProgramarMedida')

@section('content')
<?php
    use App\EmpresaCliente;
    
    $nombre = $empresa!==null ? $empresa->nombre:'';
    $totalEmpleadosEmpresa = $empresa!== null ? $empresa->totalEmpleados:0;;
    $totalEmpleadosCentros = 0;
    $centrosTrabajoEmpresa = $empresa->centrosTrabajo;
    
    foreach ($centrosTrabajoEmpresa as $centro) {
        $totalEmpleadosCentros +=$centro->totalEmpleados;
    }
?>   
<br/>
<div class="row">
    <div class="columns small-12 text-center">
        <h4>Crear Empresa - {{ strtoupper($nombre) }}</h4>
    </div>
    <fieldset class="fieldset">
        <legend class="text-center" style="font-size:18px;"><b>Centros de Trabajo</b></legend>
        
        <div class="columns small-12 medium-10 small-centered text-center">
            <div class="callout warning">
                <div class="row columns"><i class="fi-info" style="font-size: 28px; color:#ff6600"></i></div>
                <div><i><b>¿Qué es un Centro de Trabajo?</b></i></div>
                <div><i><b>Decreto 1072 de 2015, Art. 2.2.4.3.9</b></i></div>
                <div class="row columns"  style="font-size: 13px">
                    
                    <div  class="text-left"><i>"...Se entiende por Centro de Trabajo a toda edificación... destinada a una actividad económica en un empresa determinada...</i></div>
                    <div class="text-left"><i>Cuando una empresa tenga más de un centro de trabajo podrán clasificarse los trabajadores de uno o más de ellos en una clase de riesgo diferente, siempre que se configuren las siguientes condiciones:</i></div>
                    <div class="text-left">
                        <i>
                            <ol>
                                <li>Exista una clara diferenciación de las actividades desarrolladas en cada centro de trabajo</li>
                                <li>Que las edificaciones y/o áreas a cielo abierto de los centros de trabajo sean independientes entre sí, como que los trabajadores de las otras áreas no laboren parcial o totalmente en la misma edificación o área a cielo abierto, ni viceversa.</li>
                                <li>Que los factores de riesgo determinados por la actividad económica del centro de trabajo, no impliquen exposición, directa o indirecta, para los trabajadores del otro u otros centros de trabajo, ni viceversa...." </li>
                            </ol>
                        </i>
                    </div>
                </div>
            </div>
        </div>
        <div style="font-size:14px">
            <div class="row columns text-center">
                <h5>Centros Creados</h5>
            </div>
            <div class="row text-center">
                <div class="columns small-2 medium-2"><b>Nombre</b></div>
                <div class="columns small-2 medium-2"><b># Empleados</b></div>
                <div class="columns small-2 medium-2"><b>Nivel Riesgo</b></div>
                <div class="columns small-2 medium-1"><b>Ciudad</b></div>
                <div class="columns small-2 medium-2"><b>Dirección</b></div>
                <div class="columns small-2 medium-1"><b>Teléfono</b></div>
                <div class="columns small-2 medium-1"><b></b></div>
            </div>
            <div class="row text-center">
                @if(count($centrosTrabajoEmpresa) > 0)
                    @foreach ($centrosTrabajoEmpresa as $centro)
                        <div class="columns small-2 medium-2">{{ $centro->nombre }}</div>
                        <div class="columns small-2 medium-2">{{ $centro->totalEmpleados }}</div>
                        <div class="columns small-2 medium-2">{{ $centro->nivelRiesgo }}</div>
                        <div class="columns small-2 medium-1">{{ $centro->ciudad }}</div>
                        <div class="columns small-2 medium-2">{{ $centro->direccion }}</div>
                        <div class="columns small-2 medium-1">{{ $centro->telefono }}</div>
                        <div class="columns small-2 medium-2"><a class="button tiny alert" href="eliminar-centro/{{ $centro->id }}">Eliminar Centro</a></div>
                    @endforeach     
                    <br/>
                    <div class="row columns text-center">
                        <div><b>Total Empleados Empresa {{ $totalEmpleadosEmpresa }} Personas</b></div>
                        <div><b>Total Empleados Centros de Trabajo {{ $totalEmpleadosCentros }} Personas</b></div>
                    </div>
                    
                    @if($totalEmpleadosCentros < $totalEmpleadosEmpresa)
                    <br/>
                    <div class="row">
                        <div class="columns small-10 medium-6 small-centered">
                            <div class=" callout warning">
                                <div><span class="fi-info" style="font-size: 28px; color:orange"></span></div>
                                <div><i>Para poder continuar la suma de los empleados de cada centro debe ser igual al numero total de empleados de la empresa</i></div>
                            </div>
                        </div>
                    </div>
                    
                    @endif
                @else
                    <br/>
                    <div class="row columns small text-center">
                        <h6>No existe ningún centro</h6>
                    </div>
                @endif

            </div>
            
            
            @if($totalEmpleadosCentros < $totalEmpleadosEmpresa)
            <br/>
            <fieldset class="fieldset">
                <form method="POST" name="frm-crearNueva-Empresa" enctype="multipart/form-data" action="{{ route('crear-centros-trabajo',['origen'=>'centros-trabajo'])}}">
                    {{ csrf_field() }}
                    <div class="row columns text-center">
                        <h5 style="text-decoration:underline">Crear Nuevo Centro de Trabajo</h5>
                    </div>
                    @include('analissta.Asesores.crearEmpresa.errors')
                    <div class="columns small-12 medium-6">
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="nombCentro" class="text-right middle"><b>Nombre del Centro:</b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <input  required="true"  type="text" id="nombCentro" name="nombCentro" placeholder="Nombre del Centro de Trabajo" value="{{ old('nombCentro')}}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="empleados" class="text-right middle"><b># Empleados:</b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <input required="true" type="number"  id="empleados" name="empleados" placeholder="# Empleados del Centro" value="{{ $totalEmpleadosEmpresa-$totalEmpleadosCentros }}" min="0" max="{{ $totalEmpleadosEmpresa-$totalEmpleadosCentros }}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="riesgo" class="text-right middle"><b>Nivel de Riesgo más Alto:</b> <i style="font-size:10px">(Según ARL)</i></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <input required="true" list="nivel-Riesgo"  type="number" step="1" min="1" max="5"  id="riesgo" name="riesgo" placeholder="Nivel de Riesgo" value="{{ old('riesgo')}}"/>
                                <datalist id="nivel-Riesgo">
                                    <option value="1"></option>
                                    <option value="2"></option>
                                    <option value="3"></option>
                                    <option value="4"></option>
                                    <option value="5"></option>
                                </datalist>
                            </div>
                        </div>
                    </div>

                    <div class="columns small-12 medium-6">
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="ciudad" class="text-right middle"><b>Ciudad:</b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <input  required="true"  type="text" id="ciudad" name="ciudad" placeholder="Ciudad Centro Trabajo" value="{{ old('ciudad')}}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="direccion" class="text-right middle"><b>Dirección:</b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <input required="true"   type="text" id="direccion" name="direccion" placeholder="Dirección Centro Trabajo" value="{{ old('direccion')}}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="telefono" class="text-right middle"><b>Teléfono:</b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <input required="true"  type="number" id="telefono" name="telefono" min="1" placeholder="Teléfono Centro" value="{{ old('telefono')}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="columns small-12 text-center">
                        <input type="submit" class="button success small" value="Crear Centro"/>
                    </div>
                </form>
            </fieldset>
            
            @endif;
            
            <br/>
            <div class="columns small-12 text-center">
                <a class="button small" href="{{ route('empleados-jornada')}}">Anterior</a>
                <a class="button small alert" data-open="modal-confirm-borrarEmpresa">Cancelar</a>
                <?php if((string)$totalEmpleadosEmpresa === (string)$totalEmpleadosCentros):?>
                    <a class="button success small" href="{{route('tipo-valoracion')}}">Siguiente</a>
                <?php endif;?>
            </div>
        </div>
    </fieldset>
</div>
@include('analissta.Asesores.crearEmpresa.modalCancelar')

@endsection
