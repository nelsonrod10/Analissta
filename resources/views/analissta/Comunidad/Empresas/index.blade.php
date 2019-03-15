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
                <div><b>COMUNIDAD EMPRESAS</b></div>
            </div>
            
            <div class="expanded button-group">
                <a class="button" href="{{ route('inicio')}}">Volver a Inicio</a>
                <a class="button warning" href="{{ route('comunidad-analissta.index')}}">Inicio Comunidad</a>
                <a class="button success-2" href="{{route('analissta-profesionales.index')}}">Comunidad Profesionales</a>
            </div>
        </div>
        
        <div class="columns small-12">
            @foreach($empresas_comunidad as $empresa_comunidad)
            <div class="row" style="border:2px solid lightgray; margin-bottom: 20px">
                <div class="columns small-12"><h4><b>{{ucfirst(strtolower($empresa_comunidad->nombre))}}</b></h4></div>
                <div class="columns small-12 medium-6">
                    <p>
                        <b>NIT: </b>{{$empresa_comunidad->identificacion}}
                    </p>
                    <p>
                        <b>CIUDAD: </b>{{$empresa_comunidad->ciudad}}
                    </p>
                    <p>
                        <b>WEB: </b>{{$empresa_comunidad->web}}
                    </p>
                    
                </div>
                <div class="columns small-12 medium-6">
                    <p>
                        <b>EMAIL: </b>{{$empresa_comunidad->email}}
                    </p>
                    <p>
                        <b>TELEFONO: </b>{{$empresa_comunidad->telefono}}
                    </p>
                    <p>
                        <b>LICENCIA: </b><?php echo ($empresa_comunidad->licencia == null )?"No Reporta": $empresa_comunidad->licencia?>
                    </p>
                </div>
                <div class="columns small-12 medium-10 end">
                    <label><b>PERFIL</b></label>
                    <textarea readonly=""><?php echo ($empresa_comunidad->perfil == null )?"No Reporta": $empresa_comunidad->perfil?></textarea>
                </div>
                <div class="columns small-12">
                    <h5><b>Especialidades</b></h5>
                </div>
                <div class="columns small-12">
                    @foreach($empresa_comunidad->Especialidades as $especialidades)
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