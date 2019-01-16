@extends('analissta.layouts.app2')
@section('sistem-menu')
<?php
    use App\Proceso;
    use App\Peligro;
    use App\Http\Controllers\helpers;
    use App\ActividadesValoracione;
    use App\CapacitacionesValoracione;
    use App\InspeccionesValoracione;
    use App\RequisitosLegale;
    
    $proceso = Proceso::find($actividad->proceso_id);
    $peligro = Peligro::find($idPeligro);
    
    $xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    $clasificacion = $xml_GTC45->xpath("//peligros/clasificacion[id=$peligro->clasificacion]");
    $categoria = $clasificacion[0]->xpath("listDescripciones/descripcion[id=$peligro->categoria]");
    
    $cortoPlazo = $peligro->cortoPlazo;
    $largoPlazo = $peligro->largoPlazo;
    
    $nombSubcategoria = "";
    $fuentes = explode(",",$peligro->fuentes);
    $textFuentes=[];
    
    if($peligro->subCategoria !== 0){
        $subcategoria = $categoria[0]->xpath("subDescripcion[id=$peligro->subCategoria]");
        $nombSubcategoria = $subcategoria[0]->nombre;
        foreach($fuentes as $fuente){
            $text = $subcategoria[0]->xpath("fuentes/item[id=$fuente]");
            array_push($textFuentes, $text[0]->nombre);
        }
    }else{
        foreach($fuentes as $fuente){
            $text = $categoria[0]->xpath("fuentes/item[id=$fuente]");
            array_push($textFuentes, $text[0]->nombre);
        }
    }
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
<div class="row">
    <div class="text-center">
        <h5><b>Detalles Peligro</b></h5>
    </div>
</div>
<br/>
<div class="row" >
        <div class="columns small-12 medium-8 small-centered end">
            <div class="text-center" style="border-bottom:1px solid gray"><b>Identificación del Peligro</b></div>
            <div class="columns small-12 medium-6" style="font-size: 12px">
                <div class="columns small-12">
                    <b>Clasificación: </b>
                    {{ $clasificacion[0]->nombre}}
                </div>
                <div class="columns small-12">
                    <b>Descripción: </b>
                    {{ ucfirst(strtolower($categoria[0]->nombre)) }}
                </div>
                @if($nombSubcategoria !== "")
                    <div class="columns small-12">
                        <b>Descripción Detallada: </b>
                        {{ucfirst(strtolower($nombSubcategoria))}}
                    </div>
                @endif
            </div>
            <div class="columns small-12 medium-6 "  style="font-size: 12px">
                <div class="columns small-12"><b>Fuentes:</b></div>
                <div class="columns small-12">
                    <ul>
                        @foreach($textFuentes as $textoFuente)
                            <li>{{ ucfirst(strtolower($textoFuente)) }}</li>
                        @endforeach
                    </ul>    
                </div>
            </div>
            <div class="columns small-12"  style="font-size: 12px">
                <b>Detalles Identificación: </b>
                {{ $peligro->especificacion }}
            </div>
            <div class="columns small-12"  style="font-size: 12px">
                <b>Factor Humano: </b>
                {{ $peligro->factorHumano}}
            </div>
        </div>
        <!--Dejar el siguiente DIV para que funcione la clase small-centered-->
        <div class="columns small-12 text-center"><br/></div>
        
    </div>
    <br/>
    <div class="row">
        <div class="columns small-12 medium-8 small-centered end">
            <div class="columns small-12 medium-6">
                <div class="text-center" style="border-bottom:1px solid gray"><b>Efectos</b></div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Personas: </b>
                    @switch($peligro->efectoPersona)
                        @case("Largo Plazo")
                        <span>Enfermedad Laboral </span>
                        @break
                        @case("Corto Plazo")
                        <span>Accidente de Trabajo </span>
                        @break
                        @case("Corto y Largo Plazo")
                        <span>Accidente de Trabajo y Enfermedad Laboral </span>
                        @break
                    @endswitch
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Propiedad: </b>
                    {{ $peligro->efectoPropiedad }}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Procesos: </b>
                    {{ $peligro->efectoProcesos }}
                </div>
            </div>
            <div class="columns small-12 medium-6">
                <div class="text-center" style="border-bottom:1px solid gray"><b>Controles Existentes</b></div>
                <?php
                if($peligro->efectoPersona === "Corto Plazo" || $peligro->efectoPersona === "Largo Plazo"):
                        if($peligro->efectoPersona === "Corto Plazo"){
                            $textoMedida = "Accidente de Trabajo";
                            $vrFuente = $cortoPlazo->fuente;
                            $vrMedio = $cortoPlazo->medio;
                            $vrIndividuo = $cortoPlazo->individuo;
                            $vrAdmon = $cortoPlazo->administrativo;
                        }else{
                            $textoMedida = "Enfermedad Laboral";
                            $vrFuente = $largoPlazo->fuente;
                            $vrMedio = $largoPlazo->medio;
                            $vrIndividuo = $largoPlazo->individuo;
                            $vrAdmon = $largoPlazo->administrativo;
                        }
                ?>    
                
                    <div class="columns small-12"><b>{{$textoMedida}}</b></div>
                    <div class="columns small-12 end" style="font-size: 12px">
                        <b>Fuente: </b>
                        {{ $vrFuente}}
                    </div>
                    <div class="columns small-12 end" style="font-size: 12px">
                        <b>Medio: </b>
                        {{ $vrMedio}}
                    </div>
                    <div class="columns small-12 end" style="font-size: 12px">
                        <b>Individuo: </b>
                        {{$vrIndividuo}}
                    </div>
                    <div class="columns small-12 end" style="font-size: 12px">
                        <b>Administrativos: </b>
                        {{$vrAdmon}}
                    </div>
                <?php
                endif;
                if((string)$peligro->efectoPersona === "Corto y Largo Plazo"):
                ?>
                    <div class="columns small-12"><b>Accidente de Trabajo</b></div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Fuente: </b>
                        {{ $peligro->cortoPlazo->fuente}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Medio: </b>
                        {{ $peligro->cortoPlazo->medio}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Individuo: </b>
                        {{ $peligro->cortoPlazo->individuo}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Administrativos: </b>
                        {{ $peligro->cortoPlazo->administrativo}}
                    </div>
                    <div class="columns small-12"><br/><b>Enfermedad Laboral</b></div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Fuente: </b>
                        {{ $peligro->largoPlazo->fuente}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Medio: </b>
                        {{ $peligro->largoPlazo->medio}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Individuo: </b>
                        {{ $peligro->largoPlazo->individuo}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Administrativos: </b>
                        {{ $peligro->largoPlazo->administrativo}}
                    </div>
                <?php
                endif;
                ?>
            </div>
            
        </div>
        <!--Dejar el siguiente DIV para que funcione la clase small-centered-->
        <div class="columns small-12 text-center"><br/></div>
    </div>
    <br/>
    <div class="row">
        <div class="columns small-12 medium-8 small-centered end">
            <div class="text-center" style="border-bottom:1px solid gray"><b>Valoración</b></div>
            <?php
            if($peligro->efectoPersona === "Corto Plazo" || $peligro->efectoPersona === "Largo Plazo"):
                if($peligro->efectoPersona === "Corto Plazo"){
                    $vrND = $cortoPlazo->nd;
                    $vrNE = $cortoPlazo->ne;
                    $vrNC = $cortoPlazo->nc;
                    $vrNP = helpers::interpretacionValoracion($cortoPlazo->np, "NP");
                    $vrNRI = helpers::interpretacionValoracion($cortoPlazo->nri, "NRI");
                }else{
                    $vrND = $largoPlazo->nd;
                    $vrNE = $largoPlazo->ne;
                    $vrNC = $largoPlazo->nc;
                    $vrNP = helpers::interpretacionValoracion($largoPlazo->np, "NP");
                    $vrNRI = helpers::interpretacionValoracion($largoPlazo->nri, "NRI");
                }
            ?>  
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Deficiencia (ND): </b>{{$vrND}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Exposición (NE): </b>{{$vrNE}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Consecuencia (NC): </b>{{$vrNC}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Probabilidad (NP): </b>{{$vrNP}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Riesgo Inicial (NRi): </b>{{$vrNRI}}
                </div>
            <?php
            endif;
            
            if($peligro->efectoPersona === "Corto y Largo Plazo"):
            ?>
            <div class="columns small-12 medium-6">
                <div class="columns small-12"><b>Accidente de Trabajo</b></div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Deficiencia (ND): </b>{{ $cortoPlazo->nd }}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Exposición (NE): </b>{{ $cortoPlazo->ne }}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Consecuencia (NC): </b>{{ $cortoPlazo->nc }}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Probabilidad (NP): </b><?php echo helpers::interpretacionValoracion($cortoPlazo->np, "NP")?>
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Riesgo Iniciao (NRi): </b><?php echo helpers::interpretacionValoracion($cortoPlazo->nri, "NRI")?>
                </div>
            </div>
            <div class="columns small-12 medium-6">
                <div class="columns small-12"><b>Enfermedad Laboral</b></div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Deficiencia (ND): </b>{{ $largoPlazo->nd }}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Exposición (NE): </b>{{ $largoPlazo->ne }}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Consecuencia (NC): </b>{{ $largoPlazo->nc }}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Probabilidad (NP): </b><?php echo helpers::interpretacionValoracion($largoPlazo->np, "NP")?>
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Nivel Riesgo Iniciao (NRi): </b><?php echo helpers::interpretacionValoracion($largoPlazo->nri, "NRI")?>
                </div>
            </div>
            <?php
            endif;
            ?>
        </div>
        <!--Dejar el siguiente DIV para que funcione la clase small-centered-->
        <div class="columns small-12 text-center"><br/></div>
    </div>
    <br/>
    <div class="row">
        <div class="columns small-12 medium-8 small-centered end">
            <div class="text-center" style="border-bottom:1px solid gray"><b>Criterios para Controlar</b></div>
        
            <?php
            if($peligro->efectoPersona === "Corto Plazo" || $peligro->efectoPersona === "Largo Plazo"):
                if($peligro->efectoPersona === "Corto Plazo"){
                    $cliente = $cortoPlazo->cliente;
                    $contratista = $cortoPlazo->contratista;
                    $directos = $cortoPlazo->directos;
                    $visitante = $cortoPlazo->visitantes;
                    $reqLegal = $cortoPlazo->reqLegal;
                    $consecuencia = $cortoPlazo->peorConsecuencia;
                    $descRequisitos = RequisitosLegale::where('tipo_id',$cortoPlazo->id)
                            ->where('tipo_texto','Corto Plazo')
                            ->get();
                }else{
                    $cliente = $largoPlazo->cliente;
                    $contratista = $largoPlazo->contratista;
                    $directos = $largoPlazo->directos;
                    $visitante = $largoPlazo->visitantes;
                    $reqLegal = $largoPlazo->reqLegal;
                    $consecuencia = $largoPlazo->peorConsecuencia;
                    $descRequisitos = RequisitosLegale::where('tipo_id',$largoPlazo->id)
                            ->where('tipo_texto','Largo Plazo')
                            ->get();
                }
            ?>  
                <div class="columns small-12"><b>Trabajadores Expuestos</b></div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Clientes: </b>{{$cliente}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Contratistas: </b>{{$contratista}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Directos: </b>{{$directos}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Visitantes: </b>{{$visitante}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Requisito Legal Aplicable: </b>{{$reqLegal}}
                </div>
                <?php
                    if($reqLegal === "Si"):
                ?>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Descripción Legal: </b> {{ $descRequisitos[0]->requisitos}}
                    </div>
                <?php
                    endif;
                    
                ?>
                <div class="columns small-12">
                    <b>Consecuencias: </b><div style="font-size: 12px">{{ $consecuencia}}</div>
                </div>
            <?php    
            endif;
            if($peligro->efectoPersona === "Corto y Largo Plazo"):
            ?>
                <div class="columns small-12 medium-6">
                    <div class="columns small-12"><b>Accidente de Trabajo</b></div>
                    <div class="columns small-12"><b>Trabajadores Expuestos</b></div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Clientes: </b>{{$cortoPlazo->cliente}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Contratistas: </b>{{$cortoPlazo->contratista}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Directos: </b>{{$cortoPlazo->directos}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Visitantes: </b>{{$cortoPlazo->visitantes}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Requisito Legal Aplicable: </b>{{$cortoPlazo->reqLegal}}
                    </div>
                    <?php
                        if($cortoPlazo->reqLegal === "Si"):
                            $descRequisitosCorto = RequisitosLegale::where('tipo_id',$cortoPlazo->id)
                            ->where('tipo_texto','Corto Plazo')
                            ->get();
                    ?>
                        <div class="columns small-12" style="font-size: 12px">
                            <b>Descripción Legal: </b>{{ $descRequisitosCorto[0]->requisitos}}
                        </div>
                    <?php
                        endif;
                    ?>
                    <div class="columns small-12">
                        <b>Consecuencias: </b><div style="font-size: 12px">{{$cortoPlazo->peorConsecuencia}}</div>
                    </div>
                    
                </div>
                <div class="columns small-12 medium-6">
                    <div class="columns small-12"><b>Enfermedad Laboral</b></div>
                    <div class="columns small-12"><b>Trabajadores Expuestos</b></div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Clientes: </b>{{$largoPlazo->cliente}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Contratistas: </b>{{$largoPlazo->contratista}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Directos: </b>{{$largoPlazo->directos}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Visitantes: </b>{{$largoPlazo->visitantes}}
                    </div>
                    <div class="columns small-12" style="font-size: 12px">
                        <b>Requisito Legal Aplicable: </b>{{$largoPlazo->reqLegal}}
                    </div>
                    <?php
                        if($largoPlazo->reqLegal === "Si"):
                            $descRequisitosLargo = RequisitosLegale::where('tipo_id',$largoPlazo->id)
                            ->where('tipo_texto','Largo Plazo')
                            ->get();
                    ?>
                        <div class="columns small-12" style="font-size: 12px">
                            <b>Descripción Legal: </b>{{$descRequisitosLargo}}
                        </div>
                    <?php
                        endif;
                    ?>
                    <div class="columns small-12">
                        <b>Consecuencias: </b><div style="font-size: 12px">{{$largoPlazo->peorConsecuencia}}</div>
                    </div>
                    
                </div>
            <?php
            endif;
            ?>
            </div>
        <!--Dejar el siguiente DIV para que funcione la clase small-centered-->
        <div class="columns small-12 text-center"><br/></div>
    </div>
    @include('analissta.ProcesosActividades.peligroRevaloraciones')
    <br/>
    <div class="row">
        <div class="columns small-12 medium-8 small-centered end" >
            <div class="text-center" style="border-bottom:1px solid gray"><b>Medidas de Intervención</b></div>
            <div class="columns small-12 medium-6">
                
                <?php
                    $Actividades =ActividadesValoracione::where('peligro_id',$peligro->id)->get();
                    if(count($Actividades)>0):
                ?>
                <div class="columns small-12 text-center"><b>Actividades</b></div>
                    <ul class="no-bullet"  style="font-size: 12px">
                        @foreach ($Actividades as $actividad1)
                        <?php
                            switch($actividad1->estado):
                            case('Programada'):
                                $color = "#cc0000";
                                break;
                            case('En ejecucion'):
                                $color = "#f29c13";
                                break;
                            case('Ejecutado'):
                                $color = "#3adb76";
                                break;
                            default:
                                $color= "white";
                        endswitch;
                        ?>
                            <li>
                                <a href="{{route('actividad-valoracion',['id'=>$actividad1->id])}}">{{$actividad1->nombre}}</a>
                                <i style="background:<?php echo $color ?>; color:white"><small>{{$actividad1->estado}}</small></i>
                            </li>
                        @endforeach
                    </ul>    
                <?php    
                    endif;
                ?>
            </div>
            
            <div class="columns small-12 medium-6">
                
                <?php
                /*if($actividad->attributes()["idRevaloracion"]):
                    <!--<span style="background: #558000;font-size: 11px;color: white;">Revaloracion</span>-->
                //endif*/
                    $Capacitaciones=CapacitacionesValoracione::where('peligro_id',$peligro->id)->get();
                    if(count($Capacitaciones)>0):
                ?>
                <div class="columns small-12 text-center"><b>Capacitaciones</b></div>
                    <ul class="no-bullet"  style="font-size: 12px">
                        @foreach ($Capacitaciones as $capacitacion)
                            <li>
                                <a href="{{route('capacitacion-valoracion',['id'=>$capacitacion->id])}}">
                                    {{$capacitacion->nombre}}
                                
                                </a>
                            </li>
                        @endforeach
                    </ul>    
                <?php    
                    endif;
                ?>
            </div>
            
            <div class="columns small-12 medium-6 end">
                <?php
                    $Inspecciones=InspeccionesValoracione::where('peligro_id',$peligro->id)->get();
                    if(count($Inspecciones)>0):
                ?>
                <div class="columns small-12 text-center"><b>Inspecciones</b></div>
                    <ul class="no-bullet"  style="font-size: 12px">
                        @foreach ($Inspecciones as $inspeccion)
                        <?php
                            switch($inspeccion->estado):
                            case('Programada'):
                                $color = "#cc0000";
                                break;
                            case('En ejecucion'):
                                $color = "#f29c13";
                                break;
                            case('Ejecutado'):
                                $color = "#3adb76";
                                break;
                            default:
                                $color= "white";
                        endswitch;
                        ?>
                            <li>
                                <a href="{{route('inspeccion-valoracion',['id'=>$inspeccion->id])}}">{{$inspeccion->nombre}}</a>
                                <i style="background:<?php echo $color ?>; color:white"><small>{{$inspeccion->estado}}</small></i>
                            </li>
                        @endforeach
                    </ul>    
                <?php    
                    endif;
                ?>
            </div>
            
        </div>
        <!--Dejar el siguiente DIV para que funcione la clase small-centered-->
        <div class="columns small-12 text-center"><br/>
            <a class="button small alert" href="{{route('revaloracion-peligro',['id'=>$peligro])}}">Revalorar</a>
            <a class="button small" href="{{ route('ver-actividad-proceso',['id'=>$actividad->id])}}">Volver</a>
        </div>
    </div>
@endsection

