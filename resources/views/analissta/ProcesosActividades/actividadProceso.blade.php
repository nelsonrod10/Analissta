@extends('analissta.layouts.app2')
@section('sistem-menu')
<?php
    use App\Proceso;
    use App\Peligro;
    use App\Http\Controllers\helpers;
    use App\ActividadesValoracione;
    use App\CapacitacionesValoracione;
    use App\InspeccionesValoracione;
    
    
    $xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    $proceso = Proceso::find($actividad->proceso_id);
?>
    @include('analissta.layouts.appTopMenu')
@endsection
@section('content')

@include('analissta.layouts.encabezadoEmpresaCliente')
<div class="row">
    <div class="text-center"><h4><b>Proceso | {{ ucwords($proceso->nombre) }}</b></h4></div>
    <div class="text-center"><h5><b>Actividad | {{ ucwords($actividad->nombre) }}</b></h5></div>
</div>
<br/>
<div id="div-infoActividad">
    <div class="row">
        <div class="columns small-12 medium-8 small-centered end">
            <div class="text-center" style="border-bottom:1px solid gray"><h5><b>Datos Generales</b></h5></div>
            <div class="columns small-12 medium-6 text-center">
                <b>Actividad Rutinaria: </b>
                {{ $actividad->rutinaria }}
            </div>
            <div class="columns small-12 medium-4 text-center end">
                <b>Tipo Actividad: </b>
                {{ $actividad->interna }},
                {{ $actividad->externa }}
            </div>

            <div class="columns small-12">
                <br/>
                <b>Descripción de la Actividad: </b>
            </div>

            <div class="columns small-12 fieldset" style="margin:0px;">
                {{ $actividad->descripcion }}
            </div>
            <div class="columns small-12">
                <br/>
                <b>Equipos y Herramientas: </b>
            </div>

            <div class="columns small-12 fieldset" style="margin:0px;">
                {{ $actividad->equipos }}
            </div>
        </div>

        <div class="columns small-12 text-center">
            <br/>
            <a class="button small" href="{{ route('procesos-actividades',['sistema'=> $sistema])}}">Procesos y Actividades</a>
            <a id="a-updateActividad" class="button small success-2" href="{{ route('frm-actualizar-actividad-proceso',['nombreProceso'=>$proceso->nombre,'idActividad'=>$actividad->id]) }}">Editar Datos</a>
        </div>

    </div>
    <br/>
    <div class="row">
        <div class="columns small-12 medium-8 small-centered end">
            <div class="text-center" style="border-bottom:1px solid gray"><h5><b>Peligros Asociados</b></h5></div>
        </div>
        <div class="columns small-12 medium-8 small-centered end">
            <br/>
            <?php
                $arr1 = [];
                foreach ($actividad->peligros as $peligro){
                    array_push($arr1,$peligro->clasificacion);
                }
                        
                $arrClasificaciones = array_unique($arr1);
                if(count($arrClasificaciones)>0):
                foreach($arrClasificaciones as $clasif):
                    $clasificacion = $xml_GTC45->xpath("//peligros/clasificacion[id=$clasif]");
            ?>            
                <div class="row">
                    <div class="columns small-12 text-center" style="text-decoration: underline">
                        <h5>
                            <b>
                            <?php echo $clasificacion[0]->nombre;?> 
                            </b>
                        </h5>
                    </div>
                    <ul class="no-bullet">
                        <?php 
                            $categorias = Peligro::where('actividad_id',$actividad->id)
                                    ->where('clasificacion',$clasificacion[0]->id)
                                    ->get();
                            
                            foreach($categorias as $dataCateg):
                                $categoria = $xml_GTC45->xpath("//peligros/clasificacion[id=$clasif]/listDescripciones/descripcion[id=$dataCateg->categoria]");
                                $subCategoria=[];
                                if((int)$dataCateg->subCategoria !== 0){
                                    $subCategoria = $xml_GTC45->xpath("//peligros/clasificacion[id=$clasif]/listDescripciones/descripcion[id=$dataCateg->categoria]/subDescripcion[id=$dataCateg->subCategoria]");
                                }
                                
                            // if($elemDetallesPeligro[0]->subDescripcion){echo " - ".  ucfirst(strtolower($elemDetallesPeligro[0]->subDescripcion->nombre));}
                        ?>
                            <li>
                                <div class="columns small-12">
                                    <b><?php 
                                            echo ucfirst(strtolower($categoria[0]->nombre)); 
                                            echo (count($subCategoria)>0)? " - ".  ucfirst(strtolower($subCategoria[0]->nombre)):""; ?> 
                                    </b>
                                </div>
                                <div class="columns small-12" style="font-size: 12px; color: gray">
                                    <?php 
                                    $idPGRP=$idPVE=0;
                                    switch ($dataCateg->efectoPersona):
                                        case "Largo Plazo":
                                            $textNP = helpers::interpretacionValoracion($dataCateg->largoPlazo->np, "NP");
                                            $textNRI = helpers::interpretacionValoracion($dataCateg->largoPlazo->nri, "NRI");
                                            $idPVE=($dataCateg->largoPlazo->pve === "Si")?$dataCateg->largoPlazo->pve_id:0;
                                            
                                    ?>
                                        <div class="columns small-12"><b>Enfermedad Laboral</b> |  <b>Probabilidad: </b>{{$textNP}} |  <b>Riesgo Inicial: </b>{{$textNRI}}</div>
                                    <?php
                                        break;
                                        case "Corto Plazo":
                                            $textNP = helpers::interpretacionValoracion($dataCateg->cortoPlazo->np, "NP");
                                            $textNRI = helpers::interpretacionValoracion($dataCateg->cortoPlazo->nri, "NRI");
                                            $idPGRP=($dataCateg->cortoPlazo->pgrp === "Si")?$dataCateg->cortoPlazo->pgrp_id:0;
                                    ?>
                                        <div class="columns small-12"><b>Accidente de Trabajo</b> |  <b>Probabilidad: </b>{{$textNP}} |  <b>Riesgo Inicial: </b>{{$textNRI}}</div>
                                    <?php
                                        break;
                                        case "Corto y Largo Plazo":
                                            $textNPlp = helpers::interpretacionValoracion($dataCateg->largoPlazo->np, "NP");
                                            $textNRIlp = helpers::interpretacionValoracion($dataCateg->largoPlazo->nri, "NRI");
                                            $textNPcp = helpers::interpretacionValoracion($dataCateg->cortoPlazo->np, "NP");
                                            $textNRIcp = helpers::interpretacionValoracion($dataCateg->cortoPlazo->nri, "NRI");
                                            $idPVE=($dataCateg->largoPlazo->pve === "Si")?$dataCateg->largoPlazo->pve_id:0;
                                            $idPGRP=($dataCateg->cortoPlazo->pgrp === "Si")?$dataCateg->cortoPlazo->pgrp_id:0;
                                    ?>
                                        <div class="columns small-12"><b>Accidente de Trabajo</b> |  <b>Probabilidad: </b>{{$textNPcp}} |  <b>Riesgo Inicial: </b>{{$textNRIcp}}</div>
                                        <div class="columns small-12"><b>Enfermedad Laboral</b> | <b>Probabilidad: </b>{{$textNPlp}} |  <b>Riesgo Inicial: </b>{{$textNRIlp}}</div>
                                    <?php
                                        break;
                                    endswitch;
                                    ?>
                                </div>
                                <div class="columns small-12" style="font-size: 12px; color: gray">
                                    <div class="columns small-12" style="font-size: 12px; color: gray">
                                        <?php
                                            if($idPGRP !== 0):
                                                switch ($dataCateg->cortoPlazo->pgrp_table) {
                                                    case "pgrp_fisicos":
                                                        $tipoPGRP = str_replace('pgrp_',null,substr($dataCateg->cortoPlazo->pgrp_table, 0,-1));
                                                        break;
                                                    default:
                                                        $tipoPGRP = str_replace('pgrp_',null,substr($dataCateg->cortoPlazo->pgrp_table, 0,-2));
                                                        break;
                                                }
                                                
                                        ?>
                                            <a class="button tiny alert" href="{{route('pgrp',['tipo'=>$tipoPGRP,'id'=>$idPGRP])}}">PGRP</a>
                                        <?php
                                            endif;
                                        ?>
                                        <?php
                                            if($idPVE !== 0):
                                                switch ($dataCateg->largoPlazo->pve_table) {
                                                    case "pve_fisicos":
                                                        $tipoPVE = str_replace('pve_',null,substr($dataCateg->largoPlazo->pve_table, 0,-1));
                                                        break;
                                                    default:
                                                        $tipoPVE = str_replace('pve_',null,substr($dataCateg->largoPlazo->pve_table, 0,-2));
                                                        break;
                                                }
                                        ?>
                                            <a class="button tiny warning" href="{{route('pve',['tipo'=>$tipoPVE,'id'=>$idPVE])}}">PVE</a>
                                        <?php
                                            endif;
                                        ?>
                                        <?php
                                            $numActividades = ActividadesValoracione::where('peligro_id',$dataCateg->id)->get();
                                            if(count($numActividades)> 0):
                                        ?>
                                            <a class="button tiny " href="{{route('actividades')}}"><span>{{count($numActividades)}}</span>  - Actividades</a>
                                        <?php
                                            endif;
                                        ?>
                                        <?php
                                            $numCapacitaciones = CapacitacionesValoracione::where('peligro_id',$dataCateg->id)->get();
                                            if(count($numCapacitaciones)> 0):
                                        ?>
                                            <a class="button tiny success-2" href="{{route('capacitaciones')}}"><span>{{count($numCapacitaciones) }}</span> - Capacitaciones </a></span> 
                                        <?php
                                            endif;
                                        ?>
                                        <?php
                                            $numInspecciones = InspeccionesValoracione::where('peligro_id',$dataCateg->id)->get();
                                            if(count($numInspecciones)> 0):
                                        ?>
                                            <a class="button tiny success-2" href="{{route('inspecciones')}}"><span>{{ count($numInspecciones) }}</span> - Inspecciones </a>
                                        <?php
                                            endif;
                                        ?>    
                                        <div class="button-group tiny secondary">
                                            <a class="button" href="{{ route('detalles-peligro',['idActividad'=>$actividad->id,'idPeligro'=>$dataCateg->id])}}">Ver Detalles</a>
                                        </div>    

                                    </div>

                                </div>
                            </li>
                        <?php
                            endforeach;
                        ?>
                    </ul>
                </div>
            <?php
                endforeach;
            ?>
            

            <?php
                else:
            ?>
            <div class="row columns text-center">
                <i>No existen peligros asociados a esta actividad</i>
            </div>
            <?php
                endif;
            ?>
            <br/>
        </div>
        <div class="row columns text-center">
            <a class="button small" href="{{ route('procesos-actividades',['sistema'=> $sistema])}}">Procesos y Actividades</a>
            <a class="button small alert" href="{{ route('identificacion-peligro',['idActividad'=>$actividad->id])}}">Agregar Peligro</a>
        </div>
    </div>
</div>
@endsection

