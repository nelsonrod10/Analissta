@extends('analissta.layouts.appProgramarMedida')

@section('content')
<?php
    use App\EmpresaCliente;
    
    $nombre = $empresa!==null ? $empresa->nombre:'';
    $tipoValoracionBD = $empresa!==null ? $empresa->tipoValoracion:'';
    
?>
<br/>
<div class="row">
    <div class="columns small-12 text-center">
        <h4>Crear Empresa - {{ strtoupper($nombre) }}</h4>
    </div>
    <fieldset class="fieldset">
        <legend class="text-center" style="font-size:18px;"><b>Tipo de Valoración</b></legend>
        @include('analissta.Asesores.crearEmpresa.errors')
        <div style="font-size:14px">
            <fieldset class="fieldset">
                <form method="POST" name="frm-crearNueva-Empresa" enctype="multipart/form-data" action="{{route('crear-tipo-valoracion')}}">
                    {{ csrf_field() }}
                    <div class="columns small-12">
                        <div class="row text-center">
                            <div class="columns small-12">
                                <h5>¿Cómo va a configurar el Sistema de Gestión de la Empresa?</h5>
                                <br/>
                            </div>    
                            <div class="columns small-12">
                                Se va a realizar una <b>ÚNICA</b> valoración que aplicará para todos los centros de trabajo, Seleccione la siguiente opción:
                            </div>
                            <br/>
                            <div class="columns small-12">
                                <input type="radio" required="true" name="tipoValoracion" id="tipo1" value="Matriz General" <?php if($tipoValoracionBD === "Matriz General"){echo "checked";}?>/>
                                <label for="tipo1">Un Sistema de Gestión para <b>TODOS</b> los Centros de Trabajo</label>
                            </div>
                        </div>

                    </div>

                    <div class="columns small-12">
                        <br/>
                        <div class="row text-center">
                            <div class="columns small-12">
                                Se va a realizar una <b>valoración independiente para cada centro de trabajo</b>, Seleccione la siguiente opción:
                            </div>
                            <br/>
                            <div class="columns small-12">
                                <input type="radio" required="true" name="tipoValoracion" id="tipo2" value="Matriz por Centro" <?php if($tipoValoracionBD === "Matriz por Centro"){echo "checked";}?>/>
                                <label for="tipo2">Un Sistema de gestión por cada centro de Trabajo</label>
                            </div>
                        </div>
                        <br/>
                    </div>
                    <div class="columns small-12 text-center">
                        <a class="button small" href="{{ route('centros-trabajo')}}">Anterior</a>
                        <a class="button small alert" data-open="modal-confirm-borrarEmpresa">Cancelar</a>
                        <input type="submit" class="button success small" value="Siguiente"/>
                    </div>
                </form>
            </fieldset>
        </div>
    </fieldset>
</div>
@include('analissta.Asesores.crearEmpresa.modalCancelar')
@endsection
