@extends('analissta.layouts.appProgramarMedida')
@section('content')
<?php
    use App\Proceso;
    use App\Peligro;
    
    
    $xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    $xpath_nombrePeligro = $xml_GTC45->xpath("//peligros/clasificacion[id=$peligro->clasificacion]");
    $xpath_descPeligro = $xml_GTC45->xpath("//peligros/clasificacion[id=$peligro->clasificacion]/listDescripciones/descripcion[id=$peligro->categoria]");
    $clasificacionPeligro = $xpath_nombrePeligro[0]->nombre;
    $categoriaPeligro = $xpath_descPeligro[0]->nombre;
    
    
    
    $xml_ND = simplexml_load_file(base_path("archivosXML/ValoracionPeligro/xml_Tablas_ND.xml"));
?>

<div class="row">
    <div class="text-center"><h5><b>Proceso | {{ ucwords($peligro->actividad->proceso->nombre) }}</b></h5></div>
    <div class="text-center"><b>Actividad | {{ ucwords($peligro->actividad->nombre) }}</b></div>
</div>
<div class="row">
    <div class="columns small-12 medium-8 small-centered">
        <form id="frm-nuevoPeligroMedidas" name="frm-nuevoPeligroMedidas" method="POST" action="{{ route('medidas-intervencion-revaloracion',['idPeligro'=>$peligro]) }}">
            {{ csrf_field() }}  
            <div class="row columns text-center" >
                <div style="text-decoration: underline"><b>VALORACION PELIGRO </b></div>
                <div>{{ $clasificacionPeligro }} - {{ $categoriaPeligro }}</div>
            </div>
            <div class="row columns text-center" style="text-decoration: underline">
                <b>MEDIDAS DE INTERVENCION</b>
            </div>
            @include('analissta.Asesores.crearEmpresa.errors')
            <br/>
            <div class="row" style="font-size:12px">
                <div class="columns small-12">
                    <p>Pasos para Configurar Medidas de Intervención</p>
                    <ol>
                        <li>Seleccione las medidas de intervención a configurar</li>
                        <li>Por cada medida de intervención seleccionada, ingrese el listado de actividades</li>
                    </ol>
                </div>
            </div>
            <div class="columns small-12">
                <br/>
                <div class="columns small-12 medium-4 end">
                    <input type="checkbox" id="check-eliminarInter" onclick="disabledMedidasIntervencion()" class="{{($peligro->eliminar != 'N/A')?:'check-medidas'}}" name="medidasIntervencion[]" value="eliminar" data-open="reveal-eliminarIntervencion" <?php echo ($peligro->eliminar != "N/A")?"checked  disabled":""?> >
                    <label for="check-eliminarInter" style="color:gray">Eliminar</label>
                </div>
                <div class="columns small-12 medium-4 end">
                    <input type="checkbox" id="check-sustituirInter" class="{{($peligro->sustituir != 'N/A')?:'check-medidas disable-check'}}" name="medidasIntervencion[]" value="sustituir" data-open="reveal-sustituirIntervencion" <?php echo ($peligro->sustituir != "N/A")?"checked disabled":""?>>
                    <label class="label-check" for="check-sustituirInter" style="color:gray">Sustituir</label>
                </div>
                <div class="columns small-12 medium-4 end">
                    <input type="checkbox" id="check-contIngInter" class="{{($peligro->ingenieria != 'N/A')?:'check-medidas disable-check'}}" name="medidasIntervencion[]" value="ingenieria" data-open="reveal-contIngIntervencion" <?php echo ($peligro->ingenieria != "N/A")?"checked disabled":""?>>
                    <label class="label-check" for="check-contIngInter" style="color:gray">Control de Ingenieria</label>
                </div>

                <div class="columns small-12 medium-4 end">
                    <input type="checkbox" id="check-contAdmonInter" class="{{($peligro->administrativos != 'N/A')?:'check-medidas disable-check'}}" name="medidasIntervencion[]" value="administrativos" data-open="reveal-contAdmonIntervencion" <?php echo ($peligro->administrativos != "N/A")?"checked disabled":""?>>
                    <label class="label-check" for="check-contAdmonInter" style="color:gray">Controles Administrativos</label>
                </div>

                <div class="columns small-12 medium-4 end">
                    <input type="checkbox" id="check-eppInter" class="{{($peligro->epp_herramientas != 'N/A')?:'check-medidas disable-check'}}" name="medidasIntervencion[]" value="epp_herramientas" data-open="reveal-contEppIntervencion" <?php echo ($peligro->epp_herramientas != "N/A")?"checked disabled":""?>>
                    <label class="label-check" for="check-eppInter" style="color:gray">EPP, Herramientas</label>
                </div>
            </div>
            <div class="row text-center">
                <div class="columns small-12" data-tabs="">
                    <br/><br/>
                    <a class="button small" href="{{ route('criterios-revaloracion',['id'=>$peligro])}}">Anterior</a>
                    <a class="button small alert" data-open="modal-confirm-cancelValoracion">Cancelar</a>
                    <input type="submit" value="Siguiente" class="button small success"/>
                    <a class="button small success" href="{{ route('finalizar-revaloracion',['idPeligro'=>$peligro]) }}">Finalizar Revaloración</a>
                </div>
            </div>
        </form>
    </div>
</div>

@include('analissta.Valoracion.Revaloracion.modalCancelarRevaloracion')
@include('analissta.Valoracion.scriptsValoracion')
@endsection