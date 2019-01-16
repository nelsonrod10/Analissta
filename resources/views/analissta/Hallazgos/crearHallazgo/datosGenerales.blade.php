@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\Http\Controllers\helpers;
    use App\Hallazgos\Hallazgo;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    $xml_origenes = simplexml_load_file(base_path("archivosXML/Hallazgos/xml_Origenes.xml"));
    $origenes = $xml_origenes->xpath("//origenes/origen[@id != 2 and @id != 3]");
    
    if(isset($idHallazgo)){
        $hallazgoBD = Hallazgo::find($idHallazgo);
    }
?>
@section('content')
    @section('titulo-encabezado')
        Crear Nuevo Hallazgo 
    @endsection
    <div class="row text-center">
        <div class="columns small-12 medium-8 small-centered label secondary">
            <h6><b>DATOS GENERALES</b></h6>
        </div>
    </div>
    <br/>
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row">
        <div class="columns small-12 medium-8 small-centered">
            <form method="post" name="datosGeneralesHallazgo" action="{{route('crear-datos-generales-hallazgo',["id"=>$idHallazgo])}}">
                {{ csrf_field() }}
                <div class="columns small-12 ">
                    <div class="row">
                        <div class="columns small-12 medium-3"><b>Origen del Hallazgo: </b></div>
                        <div class="columns small-12 medium-6 end">
                            <select name="origen" required="true">
                                <option value="">Seleccione una opción...</option>
                                @foreach ($origenes as $origen)
                                    <option value="{{$origen->attributes()['id']}}" <?php echo (isset($hallazgoBD))?($hallazgoBD->origen_id == $origen->attributes()['id'])?"selected":"":""?> >
                                        {{$origen->nombre}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><b>Centro Trabajo:</b></div>
                        <div class="columns small-12 medium-6 end">
                            <select name="centro" required="true">
                                <option value="">Seleccione un centro...</option>
                                @foreach ($sistema->centrosTrabajo as $centro)
                                    <option value="{{$centro->id}}" <?php echo (isset($hallazgoBD))?($hallazgoBD->centrosTrabajo_id == $centro->id)?"selected":"":""?> >
                                        {{$centro->nombre}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="columns small-12 medium-3"><b>Proceso: </b></div>
                        <div class="columns small-12 medium-6 end">
                            <select name="proceso" required="true">
                                <option value="">Seleccione un proceso...</option>
                                @foreach ($sistema->procesos as $proceso)
                                    <option value="{{$proceso->id}}"  <?php echo (isset($hallazgoBD))?($hallazgoBD->proceso_id == $proceso->id)?"selected":"":""?> >
                                        {{$proceso->nombre}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <br/>
                        <div class="columns small-12 medium-3"><b class="middle">Fecha: </b></div>
                        <div class="columns small-12 medium-3 end">
                            <input type="date" class="input-required-paso-5" name="fecha" required="true" placeholder="Fecha de hallazgo" max="{{ $fechaActual }}" value='<?php echo (isset($hallazgoBD))? $hallazgoBD->fechaHallazgo :""?>'/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><b class="middle">Cargo Responsable: </b></div>
                        <div class="columns small-12 medium-4 end">
                            <input type="text" class="input-required-paso-5" name="responsable" required="true" placeholder="Cargo Responsable"  value='<?php echo (isset($hallazgoBD))? $hallazgoBD->cargoResponsable :""?>'/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 text-left">
                            <p><b>Descripción del Hallazgo</b></p>
                            <textarea name="descripcion" sytle="min-height:100px;" required="true" class="input-required-paso-5" placeholder="Describa Brevemente el hallazgo" style="min-height:100px; height:auto"><?php echo (isset($hallazgoBD))? $hallazgoBD->descripcion :""?></textarea>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row columns text-center">
                    <a class="button small alert" data-open="reveal-cancelar-crear">Cancelar</a>
                    <input type="submit" class="button small success" value="Siguiente"/>
                </div>    
            </form>
        </div>
    </div>
    @include('analissta.Hallazgos.crearHallazgo.modalCancelar')
@endsection