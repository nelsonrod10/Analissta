@extends('analissta.layouts.appProgramarMedida')

@section('content')
<?php
    use App\EmpresaCliente;
    
    $nombre = $empresa!==null ? $empresa->nombre:'';
    $totalEmpleados= $empresa!== null ? $empresa->totalEmpleados:0;;
    $directos= $empresa!== null ? $empresa->totalEmpleadosDirectos:0;
    $temporales= $empresa!== null ? $empresa->totalEmpleadosTemporales:0;
    $servicios= $empresa!== null ? $empresa->totalEmpleadosPrestServicios:0;
    $jornada= $empresa!== null ? $empresa->jornadaTrabajo:'';
    $llegada= $empresa!== null ? $empresa->horaLlegada:'';
    $salida= $empresa!== null ? $empresa->horaSalida:'';
    $almuerzo= $empresa!== null ? $empresa->horasAlmuerzo:'';
    $sumaEmpleados = $directos + $temporales + $servicios;
   
?>   
<br/>
<div class="row">
    <div class="columns small-12 text-center">
        <h4>Crear Empresa - {{ strtoupper($nombre) }}</h4>
    </div>
    <fieldset class="fieldset">
        <legend class="text-center" style="font-size:18px;"><b>Empledos y Jornada Laboral</b></legend>
        @include('analissta.Asesores.crearEmpresa.errors')
        <div style="font-size:14px">
            <form method="POST" name="frm-crearNueva-Empresa" enctype="multipart/form-data" action="{{ route('crear-empleados-jornada')}}">
                {{ csrf_field() }}
                <div class="row text-center">
                        <div class="columns small-6 small-centered ">
                            <div class="callout success ">
                                <div><i class="fi-info" style="font-size: 28px;color:orange"></i></div>
                                <div>Para poder continuar, la suma de </div>
                                <div>Empleados Directos + Temporales + Prestación de Servicios </div>
                                <div>debe ser igual a  <span id="span-totalEmpleados">{{ $totalEmpleados }}</span> empleados</div>
                            </div>
                        </div>
                    </div>
                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="directos" class="text-right middle"><b>Total Empleados Directos:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input class="input-empleados" required="true" type="number" id="directos" name="directos" placeholder="# Empleados Directos" min="0"  step="1" value="{{ $directos }}"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="temporales" class="text-right middle"><b>Total Empleados Temporales:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input class="input-empleados" required="true" type="number" id="temporales" name="temporales" placeholder="# Empleados Temporales" min="0"  step="1" value="{{ $temporales }}"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="servicios" class="text-right middle"><b>Total Empleados Prestación de Servicios:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input class="input-empleados" required="true" type="number" id="servicios" name="servicios" placeholder="# Empleados Prestación de Servicios" min="0"  step="1" value="{{ $servicios }}"/>
                        </div>
                    </div>
                    
                </div>


                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="jornada" class="text-right middle"><b>Jornada Laboral**</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select class="input-required" required="true" id="jornada" name="jornada" placeholder="Jornada Laboral">
                                <option value="">Seleccione..</option>
                                <option value="Lunes a Viernes" <?php echo $jornada === 'Lunes a Viernes'?'selected':''?> >Lunes a Viernes</option>
                                <option value="Lunes a Sabado" <?php echo $jornada === 'Lunes a Sabado'?'selected':''?>>Lunes a Sabado</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="inicio" class="text-right middle"><b>Hora de Llegada**</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input class="input-required" required="true" type="time" id="inicio" name="inicio" placeholder="Hora de Inicio" value="{{ $llegada }}"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="salida" class="text-right middle"><b>Hora de Salida**</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input class="input-required" required="true" type="time" id="salida" name="salida" placeholder="Hora de Salida" value="{{ $salida }}"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="salida" class="text-right middle"><b>Horas de Almuerzo**</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select class="input-required" required="true" name="almuerzo">
                                <option value="">Seleccione..</option>
                                <option value="0.5" <?php echo $almuerzo === '0.5'?'selected':''?>>30 minutos</option>
                                <option value="1" <?php echo $almuerzo === '1'?'selected':''?>>1 Hora</option>
                                <option value="2" <?php echo $almuerzo === '2'?'selected':''?>>2 Horas</option>
                                <option value="3" <?php echo $almuerzo === '3'?'selected':''?>>3 Horas</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 text-center">
                            <b><i>(**) Según lo estipulado en el Reglamento Interno de Trabajo.</i></b>
                        </div>
                    </div>   
                </div>
                <div class="columns small-12 text-center">
                    <a class="button small" href="{{ route('contexto-general')}}">Anterior</a>
                    <a class="button small alert" data-open="modal-confirm-borrarEmpresa">Cancelar</a>
                    <input type="submit" id="btn-siguiente-paso2" class="button success small <?php echo (int)$totalEmpleados !== (int)$sumaEmpleados ? "hide":''?>"  value="Siguiente"/>
                </div>
            </form>
        </div>
    </fieldset>
</div>
@include('analissta.Asesores.crearEmpresa.modalCancelar')

<script>
    $(".input-empleados").on("change",function(){
       var totalEmpleados = parseFloat($("#span-totalEmpleados").text());
       var totalConfigurados = 0;
       $("#btn-siguiente-paso2").addClass("hide");
       $(".input-empleados").each(function(){
           totalConfigurados += parseFloat($(this).val());
       });
       if(totalEmpleados === totalConfigurados){
         $("#btn-siguiente-paso2").removeClass("hide");
       }
    });

</script>
@endsection


