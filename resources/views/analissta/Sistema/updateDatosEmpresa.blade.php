@extends('analissta.layouts.app')

@section('content')
<br/>
<form method="POST" name="frm-update-datos-empresa" action="update-data-empresa">
    {{ csrf_field() }}
    
    <div class="row columns text-center" style="border-bottom:1px solid gray">
        <h5><b>Actualizar Datos Empresa - {{ $empresa->nombre }} </b></h5>
        <div>
            <small><i>**Se muestran únicamente los datos que se pueden editar**</i></small>
        </div>
    </div>
    @include('analissta.Asesores.crearEmpresa.errors')
    <br/>
    <div class="columns small-12 medium-6">
        <div class="row">
            <div class="columns small-12 medium-4">
                <label for="telEmpresa" class="text-right middle"><b>Teléfono:</b></label>
            </div>    
            <div class="columns small-12 medium-8 end">
                <input type="number" id="telEmpresa" name="telEmpresa" placeholder="Teléfono de la Empresa" value="<?php echo  old('telEmpresa')!==null? old('telEmpresa'):$empresa->telefono ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12 medium-4">
                <label for="dirEmpresa" class="text-right middle"><b>Dirección Principal:</b></label>
            </div>    
            <div class="columns small-12 medium-8 end">
                <input class="input-required " onchange="evaluar(this)" type="text" required="true" id="dirEmpresa" name="dirEmpresa" placeholder="Dirección principal de la Empresa" value="<?php echo  old('dirEmpresa')!==null? old('dirEmpresa'):$empresa->direccion ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12 medium-4">
                <label for="ciudadEmpresa" class="text-right middle"><b>Ciudad Principal:</b></label>
            </div>    
            <div class="columns small-12 medium-8 end">
                <input type="text" required="true" id="ciudadEmpresa" name="ciudadEmpresa" placeholder="Ciudad principal de la Empresa" value="<?php echo  old('ciudadEmpresa')!==null? old('ciudadEmpresa'):$empresa->ciudad ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12 medium-4">
                <label for="ciiuEmpresa" class="text-right middle"><b>Código CIIU:</b></label>
            </div>    
            <div class="columns small-12 medium-8 end">
                <input required="true" type="text" id="ciiuEmpresa" name="ciiuEmpresa" placeholder="Codigo CIIU" value="<?php echo  old('ciiuEmpresa')!==null? old('ciiuEmpresa'):$empresa->ciiu ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12 medium-4">
                <label for="activEmpresa" class="text-right middle"><b>Actividad Económica:</b></label>
            </div>    
            <div class="columns small-12 medium-8 end">
                <input required="true" type="text" id="activEmpresa" name="activEmpresa" placeholder="Actividad Económica de la Empresa" value="<?php echo  old('activEmpresa')!==null? old('activEmpresa'):$empresa->activEconomica ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12 medium-4">
                <label for="sectorEmpresa" class="text-right middle"><b>Sector Económico:</b></label>
            </div>    
            <div class="columns small-12 medium-8 end">
                <input required="true" type="text" id="sectorEmpresa" name="sectorEmpresa" placeholder="Sector Económico de la Empresa" value="<?php echo  old('sectorEmpresa')!==null? old('sectorEmpresa'):$empresa->sector ?>"/>
            </div>
        </div>
    </div>
    <div class="columns small-12 medium-6">
        <div class="row">
            <div class="columns small-12 medium-4">
                <label for="directos" class="text-right middle"><b>Total Empleados Directos:</b></label>
            </div>    
            <div class="columns small-12 medium-8 end">
                <input class="input-required input-empleados" required="true" type="number" id="directos" name="directos" placeholder="# Empleados Directos" min="0"  step="1" value="<?php echo  old('directos')!==null? old('directos'):$empresa->totalEmpleadosDirectos ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12 medium-4">
                <label for="temporales" class="text-right middle"><b>Total Empleados Temporales:</b></label>
            </div>    
            <div class="columns small-12 medium-8 end">
                <input class="input-required input-empleados" required="true" type="number" id="temporales" name="temporales" placeholder="# Empleados Temporales" min="0"  step="1" value="<?php echo  old('temporales')!==null? old('temporales'):$empresa->totalEmpleadosTemporales ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12 medium-4">
                <label for="servicios" class="text-right middle"><b>Total Empleados Prestación de Servicios:</b></label>
            </div>    
            <div class="columns small-12 medium-8 end">
                <input class="input-required input-empleados" required="true" type="number" id="servicios" name="servicios" placeholder="# Empleados Prestación de Servicios" min="0" step="1" value="<?php echo  old('servicios')!==null? old('servicios'):$empresa->totalEmpleadosPrestServicios ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12 medium-4">
                <label for="jornada" class="text-right middle"><b>Jornada Laboral**</b></label>
            </div>    
            <div class="columns small-12 medium-8 end">
                <select required="true" id="jornada" name="jornada" placeholder="Jornada Laboral">
                    <option value="">Seleccione..</option>
                    <option value="Lunes a Viernes" <?php if($empresa->jornadaTrabajo === "Lunes a Viernes" || old('jornada') === "Lunes a Viernes"){echo "selected";}?>>Lunes a Viernes</option>
                    <option value="Lunes a Sabado" <?php if($empresa->jornadaTrabajo === "Lunes a Sabado" || old('jornada') === "Lunes a Sabado"){echo "selected";}?>>Lunes a Sabado</option>
                </select>
            </div>
        </div>    
        <div class="row">
            <div class="columns small-12 medium-4">
                <label for="inicio" class="text-right middle"><b>Hora de Llegada**</b></label>
            </div>    
            <div class="columns small-12 medium-8 end">
                <input required="true" type="time" id="inicio" name="inicio" placeholder="Hora de Inicio" value="<?php echo  old('inicio')!==null? old('inicio'):$empresa->horaLlegada ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12 medium-4">
                <label for="salida" class="text-right middle"><b>Hora de Salida**</b></label>
            </div>    
            <div class="columns small-12 medium-8 end">
                <input required="true" type="time" id="salida" name="salida" placeholder="Hora de Salida" value="<?php echo  old('salida')!==null? old('salida'):$empresa->horaSalida ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12 text-center">
                <b><i>(**) Según lo estipulado en el Reglamento Interno de Trabajo.</i></b>
            </div>
        </div>
    </div>
    <div class="columns small-12 medium-10 small-centered">
        <label class="middle"><b>Descripción de la Actividad Económica</b></label>
    </div>
    <div class="columns small-12 medium-10 small-centered">
        <textarea name="descActividad" required="true" style="min-height: 100px;height: auto;"><?php echo  old('descActividad')!==null? old('descActividad'):$empresa->descActivEconomica ?></textarea>
    </div>
    <div class="columns medium-12 text-center">
        <input type="submit" value="Actualizar Datos" class="button small success-2">
        <a class="button small alert" href="{{ route('ver-empresa-cliente',['id'=>$empresa->id]) }}">Cancelar</a>
    </div>
</form>
@endsection
