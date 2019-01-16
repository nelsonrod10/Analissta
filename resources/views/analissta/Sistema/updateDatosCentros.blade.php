@extends('analissta.layouts.app')

@section('content')
<br/>
<div class="row columns text-center" style="border-bottom:1px solid gray">
    <h5><b>{{ $empresa->nombre }}</b></h5>
    <div><b>Actualizar Datos Centro de Trabajo</b></div>
    <div><h5><b>{{$centro->nombre}}</b></h5></div>
    <div>
        <small><i>**Se muestran únicamente los datos que se pueden editar**</i></small>
    </div>
</div>
@include('analissta.Asesores.crearEmpresa.errors')
<br/>
<div class="row">
    <form name="frm-updateData-centro" id="frm-updateData-centro" method="POST" action="{{ route('update-data-centro',['id'=>$centro->id]) }}">
        {{ csrf_field() }}
        <input type="hidden" name="idCentro" value="{{ $centro->id }}"/>
        <div class="columns small-12 medium-6">
            <div class="row">
                <div class="columns small-12 medium-4">
                    <label for="nombre" class="text-right middle"><b>Nombre Centro:</b></label>
                </div>    
                <div class="columns small-12 medium-6 end">
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre del Centro de Trabajo" value="<?php echo  old('nombre')!==null? old('nombre'):$centro->nombre ?>"/>
                </div>
            </div>
            
            <div class="row">
                <div class="columns small-12 medium-4">
                    <label for="empleados" class="text-right middle"><b>Total Empleados:</b></label>
                </div>    
                <div class="columns small-12 medium-6 end">
                    <input required="true" type="text" id="empleados" name="empleados" placeholder="Total empleados del centro de trabajo" value="<?php echo  old('empleados')!==null? old('empleados'):$centro->totalEmpleados ?>"/>
                </div>
            </div>
            
            <div class="row">
                <div class="columns small-12 medium-4">
                    <label for="ciudad" class="text-right middle"><b>Ciudad Centro:</b></label>
                </div>    
                <div class="columns small-12 medium-6 end">
                    <input required="true" type="text" id="ciudad" name="ciudad" placeholder="Ciudad donde está el Centro de Trabajo" value="<?php echo  old('ciudad')!==null? old('ciudad'):$centro->ciudad ?>"/>
                </div>
            </div>
        </div>
        <div class="columns small-12 medium-6">
            <div class="row">
                <div class="columns small-12 medium-4">
                    <label for="direccion" class="text-right middle"><b>Dirección Centro:</b></label>
                </div>    
                <div class="columns small-12 medium-6 end">
                    <input required="true" type="text" id="direccion" name="direccion" placeholder="Dirección del Centro de Trabajo" value="<?php echo  old('direccion')!==null? old('direccion'):$centro->direccion ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12 medium-4">
                    <label for="telefono" class="text-right middle"><b>Teléfono Centro:</b></label>
                </div>    
                <div class="columns small-12 medium-6 end">
                    <input required="true" type="number" step="1" id="telefono" name="telefono" placeholder="Teléfono del Centro de Trabajo" value="<?php echo  old('telefono')!==null? old('telefono'):$centro->telefono ?>"/>
                </div>
            </div>
        </div>
        <div class="columns small-12 text-center">
            <input type="submit" value="Actualizar" class="button small success-2"/>
            <a class="button small alert" href="{{ route('ver-empresa-cliente',['id'=>$empresa->id]) }}">Cancelar</a>
        </div>
</form>
</div>



@endsection
