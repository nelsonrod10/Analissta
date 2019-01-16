@extends('analissta.layouts.app')
<?php

    use Illuminate\Support\Facades\Storage;
    
?> 
@section('content')
@include('analissta.layouts.encabezadoEmpresaCliente')
<div class="row columns text-center" style="background:#0c4d78; color: white; font-size: 20px">
    <div><b>EMPLEADOS </b></div>
</div>
<div class="expanded button-group">
    <a class="button" href="{{route('mostrar-empleados')}}">Base Datos Empleados</a>
    <a class="button" href="{{ route('ver-empresa-cliente',['id'=>$empresa->id])}}">Datos Empresa</a>
</div>
@include('analissta.Empleados.errors')
<div class="row">
    <div class="columns small-10 small-centered">
        <div class="callout">
            <div class="text-center"><h4>Cargue Masivo de Empleados</h4></div>
            <div class="text-center"><h6>Analissta ofrece la opción de realizar un cargue masivo a la base de datos de empleados, para ello debe tener en cuenta: </h6></div>
            <div>
                <ol class="list-pasos">
                    @if(Storage::exists($empresa->nit.'/Empleados/BaseDatosEmpleado.xlsx') || Storage::exists($empresa->nit.'/Empleados/BaseDatosEmpleado.xls'))
                    <li>
                        <div>Felicitaciones!!!, tiene un nuevo archivo para subir, únicamente hay que hacer click y listo!!</div>
                        <form action="{{route('cargue-masivo-empleados.store')}}" method="post">
                            {{ csrf_field() }}
                            <button type="submit" class="button primary">Cargar Base Datos Empleados</button>
                        </form>
                        <div>por seguridad una vez analissta realice satisfactoriamente el cargue de los datos, se eliminará automáticamente el archivo</div>
                    </li>
                    <li>
                        <div>Si por algún motivo el archivo tiene errores, o desea subir un archivo diferente, aquí lo puede eliminar</div>
                        <a class="button alert" href="{{route('eliminar-archivo-empleados')}}">Eliminar Archivo Existente</a>
                    </li>    
                    @else
                        <li>
                            <div>La mejor opción para empezar es descargar el archivo modelo</div>
                            <form method="POST" action="{{url('file')}}">
                                {{ csrf_field() }}
                                <input type="hidden" name="path" value="public/7. BASE DATOS EMPLEADOS"/>
                                <input type="hidden" name="file" value="BaseDatosEmpleados.xlsx"/>
                                <input type="submit" value="Descargar Archivo Modelo" class="button success-2"/>
                            </form>
                            <div>Este archivo cuenta con todos los campos necesarios para realizar el proceso de cargue</div>
                        </li>
                        <li>
                            <div>Por lo visto aún no ha subido un archivo con la base de datos que desea cargar: </div>
                            <div class="small-12 medium-6 fieldset">
                                <div class="text-center"><b>Seleccione el Archivo</b></div>
                                <br/>
                                <form action="{{route('subir-archivo-empleados')}}" enctype="multipart/form-data" method="post">
                                    {{ csrf_field() }}
                                    <div class="row columns text-center">
                                        <input type="file" name="file" required=""  accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    </div>
                                    <div class="row columns text-right">
                                        <a class="button small alert" aria-label="Close modal" data-close>Cancelar</a>
                                        <button type="submit" class="button small">Subir Archivo</button>
                                    </div>
                                </form>
                            </div>
                        </li>
                    @endif
                    
                </ol>
            </div>
        </div>
    </div>
</div>
    
<style>
    .list-pasos li{
        padding: 10px;
    }
</style>    
@endsection