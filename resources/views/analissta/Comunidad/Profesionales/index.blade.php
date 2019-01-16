@extends('analissta.layouts.app')

@section('content')
<?php 
    use App\Http\Controllers\helpers;
    $xml_especialidades = simplexml_load_file(base_path("archivosXML/Comunidad/especialidades.xml"));
?>
<br/>

<div class="row">
    <div class="columns small-12 callout">
        
        <div class="columns small-12">
            @include('analissta.layouts.encabezadoEmpresaCliente')
            <div class="row columns text-center" style="background:#0c4d78; color: white; font-size: 20px">
                <div><b>COMUNIDAD PROFESIONALES</b></div>
            </div>
            
            <div class="expanded button-group">
                <a class="button" href="{{ route('inicio')}}">Volver a Inicio</a>
                <a class="button warning" href="{{ route('comunidad-analissta.index')}}">Inicio Comunidad</a>
                <a class="button success-2" href="{{route('analissta-empresas.index')}}">Comunidad Empresas</a>
            </div>
        </div>
        
        <div class="columns small-12">
            @foreach($profesionales as $profesional)
            <div class="row" style="border:2px solid lightgray; margin-bottom: 20px">
                <div class="columns small-12"><h4><b>{{ucfirst(strtolower($profesional->nombre))}}</b></h4></div>
                <div class="columns small-12 medium-6">
                    <p>
                        <b>PROFESION: </b><?php echo ($profesional->profesion == null )?"No Reporta": $profesional->profesion?>
                    </p>
                    <p>
                        <b>CIUDAD: </b>{{$profesional->ciudad}}
                    </p>
                </div>
                <div class="columns small-12 medium-6">
                    <p>
                        <b>EMAIL: </b>{{$profesional->email}}
                    </p>
                    <p>
                        <b>TELEFONO: </b>{{$profesional->telefono}}
                    </p>
                    <p>
                        <b>LICENCIA: </b><?php echo ($profesional->licencia == null )?"No Reporta": $profesional->licencia?>
                    </p>
                </div>
                <div class="columns small-12 medium-10 end">
                    <label><b>PERFIL</b></label>
                    <textarea readonly=""><?php echo ($profesional->perfil == null )?"No Reporta": $profesional->perfil?></textarea>
                </div>
                <div class="columns small-12">
                    <h5><b>Especialidades</b></h5>
                </div>
                <div class="columns small-12">
                    @foreach($profesional->Especialidades as $especialidades)
                        <?php
                            $nombreCategoria = $xml_especialidades->xpath("//comunidad/categoria[@id=$especialidades->categoria]");
                            
                            $arrEspecialidades = explode(",",$especialidades->especialidades);
                        ?>
                        <div class="row" style="padding: 10px; border-bottom:1px solid lightgrey">
                            <div class="columns small-12">
                                <b style="text-decoration: underline">{{strtoupper($nombreCategoria[0]->nombre)}}</b>
                            </div>
                            <div class="columns small-12">
                                <ul>
                                    @foreach($arrEspecialidades as $especialidad)
                                    <?php
                                        
                                        $nombreEspecialidad = $xml_especialidades->xpath("//comunidad/categoria[@id=$especialidades->categoria]/especialidades/item[@id=$especialidad]");
                                       
                                    ?>
                                    <li>
                                        @foreach($nombreEspecialidad as $nombre)
                                        {{$nombre}}
                                        @endforeach
                                       
                                    </li>
                                    @endforeach
                                </ul>

                            </div>
                        </div>
                    @endforeach
                </div>
                
            </div>
            
            @endforeach
        </div>
        
    </div>
</div>    
@endsection