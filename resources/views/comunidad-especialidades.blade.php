@extends('inicio.layouts.app')
<?php
    $xml_especialidades = simplexml_load_file(base_path("archivosXML/Comunidad/especialidades.xml"));
    $categorias = $xml_especialidades->xpath("//comunidad/categoria");
    $actionCancelar=$actionEdit="";
    
    if($tipo === "Profesional"){
        $actionCancelar="comunidad-profesionales.destroy";
        $actionEdit="comunidad-profesionales.edit";
    }
    if($tipo === "Empresa"){
        $actionCancelar="comunidad-empresas.destroy";
        $actionEdit="comunidad-empresas.edit";
    }
?>

@section('content')
    <br/><br/><br/>
    <section class="mbr-section content5" id="content7-2d" data-rv-view="36" style="padding-bottom: 0px">

        <div class="mbr-overlay" style="opacity: 0; background-color: #f6f8f5"></div>

            <div class="container">
                <div class="row heading">
                    <div class="col-sm-12 col-md-10 col-md-offset-1">
                        <h1 class="mbr-section-title display-3 heading-title" style="color:#4b4c4a">Formulario de Registro</h1>
                        <p class="mbr-section-subtitle text-1 heading-text" style="color:#4b4c4a">
                            {{$nuevo_miembro->nombre}}, por último es necesario que nos digas en que temas eres un experto. Puedes seleccionar varias categorias y especialidades
                        </p>
                    </div>
                </div>
            </div>
    </section>
    <section class="mbr-section content5" id="content7-2d" data-rv-view="36" style="padding-top: 10px">
        <div class="mbr-overlay" style="opacity: 0.3; background-color: #f6f8f5"></div>
        @if ($errors)
            @include('inicio.layouts.errors')
        @endif
        @if($nuevo_miembro->Especialidades->count() > 0)
            <div class="container">
                <div class="row text-center">
                    <div class="col-sm-12" style="color:white;background: #025aa5">
                        <h4>Especialidades Creadas</h4>
                    </div>
                </div>    
                <div class="row text-center" style="border-bottom: 2px solid lightgrey">
                    <div class="col-sm-12 col-md-4">
                        <b>Categorias</b>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <b>Especialidades</b>
                    </div>
                    <div class="col-sm-12 col-md-4"></div>
                </div>
                @foreach($nuevo_miembro->Especialidades as $especialidades)
                    <?php
                        $nombreCategoria = $xml_especialidades->xpath("//comunidad/categoria[@id=$especialidades->categoria]");
                        $arrEspecialidades = explode(",",$especialidades->especialidades);
                    ?>
                    <div class="row" style="padding: 10px; border-bottom:1px solid lightgrey">
                        <div class="col-sm-12 col-md-4">
                            {{$nombreCategoria[0]->nombre}}
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <ul>
                                @foreach($arrEspecialidades as $especialidad)
                                <?php
                                    $nombreEspecialidad = $xml_especialidades->xpath("//comunidad/categoria[@id=$especialidades->categoria]/especialidades/item[@id=$especialidad]");
                                ?>
                                <li>
                                    {{ucfirst(strtolower($nombreEspecialidad[0]))}}
                                </li>
                                @endforeach
                            </ul>
                            
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <form method="GET" action="{{route('especialidades-comunidad.edit',$especialidades)}}">
                                <input type="hidden" hidden="" class="hide" name="tipo" value="{{$tipo}}"/>
                                <input type='submit' class="btn btn-primary" value='Editar'/>
                            </form>
                            
                            <form method="POST" action="{{route('especialidades-comunidad.destroy',$especialidades)}}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <input type="hidden" hidden="" class="hide" name="tipo" value="{{$tipo}}"/>
                                <input type='submit' class="btn btn-danger" value='Eliminar'/>
                            </form>
                        </div>
                    </div>
                @endforeach
                <div class="alert alert-success col-sm-12 col-md-8 col-md-offset-2" role="alert" style="opacity: 0.7">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="alert-heading">Felicitaciones!</h4>
                    <p>Ahora eres uno de los miembros de nuestra comunidad </p>
                    <hr>
                    <p class="mb-0">Si deseas agregar más especialidades, simplemente selecciona más categorias y especialidades</p>
                    <br>
                    <div class="text-center">
                        <a class="btn btn-sm btn-black" href="{{url("finalizar-registro-comunidad")}}">Finalizar Registro</a>
                    </div>
                    
                </div>
            </div>    
        @endif
        <div class="container">
            <div class="row heading">
                <div class="col-sm-12">
                    <form id="frm-especialidades" name="especialidades" method="POST" action="{{route('especialidades-comunidad.store')}}">
                        {{ csrf_field() }}
                        <input type="hidden" hidden="" class="hide" name="miembro" value="{{$nuevo_miembro->id}}"/>
                        <input type="hidden" hidden="" class="hide" name="tipo" value="{{$tipo}}"/>
                        <div class="row form-group" id="row-categorias">
                            <label class="col-sm-12">1. Selecciona una Categoria</label>
                            @foreach($categorias as $categoria)
                                @if($nuevo_miembro->Especialidades->where('categoria',$categoria->attributes()['id'])->count()===0)
                                <div class="form-check col-sm-12 col-md-6">
                                    <input required="" class="categoria" type="radio" id="{{$categoria->attributes()['id']}}" name="categoria" value="{{$categoria->attributes()['id']}}" data-nombre="{{$categoria->nombre}}">
                                    <label style="font-weight: normal" for="{{$categoria->attributes()['id']}}">{{$categoria->nombre}}</label>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="row hide" id="row-especialidades">
                            <div class="col-sm-12" >
                                <label class="btn btn-info" id="label-categoria">Nombre Categ</label>
                                <a class="label label-danger btn-cambiar-categoria">Cambiar Categoria</a>
                            </div>
                            <div class="col-sm-12 form-group" >
                                <p>Ahora de la categoria seleccionada, dinos exactamente en que eres experto:</p>
                            </div>
                            <div class="col-sm-12 form-check" id="div-especialidades">
                            </div>
                            <div class="col-sm-12 text-center" >
                                
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <a href="{{route($actionEdit,$nuevo_miembro)}}" class="btn btn-info ">Atras</a>  
                                <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#exampleModalCenter">Cancelar</button>  
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Cancelar Registro</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{route($actionCancelar,$nuevo_miembro)}}">
                {{csrf_field()}}
                {{method_field("DELETE")}}
                <div class="modal-body">
                    <p>¿Está seguro de cancelar su registro?</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-black">Confirmar :(</button>
                </div>
            </form>      
          </div>
        </div>
    </div>
    @section('items-footer')
        <p class="card-text mbr-section-text text-2"><a href="{{url('/')}}" class="text-white">Inicio.</a><br><a href="{{url('/')}}#header1-2a" class="text-white">Beneficios.</a><br><a href="{{url('/')}}#features3-j" class="text-white">Modulos</a><br><a href="{{url('comunidad')}}" class="text-white">Comunidad</a><br><a href="{{url('/')}}#subscribe1-26" class="text-white">Contacto</a><br></p>
    @endsection
    <form method="POST" action="{{url('/especialidades-categoria/:categoria')}}" accept-charset="UTF-8" id="form-especialidades">
        {{ csrf_field() }}
    </form>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $(".categoria").on("click",function(e){
           buscarEspecialidades($(this).val(),$(this).attr("data-nombre"));
        });
        
        $(".btn-cambiar-categoria").on("click",function(e){
           $("#row-especialidades").addClass("hide");
           $("#row-categorias").removeClass("hide");
        });
        $("#frm-especialidades").on("submit",function(e){
           //alert("querias terminar??, lo siento");
           //e.preventDefault();
        });
        
    });
    function buscarEspecialidades(categoria,nombreCategoria){
        $(".info-precios").each(function(){
            $("#error-empleados").html("");
            $(this).removeClass("hidden");
        });

      var form = $('#form-especialidades');
      var url = form.attr('action').replace(':categoria',categoria); 
      var data = form.serialize();
      $.post(url,data,function(result){
          $("#row-categorias").addClass("hide");
          $("#label-categoria").html(nombreCategoria);
          $("#div-especialidades").html(result.especialidades);
          $("#row-especialidades").removeClass("hide");
          
      });
    }
</script>
@endsection