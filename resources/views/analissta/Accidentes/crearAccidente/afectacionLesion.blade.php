@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\Http\Controllers\helpers;
    use App\Accidentes\Accidente;
    use App\Empleado;
    use App\Accidentes\AccidentesAfectacione;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    $xml_lesion = simplexml_load_file(base_path("archivosXML/Accidentalidad/xml_TipoLesion.xml"));
    $tiposLesion = $xml_lesion->xpath("//lesiones/tipo");
    
    
    if(isset($idAccidente)){
        $accidenteBD = Accidente::find($idAccidente);
        
        $accidentado = Empleado::where('empresaCliente_id',$empresa->id)
            ->where('identificacion',$accidenteBD->accidentado_id)
            ->get();
    }
?>
@section('content')
    @section('titulo-encabezado')
        Reportar Nuevo Accidente 
    @endsection
    <div class="row text-center">
        <div class="columns small-12 medium-10 small-centered label secondary">
            <h6><b>TIPOS DE LESIÓN</b></h6>
        </div>
    </div>
    <br/>
    
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row">
        <div class="columns small-12 medium-10 small-centered">
            <div class="row columns">
                En el accidente de <b>{{$accidentado[0]->nombre}} {{$accidentado[0]->apellidos}}</b>, especifique los tipos de lesión:
            </div>
            <br/>
            <form method="post" id="afectacionLesionAccidente" name="cuerpoAccidente" action="{{route('crear-afectacion-lesion-accidente',["id"=>$idAccidente])}}">
                {{ csrf_field() }}
                <div class="row">
                    @foreach($tiposLesion as $lesion)
                        <?php
                            $checked = "";
                            $existeParte = AccidentesAfectacione::where('sistema_id',$sistema->id)
                                    ->where('accidente_id',$idAccidente)
                                    ->where('tipo','Lesion')
                                    ->where('descripcion',$lesion->attributes()['id'])
                                    ->get();
                            if(count($existeParte)>0){
                                $checked = "checked";
                            }

                        ?>
                        <div class="columns small-12 medium-6 end" style="padding-bottom:10px">
                            <div class="columns small-1">
                                <input type="checkbox" <?php echo $checked ?> id="lesion-{{$lesion->attributes()['id']}}" name="lesiones[]" class="check-afectacion" value="{{$lesion->attributes()['id']}}" />
                            </div>
                            <div class="columns small-11" ><label for="lesion-{{$lesion->attributes()['id']}}">{{$lesion}}</label></div>
                        </div>
                    @endforeach
                </div>
                <br/>
                <div class="row columns text-center">
                    <a class="button small" href="{{route('afectacion-cuerpo-accidente',['id'=>$idAccidente])}}">Anterior</a>
                    <a class="button small alert" data-open="reveal-cancelar-crear">Cancelar</a>
                    <input type="submit" class="button small success" value="Siguiente"/>
                </div>    
            </form>
        </div>
    </div>
    @include('analissta.Accidentes.crearAccidente.modalCancelar')
    
    <script>
        $(document).ready(function(){
            $("#afectacionLesionAccidente").on("submit",function(e){
                var flag=0;
                $(".check-afectacion").each(function(){
                    if($(this).is(":checked")){
                        flag=1;
                    }
                });
                
                if(flag === 0){
                    alert("Debe seleccionar por lo menos una opción");
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection