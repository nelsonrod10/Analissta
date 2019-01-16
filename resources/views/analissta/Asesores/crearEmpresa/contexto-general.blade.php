@extends('analissta.layouts.appProgramarMedida')

@section('content')
<?php
    use App\EmpresaCliente;
    
    $nombre = $empresa!==null ? $empresa->nombre:'';
    $nit= $empresa!== null ? $empresa->nit:'';
    $telefono= $empresa!== null ? $empresa->telefono:'';
    $direccion= $empresa!== null ? $empresa->direccion:'';
    $ciudad= $empresa!== null ? $empresa->ciudad:'';
    $fechaFundacion= $empresa!== null ? $empresa->fechaFundacion:'';
    $ciiu= $empresa!== null ? $empresa->ciiu:'';
    $actividad= $empresa!== null ? $empresa->activEconomica:'';
    $sector= $empresa!== null ? $empresa->sector:'';
    $descripcion= $empresa!== null ? $empresa->descActivEconomica:'';
    $empleados= $empresa!== null ? $empresa->totalEmpleados:'';;
            
    date_default_timezone_set('America/Bogota');
    $objFechaActual = new DateTime("NOW");;
    (string)$fechaActual = $objFechaActual->format("Y-m-d");
?>    

<br/>
<div class="row">
    <div class="columns small-12 text-center">
        <h4>Crear Empresa</h4>
        
    </div>
    <fieldset class="fieldset">
        <legend class="text-center" style="font-size:18px;"><b>Contexto General</b></legend>
        @include('analissta.Asesores.crearEmpresa.errors')
        <div style="font-size:14px">
            <form method="POST" name="frm-crearNueva-Empresa" enctype="multipart/form-data" action="{{ route('crear-contexto-general') }}">
                {{ csrf_field() }}
                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="nombEmpresa" class="text-right middle"><b>Nombre Empresa:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input type="text" id="nombEmpresa" name="nombEmpresa" required="true" placeholder="Nombre de la Empresa" value="<?php echo  old('nombEmpresa')!==null? old('nombEmpresa'):$nombre ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="nitEmpresa" class="text-right middle"><b>NIT:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input type="number" id="nitEmpresa" name="nitEmpresa" required="true" placeholder="NIT de la Empresa" value="<?php echo  old('nitEmpresa')!==null? old('nitEmpresa'):$nit ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="telEmpresa" class="text-right middle"><b>Teléfono:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input required="true" type="number" id="telEmpresa" name="telEmpresa" placeholder="Teléfono de la Empresa" value="<?php echo  old('telEmpresa')!==null? old('telEmpresa'):$telefono ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="dirEmpresa" class="text-right middle"><b>Dirección Principal:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input type="text" required="true" id="dirEmpresa" name="dirEmpresa" placeholder="Dirección principal de la Empresa" value="<?php echo  old('dirEmpresa')!==null? old('dirEmpresa'):$direccion ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="ciudadEmpresa" class="text-right middle"><b>Ciudad Principal:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input type="text" required="true" id="ciudadEmpresa" name="ciudadEmpresa" placeholder="Ciudad principal de la Empresa" value="<?php echo  old('ciudadEmpresa')!==null? old('ciudadEmpresa'):$ciudad ?>"/>
                        </div>
                    </div>
                    
                </div>


                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="fechaEmpresa" class="text-right middle"><b>Fecha de Fundación:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input type="date" required="true" id="fechaEmpresa" name="fechaEmpresa" max="{{ $fechaActual }}" placeholder="Fecha de Creación de la Empresa" value="<?php echo  old('fechaEmpresa')!==null? old('fechaEmpresa'):$fechaFundacion ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="ciiuEmpresa" class="text-right middle"><b>Código CIIU:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input required="true" type="text" id="ciiuEmpresa" name="ciiuEmpresa" placeholder="Codigo CIIU" value="<?php echo  old('ciiuEmpresa')!==null? old('ciiuEmpresa'):$ciiu ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="activEmpresa" class="text-right middle"><b>Actividad Económica:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input required="true" type="text" id="activEmpresa" name="activEmpresa" placeholder="Actividad Económica de la Empresa" value="<?php echo  old('activEmpresa')!==null? old('activEmpresa'):$actividad ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="sectorEmpresa" class="text-right middle"><b>Sector Económico:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input required="true" type="text" id="sectorEmpresa" name="sectorEmpresa" placeholder="Sector Económico de la Empresa" value="<?php echo  old('sectorEmpresa')!==null? old('sectorEmpresa'):$sector ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="empleadosEmpresa" class="text-right middle"><b># Total de Empleados:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input required="true" type="number" id="empleadosEmpresa" name="empleadosEmpresa" min="1" placeholder="Total Empleados" value="<?php echo  old('empleadosEmpresa')!==null? old('empleadosEmpresa'):$empleados ?>"/>
                        </div>
                    </div>
                </div>
                <div class="columns small-12">
                    <label class="middle"><b>Descripción de la Actividad Económica</b></label>
                </div>
                <div class="columns small-12">
                    <textarea name="descActividad" required="true" style="min-height: 100px;height: auto;"><?php echo  old('descActividad')!==null? old('descActividad'):$descripcion ?></textarea>
                </div>
                <div class="columns small-12 text-center">
                    <a class="button small alert" data-open="modal-confirm-borrarEmpresa">Cancelar</a>
                    <input type="submit" class="button success small" value="Siguiente"/>
                </div>
            </form>
        </div>
    </fieldset>
</div>
@include('analissta.Asesores.crearEmpresa.modalCancelar')

@endsection


