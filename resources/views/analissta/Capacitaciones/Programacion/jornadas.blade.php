@extends('analissta.layouts.appProgramarMedida')

<?php
use App\Http\Controllers\helpers;
use App\CapacitacionesCalendario;
use App\CentrosTrabajo;
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
            <h6><b>JORNADAS</b></h6>
        </div>
    </div>
    <br/>
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row text-center">
        <div class="columns small-12 medium-8 small-centered">
            <?php
                $totalInvitados=$totalPoblacion=0;
                $arrCentros = explode(",", $arrDataCentros);
                $newArr = array_chunk($arrCentros, 3);
                for($i=0;$i<count($newArr);$i++):
                    $nombreCentro = CentrosTrabajo::find($newArr[$i][0]);
                    $jornadas = CapacitacionesCalendario::where('sistema_id',$sistema->id)
                            ->where('tipo',$tipoCapacitacion)
                            ->where('centroTrabajo_id',$newArr[$i][0])
                            ->where('capacitacion_id',$capacitacion->id)
                            ->where('anio', helpers::getCurrentYear())
                            ->get();
            ?>
            <div class="row">
                <div class="row columns text-center" style="background-color:grey; color:white">
                    <b>{{$nombreCentro->nombre}}</b>
                </div>
                <div class="row show-for-medium text-center">
                    <div class="columns medium-4"><b style="text-decoration:underline">Mes</b></div>
                    <div class="columns medium-4"><b style="text-decoration:underline">Semana</b></div>
                    <div class="columns medium-4"><b style="text-decoration:underline">Invitados</b></div>
                </div>
                
                <?php
                    $invitados=0;
                    if(count($jornadas) > 0 ):
                        foreach ($jornadas as $jornada):
                        $invitados += $jornada->invitados;
                ?>
                <div class="row text-center">
                    <div class="columns medium-4">{{$jornada->mes}}</div>
                    <div class="columns medium-4">{{$jornada->semana}}</div>
                    <div class="columns medium-4">{{$jornada->invitados}}</div>
                </div>
                    
                <?php
                        endforeach;
                    else:
                ?>
                    <div class="row columns text-center">
                        <i>No hay jornadas para este centro de trabajo</i>
                    </div>
                <?php
                    endif;
                ?>
                <div class="columns small-12 callout success hide" id="frm-jornadas-centro-{{$newArr[$i][0]}}" >
                    <form method="post" name="frmJornadasCapacitacion-{{$i}}" action="{{route('jornadas-capacitacion')}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="tipo" value="{{$tipoCapacitacion}}"/>
                        <input type="hidden" name="idCapacitacion" value="{{$capacitacion->id}}"/>
                        <input type="hidden" name="centro" value="{{$newArr[$i][0]}}"/>
                        <input type="hidden" name="responsable" value="{{$newArr[$i][1]}}"/>
                        <input type="hidden" name="poblacion" value="{{$newArr[$i][2]}}"/>
                        <input type="hidden" name="arrDataCentros" value="{{$arrDataCentros}}"/>
                        <div class="row columns text-center">
                            <b>AGREGAR JORNADA</b>
                        </div>

                        <div class="columns small-12 medium-4">
                            <div class="columns small-12 text-center">
                                <b style="text-decoration:underline">Mes</b>
                            </div>
                            <select name="mes" required="true">
                                <option value="">Seleccione</option>
                                <?php 
                                for($i1=0;$i1<=11;$i1++):
                                    $textoMes = helpers::meses_de_numero_a_texto($i1);
                                ?>  
                                    <option value="{{$i1}}">{{$textoMes}}</option>
                                <?php    
                                endfor;    
                                ?>
                            </select>
                        </div>
                        <div class="columns small-12 medium-4">
                            <div class="columns small-12 text-center">
                                <b style="text-decoration:underline">Semana </b>
                            </div>
                            <select name="semana" required="true">
                                <option value="">Seleccione</option>
                                <option value="1">Semana 1</option>
                                <option value="2">Semana 2</option>
                                <option value="3">Semana 3</option>
                                <option value="4">Semana 4</option>
                            </select>
                        </div>
                        <div class="columns small-12 medium-4">
                            <div class="columns small-12 text-center">
                                <b style="text-decoration:underline">Población a Invitar</b>
                            </div>
                            <input min="1" max="{{$newArr[$i][2] - $invitados}}" type="number" id="invitados" name="invitados" required="true" placeholder="Numero de asistentes a esta jornada"/>
                        </div>
                        <div class="row text-center">
                            <div class="columns small-12">
                                <a id="{{$newArr[$i][0]}}" class="button small alert btn-cancelar-jornada-centro">Cancelar</a>
                                <input type="submit" class="button small" value="Agregar"/>
                            </div>
                        </div>
                    </form>
                </div>
                <br/>
                
                <div class="columns small-12 medium-6 text-left">
                    <div class="columns small-12"><b>Población Total: </b>  {{$newArr[$i][2]}} Personas</div>
                    <div class="columns small-12"><b>Población Programada: </b>{{$invitados}} Personas</div>
                    <div class="columns small-12"><b>Pendientes por Programar: </b>{{$newArr[$i][2] - $invitados}} Personas</div>
                </div>
                <div class="columns small-12 medium-6 text-right">
                    @if(($newArr[$i][2] - $invitados)>0)
                    <a id="{{$newArr[$i][0]}}" class="button small success-2 btn-agregar-jornada-centro">
                        <i class="fi-plus"></i> Agregar Jornada
                    </a>
                    @endif
                </div>      
            </div>        
            <br/>
            <?php
                $totalPoblacion += $newArr[$i][2];
                $totalInvitados +=$invitados;
            endfor;
            ?>
            
            @if($totalInvitados !== $totalPoblacion)
            <br/>
            <div class="row columns text-center">
                <i style="color:red">Para poder continuar debe crear todas las jornadas</i>
            </div>
            @endif
            <br/>
            <div class="row columns text-center">
                <a class="button small" href="{{ route('ver-programacion-capacitacion',['id'=>$capacitacion->id,'tipo'=>$tipoCapacitacion])}}">Anterior</a>
                <a class="button small alert" data-open="reveal-cancelar-programacion-capacitacion">Cancelar</a>
                @if($totalInvitados == $totalPoblacion)
                <a class="button small success-2" href="{{route('programacion-presupuesto-capacitacion',['id'=>$capacitacion->id,'tipo'=>$tipoCapacitacion,'arrDataCentros'=>$arrDataCentros])}}">Siguiente</a>
                @endif
            </div>    
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
           
           $(".btn-agregar-jornada-centro").on("click",function(){
              var id = $(this).attr("id");
              $("#frm-jornadas-centro-"+id).removeClass("hide");
           });
           
           $(".btn-cancelar-jornada-centro").on("click",function(){
              var id = $(this).attr("id");
              $("#frm-jornadas-centro-"+id).addClass("hide");
           });
        });
    </script>
@endsection

