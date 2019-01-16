@extends('analissta.layouts.app')

@section('content')
<div class="row">
<fieldset class="fieldset">
    <form method="POST" name="frm-crearNuevo-Centro" enctype="multipart/form-data" action="{{ route('crear-centros-trabajo',['origen'=>'ver-empresa-cliente'])}}">
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
                    <input required="true" type="number"  id="empleados" name="empleados" placeholder="# Empleados del Centro" value="" min="0" />
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
            <div class="callout alert">
                <div class="row columns text-center"><i class="fi-alert" style="font-size: 28px; color:#ff6600"></i></div>
                <div><i>Al crear este nuevo centro NO olvide modificar el total de empleados de la empresa</i></div>
                <br/>
            </div>
        </div>
        <div class="columns small-12 text-center">
            <a href="{{ route('ver-empresa-cliente',['id'=>$empresa->id])}}" class="button small alert">Cancelar</a>
            <input type="submit" class="button success small" value="Crear Centro"/>
        </div>
    </form>
</fieldset>
</div>
@endsection