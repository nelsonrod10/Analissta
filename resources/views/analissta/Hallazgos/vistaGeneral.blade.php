@extends('analissta.layouts.appSideBar')
<?php
use App\Http\Controllers\helpers;
use App\Hallazgos\Hallazgo;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    
    $xml_origenes = simplexml_load_file(base_path("archivosXML/Hallazgos/xml_Origenes.xml"));
    
    $origenesBD = Hallazgo::where('sistema_id',$sistema->id)
            ->orderBy('origen_id','asc')
            ->get()
            ->unique('origen_id');
    
?>
@section('sistem-menu')
<style>
    .titulo-origenes{
        font-size: 16px;
        font-weight: bold;
        color: #3c3737;
    }
    .a-hallazgo{
        width: auto;
        height: auto;
        max-width: 80%;
        max-height: 25px;
        overflow: hidden;

    }
    .a-hallazgo a{
        text-decoration: underline;
    }
</style>
@include('analissta.layouts.appTopMenu')

@endsection
@section('sidebar')

@include('analissta.Hallazgos.menuHallazgos')


@endsection
@section('content')
    @section('titulo-encabezado')
        Hallazgos 
    @endsection
    
    @section('buttons-submenus')
        <a class="button small" href="{{route('crear-hallazgo')}}">Crear Hallazgo</a>
        <a class="button small warning" href="{{route('hallazgos')}}">Listado Hallazgos</a>
    @endsection
    
    <div class="row columns text-center">
        <b>Tipos de estados</b>
    </div>
    <br/>
    <div class="row">
        <div class="columns small-1"><span class="label tiny warning"> Abierto </span></div>
        <div class="columns small-11"><span style="font-size:13px"> NO se han ejecutado todas las actividades y capacitaciones, y <b>aún no se ha llegado</b> a la fecha de cierre propuesta.</span></div>
    </div>
    <br/>
    <div class="row">
        <div class="columns small-1"><span class="label tiny alert"> Vencido </span></div>
        <div class="columns small-11"><span style="font-size:13px"> NO se han ejecutado todas las actividades y capacitaciones, y ya <b>se superó</b> fecha de cierre propuesta</span></div>
    </div>
    <div class="row">
        <div class="columns small-1"><span class="label tiny success-2"> Cerrado </span></div>
        <div class="columns small-11"><span style="font-size:13px"> Todas las actividades y/o capacitaciones ejecutadas al 100%, y <b>realizadas antes de</b> la fecha de cierre propuesta</span></div>
    </div>
    
    <div class="row columns text-center">
        <br/>
        <h5><b>Listado de Hallazgos según Estado</b></h5>
    </div>
    <div class="row">
        <div class="columns small-4 medium-3 text-center">
            <b>Origen</b>
        </div>
        <div class="columns small-4 medium-5 text-center">
            <b>Descripción</b>
        </div>
        <div class="columns small-4 medium-2 text-center">
            <b>Estado</b>
        </div>
        <div class="columns small-4 medium-2 text-center">

        </div>
    </div>
    @if(count($sistema->hallazgos) === 0)
    <br/>
    <div class="columns small-12 text-center"><h6><b><i>No hay hallazgos para mostrar</i></b></h6></div>
    @endif
    <?php   
        foreach($sistema->hallazgos as $hallazgo):
            $nombreOrigen = $xml_origenes->xpath("//origenes/origen[@id={$hallazgo->origen_id}]");
            $objFechaCierre = new DateTime($hallazgo->fechaCierre);
            $diff = $objFechaActual->diff($objFechaCierre);

            if($hallazgo->cerrado === "No"):
                if((int)$diff->format('%R%a') < 0):
                    $estado = "Vencido";
                    $color = "#cc0000";
                elseif ((int)$diff->format('%R%a') >= 0):
                    $estado = "Abierto";
                    $color = "#f29c13";
                endif;
            else:    
                $estado = "Cerrado";
                $color = "#339900";
            endif;
    ?>
        <div class="row">
            <div class="columns small-4 medium-3">
                <?php echo  $nombreOrigen[0]->attributes()["short-name"]; ?>
            </div>
            <div class="columns small-4 medium-5">
                <?php
                    echo substr($hallazgo->descripcion, 0,70)
                ?>
            </div>
            <div class="columns small-4 medium-2 text-center">
                <span class="label" style="background:<?php echo $color; ?>">{{$estado}}</span>
            </div>
            <div class="columns small-4 medium-2 text-center">
                <a class="button tiny" href="{{route('hallazgo',['id'=>$hallazgo->id])}}">Ver Hallazgo</a>
            </div>
        </div>
    <?php 
         endforeach;
    ?>  
@endsection

