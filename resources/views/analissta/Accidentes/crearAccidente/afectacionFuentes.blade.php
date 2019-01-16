@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\Http\Controllers\helpers;
    use App\Accidentes\Accidente;
    use App\Empleado;
    use App\Accidentes\AccidentesAfectacione;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    $xml_fuentes = simplexml_load_file(base_path("archivosXML/Accidentalidad/xml_FuentesLesion.xml"));
    $tiposFuentes = $xml_fuentes->xpath("//fuentesLesion/tipo");
    
    
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
            <h6><b>FUENTES DEL ACCIDENTE</b></h6>
        </div>
    </div>
    <br/>
    
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row">
        <div class="columns small-12 medium-10 small-centered">
            <div class="row columns">
                En el accidente de <b>{{$accidentado[0]->nombre}} {{$accidentado[0]->apellidos}}</b>, especifique los agentes:
            </div>
            <br/>
            <form method="post" id="afectacionFuenteAccidente" name="fuentesAccidente" action="{{route('crear-afectacion-fuente-accidente',["id"=>$idAccidente])}}">
                {{ csrf_field() }}
                <div class="row">
                    @foreach($tiposFuentes as $tipo)
                        <div class="columns small-12 medium-6">
                            <div class="text-center" style="font-size:18px;color:white;background:gray">{{$tipo->nombre}}</div>
                            @if($tipo->nombre->attributes()['textAuxiliar'])
                                <div class="text-center" style="padding:10px;font-size:12px;background:lightgray">
                                    <i>{{$tipo->nombre->attributes()['textAuxiliar']}}</i>
                                </div>
                            @endif
                            <ul class="no-bullet">
                                <?php
                                    $fuentes = $xml_fuentes->xpath("//fuentesLesion/tipo[@id='{$tipo->attributes()['id']}']/fuentes");
                                ?>
                                @foreach($fuentes as $fuente)
                                    <?php
                                        $checked = "";
                                        $existeFuente = AccidentesAfectacione::where('sistema_id',$sistema->id)
                                                ->where('accidente_id',$idAccidente)
                                                ->where('tipo','Fuentes')
                                                ->where('categoria',$tipo->attributes()['id'])
                                                ->where('descripcion',$fuente->attributes()['id'])
                                                ->get();
                                        if(count($existeFuente)>0){
                                            $checked = "checked";
                                        }
                                        
                                    ?>
                                    <li>
                                        <input type="checkbox" <?php echo $checked ?> id="fuente-<?php echo $tipo->attributes()['id']."-".$fuente->attributes()['id'] ?>" name="fuentes[]" class="check-afectacion" value="<?php echo $tipo->attributes()['id']."-".$fuente->attributes()['id'] ?>"/>
                                        <label for="fuente-<?php echo $tipo->attributes()['id']."-".$fuente->attributes()['id'] ?>">{{$fuente}}</label>
                                        @if($fuente->attributes()['textAuxiliar'])
                                            <i style="font-size:12px;background:#c2f0c2">{{$fuente->attributes()['textAuxiliar']}}</i>
                                        @endif
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    @endforeach
                </div>
                <br/>
                <div class="row columns text-center">
                    <a class="button small" href="{{route('afectacion-agente-accidente',['id'=>$idAccidente])}}">Anterior</a>
                    <a class="button small alert" data-open="reveal-cancelar-crear">Cancelar</a>
                    <input type="submit" class="button small success" value="Siguiente"/>
                </div>    
            </form>
        </div>
    </div>
    @include('analissta.Accidentes.crearAccidente.modalCancelar')
    
    <script>
        $(document).ready(function(){
            $("#afectacionFuenteAccidente").on("submit",function(e){
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