@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\Http\Controllers\helpers;
    use App\Accidentes\Accidente;
    use App\Empleado;
    use App\Accidentes\AccidentesAfectacione;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    $xml_cuerpo = simplexml_load_file(base_path("archivosXML/Accidentalidad/xml_PartesCuerpo.xml"));
    $tiposCuerpo = $xml_cuerpo->xpath("//partesCuerpo/tipo");
    
    
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
            <h6><b>PARTES DEL CUERPO AFECTADAS</b></h6>
        </div>
    </div>
    <br/>
    
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row">
        <div class="columns small-12 medium-10 small-centered">
            <div class="row columns">
                En el accidente de <b>{{$accidentado[0]->nombre}} {{$accidentado[0]->apellidos}}</b>, especifique las partes del cuerpo afectadas:
            </div>
            <br/>
            <form method="post" id="afectacionCuerpoAccidente" name="cuerpoAccidente" action="{{route('crear-afectacion-cuerpo-accidente',["id"=>$idAccidente])}}">
                {{ csrf_field() }}
                <div class="row">
                    @foreach($tiposCuerpo as $tipo)
                        <div class="columns small-12 medium-6">
                            <div class="text-center" style="font-size:18px;color:white;background:gray">{{$tipo->nombre}}</div>
                            @if($tipo->nombre->attributes()['textAuxiliar'])
                                <div class="text-center" style="padding:10px;font-size:12px;background:lightgray">
                                    <i>{{$tipo->nombre->attributes()['textAuxiliar']}}</i>
                                </div>
                            @endif
                            <ul class="no-bullet">
                                <?php
                                    $partesCuerpo = $xml_cuerpo->xpath("//partesCuerpo/tipo[@id='{$tipo->attributes()['id']}']/parte");
                                ?>
                                @foreach($partesCuerpo as $parte)
                                    <?php
                                        $checked = "";
                                        $existeParte = AccidentesAfectacione::where('sistema_id',$sistema->id)
                                                ->where('accidente_id',$idAccidente)
                                                ->where('tipo','Cuerpo')
                                                ->where('categoria',$tipo->attributes()['id'])
                                                ->where('descripcion',$parte->attributes()['id'])
                                                ->get();
                                        if(count($existeParte)>0){
                                            $checked = "checked";
                                        }
                                        
                                    ?>
                                    <li>
                                        <input type="checkbox" <?php echo $checked ?> id="parte-<?php echo $tipo->attributes()['id']."-".$parte->attributes()['id'] ?>" name="partes[]" class="check-afectacion" value="<?php echo $tipo->attributes()['id']."-".$parte->attributes()['id'] ?>"/>
                                        <label for="parte-<?php echo $tipo->attributes()['id']."-".$parte->attributes()['id'] ?>">{{$parte}}</label>
                                        @if($parte->attributes()['textAuxiliar'])
                                            <i style="font-size:12px;background:#c2f0c2">{{$parte->attributes()['textAuxiliar']}}</i>
                                        @endif
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    @endforeach
                </div>
                <br/>
                <div class="row columns text-center">
                    <a class="button small" href="{{route('datos-accidente',['id'=>$idAccidente])}}">Anterior</a>
                    <a class="button small alert" data-open="reveal-cancelar-crear">Cancelar</a>
                    <input type="submit" class="button small success" value="Siguiente"/>
                </div>    
            </form>
        </div>
    </div>
    @include('analissta.Accidentes.crearAccidente.modalCancelar')
    
    <script>
        $(document).ready(function(){
            $("#afectacionCuerpoAccidente").on("submit",function(e){
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