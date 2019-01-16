@extends('analissta.layouts.appProgramarMedida')

<?php
use App\Http\Controllers\helpers;
use App\ActividadesCalendario;
?>
@section('content')
    @section('titulo-encabezado')
        Programación Actividad
    @endsection
    @section('titulo-programacion')
        {{$actividad->nombre}}
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
            <form method="post" id="frmProgramarActividad" name="frmProgramarActividad" action="{{route('programacion-actividad')}}">
                {{ csrf_field() }}
                <input type="hidden" name="tipo" value="{{$tipoActividad}}"/>
                <input type="hidden" name="idActividad" value="{{$actividad->id}}"/>
                <div class="row">
                    <div class="columns small-12 medium-3"><b>Frecuencia de ejecución: </b></div>
                    <div class="columns small-12 medium-6 end">
                        <select name="frecuencia" required="true" >
                            <option value="">Seleccione</option>
                            <option value="semanal" <?php echo ($actividad->frecuencia === "semanal")?"selected":""?>>Semanal</option>
                            <option value="quincenal" <?php echo ($actividad->frecuencia === "quincenal")?"selected":""?>>Quincenal</option>
                            <option value="mensual" <?php echo ($actividad->frecuencia === "mensual")?"selected":""?>>Mensual (Cada mes)</option>
                            <option value="bimestral" <?php echo ($actividad->frecuencia === "bimestral")?"selected":""?>>Bimestral (Cada 2 meses)</option>
                            <option value="trimestral" <?php echo ($actividad->frecuencia === "trimestral")?"selected":""?>>Trimestral (Cada 3 meses)</option>
                            <option value="cuatrimestral" <?php echo ($actividad->frecuencia === "cuatrimestral")?"selected":""?>>Cuatrimestral (Cada 4 meses)</option>
                            <option value="semestral" <?php echo ($actividad->frecuencia === "semestral")?"selected":""?>>Semestral (Cada 6 meses)</option>
                            <option value="anual" <?php echo ($actividad->frecuencia === "anual")?"selected":""?>>Anual</option>
                        </select>
                    </div>
                </div>
                <div class="row columns text-center">
                    <h5><b>Centros de Trabajo</b></h5>
                    <i> (Seleccione los centros de trabajo en donde va a realizar la actividad)</i>
                </div>
                @foreach($sistema->centrosTrabajo as $centro)
                    <?php
                        $calendariobd = ActividadesCalendario::where('sistema_id', $sistema->id)
                                ->where('centroTrabajo_id',$centro->id)
                                ->where('actividad_id',$actividad->id)
                                ->where('tipo',$tipoActividad)
                                ->first();
                    ?>
                    <div class="row">
                        <div class="columns small-12 text-left">
                            <input type="checkbox" id="{{$centro->id}}" name="dataCentros[]" class="check-box-centro" value="{{$centro->id}}" <?php echo (isset($calendariobd->centroTrabajo_id) && ($calendariobd->centroTrabajo_id === $centro->id))?"checked":"" ?>/>
                            <label  for="{{$centro->id}}"><h6> {{$centro->nombre}}</h6></label>
                        </div>    
                    </div>
                    <div class="row fieldset text-left hide" id="fieldset-{{$centro->id}}">
                        <div class="columns small-12 medium-3"><b>Nombre Empleado: </b></div>
                        <div class="columns small-12 medium-6 end">
                            <input type="text" class="data-centro-{{$centro->id}}" placeholder="Persona que realiza la actividad ejm: Juan Perez" value="<?php echo (isset($calendariobd->centroTrabajo_id))?$calendariobd->responsable:"" ?>"/>
                        </div>
                        <div class="columns small-12">
                            <div><b class="">Fecha de Inicio </b></div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 text-center">
                                <i>En que mes y semana se iniciará</i>
                            </div>
                            <div class="columns small-12 medium-6">
                                <div class="row">
                                    <div class="columns small-4 text-right"><b>Mes: </b></div>
                                    <div class="columns small-8">
                                        <select class="data-centro-{{$centro->id}}" >
                                            <option value="">Seleccione</option>
                                        <?php 
                                        
                                        for($i=0;$i<=11;$i++):
                                            $textoMes = helpers::meses_de_numero_a_texto($i);
                                        ?>  
                                            <option value="{{$i}}" <?php echo (isset($calendariobd->centroTrabajo_id) && ($calendariobd->mes_inicio === $textoMes))?"selected":"" ?>>{{ $textoMes}}</option>
                                        <?php    
                                        endfor;    
                                        ?>
                                            <!--
                                            (getdate()["mon"]-1)
                                            -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="columns small-12 medium-6">
                                <div class="row">
                                    <div class="columns small-4 text-right"><b>Semana: </b></div>
                                    <div class="columns small-8 end">
                                        <select class="data-centro-{{$centro->id}}" >
                                            <option value="">Seleccione</option>
                                            <option value="1" <?php echo (isset($calendariobd->centroTrabajo_id) && ($calendariobd->semana_inicio === "1"))?"selected":"" ?>>Semana 1</option>
                                            <option value="2" <?php echo (isset($calendariobd->centroTrabajo_id) && ($calendariobd->semana_inicio === "2"))?"selected":"" ?>>Semana 2</option>
                                            <option value="3" <?php echo (isset($calendariobd->centroTrabajo_id) && ($calendariobd->semana_inicio === "3"))?"selected":"" ?>>Semana 3</option>
                                            <option value="4" <?php echo (isset($calendariobd->centroTrabajo_id) && ($calendariobd->semana_inicio === "4"))?"selected":"" ?>>Semana 4</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <br/>
                <div class="row columns text-center">
                    <a class="button small" href="{{ route('programar-actividad',['id'=>$actividad->id,'tipo'=>$tipoActividad])}}">Anterior</a>
                    <a class="button small alert" data-open="reveal-cancelar-programacion-actividad">Cancelar</a>
                    <input type="submit" class="button small success" value="Siguiente"/>
                </div>    
            </form>
        </div>
    </div>
    @include('analissta.Actividades.Programacion.modalCancelar')
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
           
           $("#frmProgramarActividad").on("submit", function(e){
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

