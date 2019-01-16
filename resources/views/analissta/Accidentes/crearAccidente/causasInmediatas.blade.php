@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\CausasBasicasInmediata;
    $xmlCausas = simplexml_load_file(base_path("archivosXML/Hallazgos/xml_Causas_Inmediatas.xml"));
    $factores = $xmlCausas->xpath("//causasInmediatas/factor");
    
    $causasBD = CausasBasicasInmediata::where('sistema_id',$sistema->id)
        ->where('origen_id',$idAccidente)
        ->where('origen_table','Accidentes')
        ->where('tipo','Inmediata')
        ->get();
    
    $arrFactoresCategoriasCausasBD=[];
    
    foreach ($causasBD as $value) {
        array_push($arrFactoresCategoriasCausasBD, $value->factor."-".$value->categoria);
    }
?>
@section('content')
    @section('titulo-encabezado')
        Reportar Nuevo Accidente 
    @endsection
    <div class="row text-center">
        <div class="columns small-12 small-centered label secondary">
            <h6><b>CAUSAS INMEDIATAS</b></h6>
        </div>
    </div>
        <div class="columns small-12  small-centered end">
            <div class="row columns text-center">
                <h6><b>Causas Seleccionadas</b></h6>
            </div>
            @if(count($causasBD)>0)
                <div class="row" id="causas-seleccionadas">
                    <div class="row columns text-center">
                        <a class="button small" onclick="$('#listado-causas-Inmediatas').removeClass('hide').show();$('#div-btns-siguientePaso').hide();$('#causas-seleccionadas').hide();">Agregar más Causas</a>
                    </div>
                    @foreach($causasBD as $causa)
                        @php 
                          $nombFactor = $xmlCausas->xpath("//causasInmediatas/factor[@factor=$causa->factor]");  
                          $nombCateg  = $xmlCausas->xpath("//causasInmediatas/factor[@factor=$causa->factor]/categoria[@id=$causa->categoria]");
                          $items1     = $xmlCausas->xpath("//causasInmediatas/factor[@factor=$causa->factor]/categoria[@id=$causa->categoria]/descripcion/item");
                          $arrCausas  = explode(",",$causa->descripcion);
                        @endphp
                    <div class="columns small-12 medium-7 small-centered end" data-equalizer>
                        <div class="cell medium-12" >
                            <div class="callout" data-equalizer-watch>
                                <div class="text-center" style="text-decoration: underline"><b>{{$nombCateg[0]->nombre}}</b></div>
                                <div class="">
                                    <ul  style="font-size: 12px">
                                        @foreach($arrCausas as $descCausa)
                                            @php
                                                $nombCausa  = $xmlCausas->xpath("//causasInmediatas/factor[@factor=$causa->factor]/categoria[@id=$causa->categoria]/descripcion/item[id=$descCausa]");
                                            @endphp
                                            <li>{{$nombCausa[0]->nombre}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="">
                                    <label><b>Observaciones</b></label>
                                    <textarea readonly="true" style="font-size: 12px">{{$causa->observaciones}}</textarea>
                                </div>
                                <div class="text-center">
                                    <a class="button small warning" data-open="modificar-{{$causa->factor}}-{{$causa->categoria}}">Modificar</a>
                                    <a class="button small alert" href="{{route('eliminar-causa-inmediata-accidente',['idAccidente'=>$idAccidente,'idCausa'=>$causa->id])}}">Eliminar</a>
                                </div>
                            </div>
                        </div>
                    </div>
        <!--**************REVEAL PARA MODIFICAR UNA CAUSA INMEDIATA YA SELECCIONADA********************-->        
                    <div id='modificar-{{$causa->factor}}-{{$causa->categoria}}' class="reveal" data-reveal>
                        <div class="row columns text-center">
                            <h5>{{$nombCateg[0]->nombre}}</h5>
                            <i><b>Seleccione las causas inmediatas.</b></i>
                        </div>

                        <form name="frm-causas-inmediatas" method="post" action="{{route('crear-causas-inmediatas-accidente',['id'=>$idAccidente])}}" class="frm-causas-inmediatas" id="frm-modificar-{{$causa->factor}}-{{$causa->categoria}}" data-factor='{{$causa->factor}}' data-categoria='{{$causa->categoria}}'>
                            {{ csrf_field() }}
                            <input type="hidden" class="hide" name="factor" value="{{$causa->factor}}"/>
                            <input type="hidden" class="hide" name="categoria" value="{{$causa->categoria}}"/>
                            
                            @foreach($items1 as $item)
                            <div class="row">
                                <div class="columns small-1 text-right">
                                    <input type="checkbox" class="checkbox_{{$causa->factor}}_{{$causa->categoria}}" name="arrmedidas[]" id="modificar-check-{{$causa->factor}}-{{$causa->categoria}}-{{$item->id}}" value="{{$item->id}}" <?php echo (in_array($item->id, $arrCausas)?"checked":"")?>/>
                                </div>
                                <div class="columns small-11">
                                    <label for="modificar-check-{{$causa->factor}}-{{$causa->categoria}}-{{$item->id}}">{{$item->nombre}}</label>  
                                </div>
                            </div>
                            @endforeach
                            <br/>
                            <div class="row columns">
                                <div><b>Detalles importantes:</b></div>
                                <textarea name="detalles" placeholder="Puede escribir detalles que considere importantes sobre estas causas" style="min-height:100px;height: auto">{{$causa->observaciones}}</textarea>
                            </div>
                            <div class="row columns text-center">
                                <input type="submit" class="button small success" value="Agregar"/>
                                <a data-close class="button small alert">Cancelar</a>
                            </div>
                        </form>
                    </div>        
                    @endforeach
                </div>
            @else
            <br/>
            <div class="row columns text-center">
                <h6><i style="color: #cc0000">No existe ninguna causa inmediata, para poder continuar seleccione por lo menos una causa inmediata</i></h6>
            </div>
            @endif
            
            @include('analissta.Asesores.crearEmpresa.errors')
            <br/><br/>
            <div class="row columns hide"  id="listado-causas-Inmediatas">
                <div class="row columns text-center ">
                    <div><i>Seleccione las causas inmediatas que considere necesarias</i></div>
                    <div class="row columns small-12"><i><b class="msj-error-programarIntervencion" style="color:red"></b></i></div>
                </div>
                <div class="row">
                    @foreach($factores as $factor)
                        <div class="columns small-12 text-center" style="background:#999999;color:white"><b>{{strtoupper($factor->nombre)}}</b></div>
                        <?php
                            $attrFactor = $factor->attributes()['factor'];
                            $categorias = $xmlCausas->xpath("//causasInmediatas/factor[@factor=$attrFactor]/categoria");
                        ?>
                        @foreach($categorias as $categoria)
                            <?php
                                $idCategoria = $categoria->attributes()['id'];
                                $items = $xmlCausas->xpath("//causasInmediatas/factor[@factor=$attrFactor]/categoria[@id=$idCategoria]/descripcion/item");
                            ?>
                        <!--Si no esta en el array de la base de datos se muestra la información de la categoria-->   
                            @if(!in_array($attrFactor."-".$idCategoria, $arrFactoresCategoriasCausasBD))
                                <div class='columns small-6 medium-3'style='padding-bottom: 20px;'>
                                    <a style="text-decoration: underline" data-open='{{$attrFactor}}-{{$idCategoria}}'>{{$categoria->nombre}}</a>
                                </div>
                                <div id='{{$attrFactor}}-{{$idCategoria}}' class="reveal" data-reveal>
                                    <div class="row columns text-center">
                                        <h5>{{$categoria->nombre}}</h5>
                                        <i><b>Seleccione las causas inmediatas.</b></i>
                                    </div>

                                    <form name="frm-causas-inmediatas" method="post" action="{{route('crear-causas-inmediatas-accidente',['id'=>$idAccidente])}}" class="frm-causas-inmediatas" id="frm-{{$attrFactor}}-{{$idCategoria}}" data-factor='{{$attrFactor}}' data-categoria='{{$idCategoria}}'>
                                        {{ csrf_field() }}
                                        <input type="hidden" class="hide" name="factor" value="{{$attrFactor}}"/>
                                        <input type="hidden" class="hide" name="categoria" value="{{$idCategoria}}"/>
                                        @foreach($items as $item)
                                        <div class="row">
                                            <div class="columns small-1 text-right">
                                                <input type="checkbox" class="checkbox_{{$attrFactor}}_{{$idCategoria}}" name="arrmedidas[]" id="{{$attrFactor}}-{{$idCategoria}}-{{$item->id}}" value="{{$item->id}}"/>
                                            </div>
                                            <div class="columns small-11">
                                                <label for="{{$attrFactor}}-{{$idCategoria}}-{{$item->id}}">{{$item->nombre}}</label>  
                                            </div>
                                        </div>
                                        @endforeach
                                        <br/>
                                        <div class="row columns">
                                            <div><b>Detalles importantes:</b></div>
                                            <textarea name="detalles" placeholder="Puede escribir detalles que considere importantes sobre estas causas" style="min-height:100px;height: auto"></textarea>
                                        </div>
                                        <div class="row columns text-center">
                                            <input type="submit" class="button small success" value="Agregar"/>
                                            <a data-close class="button small alert">Cancelar</a>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
                <div class="row text-center">
                    <a class="button small" onclick="$('#listado-causas-Inmediatas').addClass('hide').hide();$('#div-btns-siguientePaso').show();;$('#causas-seleccionadas').show();">Terminar</a>
                </div>
            </div>
        </div>
        <div id="div-btns-siguientePaso" class="row columns text-center">
            <div class="row columns small-12 hide" id="div-alert-causas"><i><b style="color:red">No se han seleccionado Causas Inmediatas</b></i></div>
            <div class="row columns text-center">
                <a class="button small" onclick="$('#listado-causas-Inmediatas').removeClass('hide').show();$('#div-btns-siguientePaso').hide();$('#causas-seleccionadas').hide();">Agregar Causas</a>
            </div>
            <a class="button small" href="{{ route('peligro-asociado-accidente',['id'=>$idAccidente]) }}">Anterior</a>

            <a class="button small alert" data-open="reveal-cancelar-crear">Cancelar</a>
            @if(count($causasBD)>0)
                <a class="button small success" href="{{ route('causas-basicas-accidente',['id'=>$idAccidente])}}">Siguiente</a>
            @endif
            
        </div>
    <script>
        $(document).ready(function(){
           /*$(".frm-causas-inmediatas").on("submit",function(e){
               alert("jejeje");
              var factor=$(this).attr("data-factor");
              var categoria=$(this).attr("data-categoria");
              var flagCheck=0;
              $(".checkbox_"+factor+"_"+categoria).each(function(){
                  if($(this).is(":checked")){
                      flagCheck = 1;
                  }
              });
              if(flagCheck === 1){
                //quiere decir que algun check fue seleccionado
                //alert($(this).attr("id")+", "+factor+", "+categoria);
                var form = $('#frm-'+factor+"-"+categoria);
                var url = form.attr('action'); 
                var data = form.serialize();
                $.post(url,data,function(result){
                    alert(result);
                });
              }else{
                  alert("Debe seleccionar por lo menos una causa");
              }
              
              e.preventDefault();
           });*/
        });
    </script>
    
    @include('analissta.Accidentes.crearAccidente.modalCancelar')
@endsection