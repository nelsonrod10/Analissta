@extends('analissta.layouts.appProgramarMedida')
@section('content')
<?php
    use App\Proceso;
    use App\Peligro;
    use App\ActividadesValoracione;
    use App\CapacitacionesValoracione;
    use App\InspeccionesValoracione;
    
    $proceso = Proceso::find($actividad->proceso_id);
    $peligro = Peligro::find(session('idPeligro'));
    
    $xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    $xpath_nombrePeligro = $xml_GTC45->xpath("//peligros/clasificacion[id=$peligro->clasificacion]");
    $xpath_descPeligro = $xml_GTC45->xpath("//peligros/clasificacion[id=$peligro->clasificacion]/listDescripciones/descripcion[id=$peligro->categoria]");
    $clasificacionPeligro = $xpath_nombrePeligro[0]->nombre;
    $categoriaPeligro = $xpath_descPeligro[0]->nombre;
    
    $medidasPendientes = count(session('arrMedidas'));
    $medidaAconfigurar = session('arrMedidas')[0];
    
    switch ($peligro->efectoPersona) {
        case 'Corto Plazo':
            $textoEfecto = "Accidentes de Trabajo";
        break;
        case 'Largo Plazo':
            $textoEfecto = "Enfermedades Laborales";
        break;
        case 'Corto y Largo Plazo':
            $textoEfecto = "Accidentes de Trabajo y Enfermedades Laborales";
        break;

        default:
            $textoEfecto = "";
        break;
    }
    //$flagMedidas para saber si son solo actividades, o en el caso de administrativos: actividades, capacitaciones e inspecciones
    $flagMedidas = "";
    switch ($medidaAconfigurar) {
        case 'administrativos':
            $textoMedida = "Controles Administrativos";
            $flagMedidas = "Todas";
        break;
        case 'ingenieria':
            $textoMedida = "Controles de Ingenieria";
        break;
        case 'epp_herramientas':
            $textoMedida = "EPP y Herramientas";
        break;
        default:
            $textoMedida = "";
        break;
    }
    if ($medidaAconfigurar != 'administrativos' && $medidaAconfigurar != 'ingenieria' && $medidaAconfigurar != 'epp_herramientas'){
        $textoMedida = ucfirst($medidaAconfigurar);
    }
        
    
?>

<div class="row">
    <div class="text-center"><h5><b>Proceso | {{ ucwords($proceso->nombre) }}</b></h5></div>
    <div class="text-center"><b>Actividad | {{ ucwords($actividad->nombre) }}</b></div>
</div>
<div class="row">
    <div class="columns small-12 medium-8 small-centered">
        <div class="row columns text-center" >
            <div style="text-decoration: underline"><b>VALORACION PELIGRO </b></div>
            <div>{{ $clasificacionPeligro }} - {{ $categoriaPeligro }}</div>
        </div>
        <div class="row columns text-center" style="text-decoration: underline">
            <b>MEDIDAS DE INTERVENCION</b>
        </div>
        @include('analissta.Asesores.crearEmpresa.errors')
        <br/>
        <div class="row columns text-center">
            <b>{{ $textoEfecto}}</b>
        </div>
        <div class="row columns text-center label secondary">
            <b style="font-size:18px">{{ $textoMedida }}</b>
        </div>
        @if($flagMedidas == '')
            @include('analissta.Valoracion.frmCrearActividades')
            @php
                $conteoActividades = ActividadesValoracione::where('peligro_id',session('idPeligro'))
                        ->where('medida',$medidaAconfigurar)
                        ->get();
                $conteoAvanzar = count($conteoActividades);
                $mensajeContinuar = "Para continuar debe crear por lo menos una Actividad";
            @endphp
        @endif
        @if($flagMedidas == 'Todas')
            @php
                $conteoActividades = ActividadesValoracione::where('peligro_id',session('idPeligro'))
                        ->where('medida',$medidaAconfigurar)
                        ->get();
                $conteoCapacitaciones = CapacitacionesValoracione::where('peligro_id',session('idPeligro'))
                        ->where('medida',$medidaAconfigurar)
                        ->get();
                $conteoInspecciones = InspeccionesValoracione::where('peligro_id',session('idPeligro'))
                        ->where('medida',$medidaAconfigurar)
                        ->get();
                        
                $conteoAvanzar = count($conteoActividades) + count($conteoCapacitaciones) + count($conteoInspecciones);
                $mensajeContinuar = "Para continuar debe crear por lo menos una Actividad, Capacitación o Inspección";
            @endphp
            <div class="row">
                <div class="columns small-11 medium-6" style="border-right:1px solid lightgray;">
                    @include('analissta.Valoracion.frmCrearActividades')
                </div>
                <div class="columns small-11 medium-6" style="border-right:1px solid lightgray;">
                    @include('analissta.Valoracion.frmCrearCapacitaciones')
                </div>    
            </div>
            <div class="row" style="border-top:1px solid lightgray;">
                <div class="columns small-6 end" >
                    @include('analissta.Valoracion.frmCrearInspecciones')
                </div>
            </div>    
        @endif
    </div>
</div>
<div class="row text-center">
    <div class="columns small-12" data-tabs="">
        <br/>
        <a class="button small " href="{{ route('volver-a-medidas-intervencion-peligro',['idActividad'=>$actividad->id])}}">Volver a Medidas</a>
        <a class="button small alert" data-open="modal-confirm-cancelValoracion">Cancelar Valoración</a>
        @if($conteoAvanzar > 0)
            @if($medidasPendientes === 1)
            <!-- quiere decir que estamos en la ultima medida y debe finalizar la valoracion-->
                <a class="button small success" href="{{ route('finalizar-valoracion',['idActividad'=>$actividad->id,'medida'=>$medidaAconfigurar]) }}">Finalizar Valoración</a>
            @else
                <a class="button small success" href="{{ route('avanzar-medida-intervencion',['idActividad'=>$actividad->id,'medida'=>$medidaAconfigurar]) }}">Siguiente</a>
            @endif
        @else
        <div><b>* <i>{{$mensajeContinuar}}</i> *</div>
        @endif
        
        
    </div>
</div>

@include('analissta.Valoracion.modalCancelarValoracion')
@include('analissta.Valoracion.scriptsValoracion')
@endsection