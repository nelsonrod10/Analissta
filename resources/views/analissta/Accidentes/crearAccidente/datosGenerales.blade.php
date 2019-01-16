@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\Http\Controllers\helpers;
    use App\Accidentes\Accidente;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    //$xml_origenes = simplexml_load_file(base_path("archivosXML/Accidentes/xml_Origenes.xml"));
    //$origenes = $xml_origenes->xpath("//origenes/origen[@id != 2]");
    
    if(isset($idAccidente)){
        $accidenteBD = Accidente::find($idAccidente);
    }
?>
@section('content')
    @section('titulo-encabezado')
        Reportar Nuevo Accidente
    @endsection
    <style>
            .titulo-origenes{
                font-size: 16px;
                font-weight: bold;
                color: #3c3737;
            }
            .div-descripcion{
                width: auto;
                height: auto;
                max-width: 100%;
                max-height: 25px;
                overflow: hidden;
                
            }
            .div-descripcion a{
                text-decoration: underline;
            }
            .warning-2{
                background: #f29c13;
                color:white;
            }
            .info-costos{
                font-size: 12px;
                background:#ccffcc;    
            }
        </style>
    <div class="row text-center">
        <div class="columns small-12 medium-8 small-centered label secondary">
            <h6><b>DATOS GENERALES</b></h6>
        </div>
    </div>
    <br/>
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row">
        <div class="columns small-12 medium-8 small-centered">
            <form method="post" name="datosGeneralesAccidente" action="{{route('crear-datos-generales-accidente',["id"=>$idAccidente])}}">
                {{ csrf_field() }}
                <div class="columns small-12 ">
                    <div class="row">
                        <div class="columns small-12 medium-3">
                            <b class="middle">Cargo Responsable: </b>
                            <div class="info-costos"><i>¿Quien va a estar al tanto de la investigación y seguimiento del accidente?</i></div>
                        </div>
                        <div class="columns small-12 medium-6 end">
                            <input type="text" name="cargo" required="true" placeholder="Cargo Responsable" value="<?php echo (isset($accidenteBD))?$accidenteBD->cargoResponsable:"" ?>"/>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="columns small-12 medium-3"><b>Centro Trabajo:</b></div>
                        <div class="columns small-12 medium-6 end">
                            <select name="centro" required="true">
                                <option value="">Seleccione un centro...</option>
                                @foreach ($sistema->centrosTrabajo as $centro)
                                    <option value="{{$centro->id}}" <?php echo (isset($accidenteBD))?($accidenteBD->centrosTrabajo_id == $centro->id)?"selected":"":""?> >
                                        {{$centro->nombre}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><b>Proceso: </b></div>
                        <div class="columns small-12 medium-6 end">
                            <select name="proceso" required="true">
                                <option value="">Seleccione un proceso...</option>
                                @foreach ($sistema->procesos as $proceso)
                                    <option value="{{$proceso->id}}"  <?php echo (isset($accidenteBD))?($accidenteBD->proceso_id == $proceso->id)?"selected":"":""?> >
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
                            <input type="date"  name="fecha" required="true" placeholder="Fecha de accidente" max="{{ $fechaActual }}" value='<?php echo (isset($accidenteBD))? $accidenteBD->fechaAccidente:""?>'/>
                        </div>
                    </div>
                    <div class="row">
                        <br/>
                        <div class="columns small-12 medium-3"><b class="middle">Hora: </b></div>
                        <div class="columns small-12 medium-3 end">
                            <input type="time"  name="hora" required="true" placeholder="Hora del accidente" max="{{ $fechaActual }}" value='<?php echo (isset($accidenteBD))? $accidenteBD->horaAccidente:""?>'/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-3"><b class="middle">Clasificación: </b></div>
                        <div class="columns small-12 medium-4 end">
                            <select name="clasificacion" required="">
                                <option value="">Seleccione...</option>
                                <option value="Casi Accidente" <?php echo (isset($accidenteBD))?($accidenteBD->clasificacion == 'Casi Accidente')?"selected":"":""?>>Casi Accidente</option>
                                <option value="Accidente" <?php echo (isset($accidenteBD))?($accidenteBD->clasificacion == 'Accidente')?"selected":"":""?>>Accidente</option>
                                <option value="Enfermedad Laboral" <?php echo (isset($accidenteBD))?($accidenteBD->clasificacion == 'Enfermedad Laboral')?"selected":"":""?>>Enfermedad Laboral</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12">
                            <p><b>Lugar Exacto Accidente:</b></p>
                            <textarea name="lugar" sytle="min-height:100px;" required="true" class="input-required-paso-5" placeholder="Describa exactamente donde ocurrio el accidente" style="min-height:100px; height:auto"><?php echo (isset($accidenteBD))? $accidenteBD->lugar:""?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 text-left">
                            <p><b>Descripción del Accidente</b></p>
                            <textarea name="descripcion" sytle="min-height:100px;" required="true"  placeholder="Describa Brevemente el accidente" style="min-height:100px; height:auto"><?php echo (isset($accidenteBD))? $accidenteBD->descripcion :""?></textarea>
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
    @include('analissta.Accidentes.crearAccidente.modalCancelar')
@endsection