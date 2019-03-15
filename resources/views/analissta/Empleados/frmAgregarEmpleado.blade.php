@extends('analissta.layouts.appProgramarMedida')

@section('content')
<?php
use App\Http\Controllers\helpers;

date_default_timezone_set('America/Bogota');
$objFechaActual = helpers::getDateNow();
(string)$fechaActual = $objFechaActual->format("Y-m-d");
$objFechaMinNacimiento = helpers::getDateNow()->sub(new DateInterval("P99Y"));
$objFechaMaxNacimiento = helpers::getDateNow()->sub(new DateInterval("P18Y"));

?>
<br/>
<div class="row">
    <fieldset class="fieldset " id="frm-nuevoUsuario">
        
        
    <form method="POST" name="frm-crearNuevo-Empleado" enctype="multipart/form-data" action="{{ route('agregar-nuevo-empleado',['id'=>isset($empleado)?$empleado->id:0,'idOrigen'=>$idOrigen]) }}">
        {{ csrf_field() }}
        <input type="hidden" value="{{ $origen }}" name="pagina" readonly="true" hidden="true" class="hide"/>
        <div class="row columns text-center">
            <br/>
            <h5>
            @if(isset($empleado))
                <span>Editar Datos - </span>
                {{ ucwords(strtolower($empleado->nombre))}} {{ucwords(strtolower($empleado->apellidos)) }}
            @else
                Agregar Nuevo Empleado 
            @endif
            </h5>
        </div>
        @include('analissta.Asesores.crearEmpresa.errors')
        <div class="row">
            <div class="columns small-12 medium-6">
                <div class="row">
                    <div class="columns small-12 medium-4">
                        <label for="nombres" class="text-right middle"><b>Nombres:</b></label>
                    </div>    
                    <div class="columns small-12 medium-8 end">
                        <input required="true" type="text" id="nombres" name="nombres" placeholder="Nombres del Empleado" value="{{ isset($empleado)?$empleado->nombre:"" }}"  <?php echo isset($empleado)?"readonly='true'":""?>/>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-4">
                        <label for="apellidos" class="text-right middle"><b>Apellidos:</b></label>
                    </div>    
                    <div class="columns small-12 medium-8 end">
                        <input required="true" type="text" id="apellidos" name="apellidos" placeholder="Apellidos del Empleado" value="{{ isset($empleado)?$empleado->apellidos:"" }}"  <?php echo isset($empleado)?"readonly='true'":""?>/>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-4">
                        <label for="identifiacion" class="text-right middle"><b># Identificación:</b></label>
                    </div>    
                    <div class="columns small-12 medium-8 end">
                        <input required="true" type="number" id="identificacion" name="id" min="1" placeholder="# Número de Identificacion" value="{{ isset($empleado)?$empleado->identificacion:"" }}"  <?php echo isset($empleado)?"readonly='true'":""?>/>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-4">
                        <label for="nacimiento" class="text-right middle"><b>Fecha de Nacimiento:</b></label>
                    </div>    
                    <div class="columns small-12 medium-8 end">
                        <input required="true" type="date" id="nacimiento" name="nacimiento" min="{{ $objFechaMinNacimiento->format("Y-m-d") }}" max="{{ $objFechaMaxNacimiento->format("Y-m-d") }}"  placeholder="# Fecha de Nacimiento" value="{{ isset($empleado)?$empleado->fechaNacimiento:"" }}"  <?php echo isset($empleado)?"readonly='true'":""?>/>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-4">
                        <label for="genero" class="text-right middle"><b>Genero:</b></label>
                    </div>    
                    <div class="columns small-12 medium-8 end">
                        <select name="genero" required="true">
                            <option value="">Seleccione...</option>
                            <option value="Hombre" <?php echo (isset($empleado) && $empleado->genero === "Hombre")?"selected='true'":""?>>Hombre</option>
                            <option value="Mujer" <?php echo (isset($empleado) && $empleado->genero === "Mujer")?"selected='true'":"" ?>>Mujer</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-4">
                        <label for="ingreso" class="text-right middle"><b>Fecha de Ingreso a la empresa:</b></label>
                    </div>    
                    <div class="columns small-12 medium-8 end">
                        <input required="true" type="date" id="ingreso" name="ingreso" min="{{$empresa->fechaFundacion}}" max="{{$fechaActual}}"  placeholder="# Fecha de Ingreso" value="{{ isset($empleado)?$empleado->fecha_ingreso:"" }}"/>
                    </div>
                </div>
            </div>


            <div class="columns small-12 medium-6">
                <div class="row">
                    <div class="columns small-12 medium-4">
                        <label for="centroTrabajo" class="text-right middle"><b>Centro Trabajo:</b></label>
                    </div>    
                    <div class="columns small-12 medium-8 end">
                        <select name="centroTrabajo" required="true">
                            <option value="">Seleccione...</option>
                            @foreach($empresa->centrostrabajo as $centro)
                                <option value="{{ $centro->id}}" <?php echo (isset($empleado) && $empleado->centrosTrabajos_id === $centro->id)?"selected='true'":"" ?>>{{ $centro->nombre }}</option>
                                
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-4">
                        <label for="cargo" class="text-right middle"><b>Cargo:</b></label>
                    </div>    
                    <div class="columns small-12 medium-8 end">
                        <input required="true" type="text" id="cargo" name="cargo" placeholder="Cargo Actual del Empleado" value="{{ isset($empleado)?$empleado->cargo:"" }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-4">
                        <label for="salario" class="text-right middle"><b>Salario Mensual:</b></label>
                    </div>    
                    <div class="columns small-12 medium-8 end">
                        <input required="true" type="number" id="salario" name="salario" placeholder="Salario Mensual del Empleado" value="{{ isset($empleado)?$empleado->salarioMes:"" }}" min="{{helpers::getSalarioMinimoVigente()}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-4">
                        <label for="email" class="text-right middle"><b>Email:</b></label>
                    </div>    
                    <div class="columns small-12 medium-8 end">
                        <input required="true" type="email" id="email" name="email" placeholder="Email Actual del Empleado" value="{{ isset($empleado)?$empleado->email:"" }}" <?php echo isset($empleado)?"readonly='true'":""?>/>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-4">
                        <label for="telefono" class="text-right middle"><b>Teléfono:</b></label>
                    </div>    
                    <div class="columns small-12 medium-8 end">
                        <input required="true" type="number" id="telefono" name="telefono" placeholder="Teléfono del Empleado" value="{{ isset($empleado)?$empleado->telefono:"" }}"/>
                    </div>
                </div>
                
            </div>
            <div class="columns small-12 text-center">
                <a  class="button alert small" href="{{ route($origen,['id'=>$idOrigen]) }}">Cancelar</a><!--'ver-empresa-cliente',['id'=>$empresa->id]-->
                <input type="submit" class="button success small" value="Guardar"/>
            </div>
        </div>    
    </form>
</fieldset>
</div>


@endsection
