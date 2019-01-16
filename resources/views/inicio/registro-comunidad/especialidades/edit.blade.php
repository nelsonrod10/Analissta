@extends('inicio.layouts.app')
<?php
    $xml_especialidades = simplexml_load_file(base_path("archivosXML/Comunidad/especialidades.xml"));
    $categoria = $xml_especialidades->xpath("//comunidad/categoria[@id=$especialidad->categoria]");
    $especialidades = $xml_especialidades->xpath("//comunidad/categoria[@id=$especialidad->categoria]/especialidades/item");
    $arrEspecialidades = explode(",",$especialidad->especialidades);
    
    $action ="";
    if($tipo === "Profesional"){
        $action="especialidades-profesionales.update";
    }
    
    if($tipo === "Empresa"){
        $action="especialidades-empresas.update";
    }
?>

@section('content')
    <br/><br/><br/>
    <section class="mbr-section content5" id="content7-2d" data-rv-view="36" >

        <div class="mbr-overlay" style="opacity: 0; background-color: #f6f8f5"></div>
        @if ($errors)
            @include('inicio.layouts.errors')
        @endif
        <div class="container">
            <div class="row heading">
                <h5 class="col-sm-12 text-center">Editar Especialidad</h5>
            </div>
            <form method="POST" action="{{route($action,$especialidad)}}">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-md-offset-2">
                        <b>Categoria: </b>{{$categoria[0]->nombre}}
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-md-offset-2 text-center ">
                        <div><b>Especialidades</b></div>
                        <div class="help-text">(Seleccione las especialidades)</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-10 col-md-offset-1 ">
                        @foreach($especialidades as $especialidadXML)
                            <div class="col-sm-12 col-md-6">
                                <input type="checkbox" id="{{$especialidadXML->attributes()["id"]}}" name="especialidades[]" value="{{$especialidadXML->attributes()["id"]}}" <?php echo (in_array($especialidadXML->attributes()["id"], $arrEspecialidades))?"checked":""?>/>
                                <label for="{{$especialidadXML->attributes()["id"]}}" style="font-weight: normal">{{ucfirst(strtolower($especialidadXML))}}</label>
                            </div>
                        @endforeach
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-md-offset-2 text-right">
                        <a class="btn btn-danger" href="{{url()->previous()}}">Cancelar</a>
                        <input type="submit" class="btn btn-success" value="Guardar"/>
                    </div>
                </div>
            </form>
            
        </div>    
    </section>
@endsection