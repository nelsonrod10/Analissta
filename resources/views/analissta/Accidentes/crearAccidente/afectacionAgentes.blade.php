@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\Http\Controllers\helpers;
    use App\Accidentes\Accidente;
    use App\Empleado;
    use App\Accidentes\AccidentesAfectacione;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    $xml_agentes = simplexml_load_file(base_path("archivosXML/Accidentalidad/xml_AgentesAccidente.xml"));
    $tiposAgentes = $xml_agentes->xpath("//agentes/tipo");
    
    
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
            <h6><b>AGENTES DEL ACCIDENTE</b></h6>
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
            <form method="post" id="afectacionAgenteAccidente" name="agentesAccidente" action="{{route('crear-afectacion-agente-accidente',["id"=>$idAccidente])}}">
                {{ csrf_field() }}
                <div class="row">
                    @foreach($tiposAgentes as $tipo)
                        <div class="columns small-12 medium-6">
                            <div class="text-center" style="font-size:18px;color:white;background:gray">{{$tipo->nombre}}</div>
                            @if($tipo->nombre->attributes()['textAuxiliar'])
                                <div class="text-center" style="padding:10px;font-size:12px;background:lightgray">
                                    <i>{{$tipo->nombre->attributes()['textAuxiliar']}}</i>
                                </div>
                            @endif
                            <ul class="no-bullet">
                                <?php
                                    $agentes = $xml_agentes->xpath("//agentes/tipo[@id='{$tipo->attributes()['id']}']/agente");
                                ?>
                                @foreach($agentes as $agente)
                                    <?php
                                        $checked = "";
                                        $existeAgente = AccidentesAfectacione::where('sistema_id',$sistema->id)
                                                ->where('accidente_id',$idAccidente)
                                                ->where('tipo','Agentes')
                                                ->where('categoria',$tipo->attributes()['id'])
                                                ->where('descripcion',$agente->attributes()['id'])
                                                ->get();
                                        if(count($existeAgente)>0){
                                            $checked = "checked";
                                        }
                                        
                                    ?>
                                    <li>
                                        <input type="checkbox" <?php echo $checked ?> id="agente-<?php echo $tipo->attributes()['id']."-".$agente->attributes()['id'] ?>" name="agentes[]" class="check-afectacion" value="<?php echo $tipo->attributes()['id']."-".$agente->attributes()['id'] ?>"/>
                                        <label for="agente-<?php echo $tipo->attributes()['id']."-".$agente->attributes()['id'] ?>">{{$agente}}</label>
                                        @if($agente->attributes()['textAuxiliar'])
                                            <i style="font-size:12px;background:#c2f0c2">{{$agente->attributes()['textAuxiliar']}}</i>
                                        @endif
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    @endforeach
                </div>
                <br/>
                <div class="row columns text-center">
                    <a class="button small" href="{{route('afectacion-lesion-accidente',['id'=>$idAccidente])}}">Anterior</a>
                    <a class="button small alert" data-open="reveal-cancelar-crear">Cancelar</a>
                    <input type="submit" class="button small success" value="Siguiente"/>
                </div>    
            </form>
        </div>
    </div>
    @include('analissta.Accidentes.crearAccidente.modalCancelar')
    
    <script>
        $(document).ready(function(){
            $("#afectacionAgenteAccidente").on("submit",function(e){
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