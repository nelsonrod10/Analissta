@extends('analissta.layouts.appProgramarMedida')

<?php
use App\Http\Controllers\helpers;
use App\CapacitacionesCalendario;
?>
@section('content')
    @section('titulo-encabezado')
        Programación Capacitación
    @endsection
    @section('titulo-programacion')
        {{$capacitacion->nombre}}
    @endsection
    <div class="row text-center">
        <div class="columns small-12 medium-8 small-centered label secondary">
            <h6><b>PROGRAMACION</b></h6>
        </div>
    </div>
    <br/>
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row text-center">
        <div class="columns small-12 medium-8 small-centered">
            <form method="post" id="frmProgramarCapacitacion" name="frmProgramarCapacitacion" action="{{route('programacion-capacitacion')}}">
                {{ csrf_field() }}
                <input type="hidden" name="tipo" value="{{$tipoCapacitacion}}"/>
                <input type="hidden" name="idCapacitacion" value="{{$capacitacion->id}}"/>
                <div class="columns small-12 medium-6 small-centered end">
                    <div class="row">
                        <div class="columns small-12 medium-3">¿Evaluable?: </div>
                        <div class="columns small-12 medium-9">
                            <input id="evaluable-si" type="radio" name="evaluable" required="true" value="Si" <?php echo ($capacitacion->evaluable == "Si")?"Checked":""?>/>
                            <label for="evaluable-si">SI</label>
                            <input id="evaluable-no" type="radio" name="evaluable" required="true" value="No"  <?php echo ($capacitacion->evaluable == "No")?"Checked":""?>/>
                            <label for="evaluable-no">NO</label>
                        </div>
                    </div>
                </div>
                
                <div class="columns small-12 medium-6 small-centered end">
                    <div class="row">
                        <div class="columns small-12 medium-3">Capacitador: </div>
                        <div class="columns small-12 medium-9">
                            <input id="interno" type="radio" name="capacitador" required="true" value="Interno" <?php echo ($capacitacion->capacitador == "Interno")?"Checked":""?>/>
                            <label for="interno">Interno</label>
                            <input id="externo" type="radio" name="capacitador" required="true" value="Externo" <?php echo ($capacitacion->capacitador == "Externo")?"Checked":""?>/>
                            <label for="externo">Externo</label>
                        </div>
                    </div>
                </div>    
                <br/>
                <div class="row columns text-center">
                    <h5><b>Centros de Trabajo</b></h5>
                    <i> (Seleccione los centros de trabajo en donde va a realizar la capacitacion)</i>
                </div>
                @foreach($sistema->centrosTrabajo as $centro)
                    <?php
                        $calendariobd = CapacitacionesCalendario::where('sistema_id', $sistema->id)
                                ->where('centroTrabajo_id',$centro->id)
                                ->where('capacitacion_id',$capacitacion->id)
                                ->where('tipo',$tipoCapacitacion)
                                ->first();
                    ?>
                
                    <div class="row">
                        <div class="columns small-12 text-left">
                            <input type="checkbox" id="{{$centro->id}}" name="dataCentros[]" class="check-box-centro" value="{{$centro->id}}" <?php echo (isset($calendariobd->centroTrabajo_id) && ($calendariobd->centroTrabajo_id === $centro->id))?"checked":"" ?>/>
                            <label  for="{{$centro->id}}"><h6> {{$centro->nombre}}</h6></label>
                        </div>    
                    </div>
                    <div class="row fieldset text-left hide" id="fieldset-{{$centro->id}}">
                        <div class="row">
                            <div class="columns small-12 medium-3"><b>Nombre Empleado: </b></div>
                            <div class="columns small-12 medium-6 end">
                                <input type="text" class="data-centro-{{$centro->id}}" placeholder="Persona que realiza la capacitacion ejm: Juan Perez" value="<?php echo (isset($calendariobd->centroTrabajo_id))?$calendariobd->responsable:"" ?>"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-3"><b>Población Objetivo: </b></div>
                            <div class="columns small-12 medium-6 end">
                                <input type="number" list="maximo-empleados-centro{{$centro->id}}" class="data-centro-{{$centro->id}}" placeholder="# de personas a capacitar" value="<?php echo (isset($calendariobd->centroTrabajo_id))?$calendariobd->poblacion_objetivo:"" ?>"/>
                                <datalist id="maximo-empleados-centro{{$centro->id}}">
                                    <option value="{{$centro->totalEmpleados}}">Empleados centro {{$centro->nombre}}</option>
                                </datalist>
                            </div>
                        </div>
                        
                    </div>
                @endforeach

                <br/>
                <div class="row columns text-center">
                    <a class="button small" href="{{ route('programar-capacitacion',['id'=>$capacitacion->id,'tipo'=>$tipoCapacitacion])}}">Anterior</a>
                    <a class="button small alert" data-open="reveal-cancelar-programacion-capacitacion">Cancelar</a>
                    <input type="submit" class="button small success" value="Siguiente"/>
                </div>    
            </form>
        </div>
    </div>
    @include('analissta.Capacitaciones.Programacion.modalCancelar')
    <script>    
        $(document).ready(function(){
           //para cuando se reinia la pagina
           $(".check-box-centro").each(function(){
               if($(this).is(":checked")){
                   var id= $(this).attr('id');
                   $("#fieldset-"+id).removeClass("hide");
                   
                   $(".data-centro-"+id).each(function(){
                        $(this).attr("required","");
                        $(this).attr("name","dataCentros[]");
                   });
               }
           });
           
           $(".check-box-centro").on("click", function(){
               var id= $(this).attr('id');
               $(".data-centro-"+id).each(function(){
                   $(this).removeAttr("required");
                   $(this).removeAttr("name");
               });
               $("#fieldset-"+id).addClass("hide");
               if($(this).is(':checked')){
                   $("#fieldset-"+id).removeClass("hide");
                   
                   $(".data-centro-"+id).each(function(){
                        $(this).attr("required","");
                        $(this).attr("name","dataCentros[]");
                   });
               }
           });
           
           $("#frmProgramarCapacitacion").on("submit", function(e){
               var flag= 0;
               var flagInputs=0;
               $(".check-box-centro").each(function(){
                  if($(this).is(":checked")){
                      flag = 1;
                      var id= $(this).attr('id');
                      $(".data-centro-"+id).each(function(){
                            if($(this).val() === ""){
                                flagInputs=1;
                            };
                      });
                  } 
               });
               
               if(flag === 0){
                   alert("Debe seleccionar por lo menos un centro de trabajo");
                   e.preventDefault();
               }
               if(flagInputs === 1){
                   alert("Debe diligenciar los campos del centro de trabajo");
                   e.preventDefault();
               }
           });
        });
    </script>
@endsection

