@extends('analissta.layouts.appProgramarMedida')
@section('content')
<?php
    use App\Proceso;
    use App\Peligro;
    $proceso = Proceso::find($actividad->proceso_id);
    
    $xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    $clasificacionesPeligro = $xml_GTC45->xpath("//peligros/clasificacion");
    
    $clasificacionBD = $descripcionBD = $subDescripcionBD="0" ;
    $especificacionBD=$factorHBD="";
    $fuentesBD = [0];
    
    if(session('idPeligro')){
        $peligroBD = Peligro::find(session('idPeligro'));
        $clasificacionBD = $peligroBD->clasificacion ;
        $descripcionBD = $peligroBD->categoria;
        $subDescripcionBD = $peligroBD->subCategoria;
        $fuentesBD = explode(",",$peligroBD->fuentes);
        $especificacionBD = $peligroBD->especificacion;
        $factorHBD = $peligroBD->factorHumano;
    }
    $vrClasificacion = (old('clasificacion')!==null)? old('clasificacion'):$clasificacionBD;
    $vrDescripcion = (old('descripcion')!==null)? old('descripcion'):$descripcionBD;
    $vrSubDescripcion = (old('subdescripcion')!==null)? old('subdescripcion'):$subDescripcionBD;
    $vrEspecificacion = (old('especificacion')!==null)? old('especificacion'):$especificacionBD;
    $vrFactorH = (old('factorH')!==null)? old('factorH'):$factorHBD;
    $arrFuentes = (old('fuentes')!==null)?old('fuentes'):$fuentesBD;
    $vrFuentes = implode(",",$arrFuentes);
?>

<form id="frm-nuevoPeligroDataGral" name="frm-nuevoPeligroDataGral" method="POST" action="{{ route('guardar-identificacion-peligro',['idActividad'=>$actividad->id]) }}">
    {{ csrf_field() }}
    <br/>
    <div class="row columns text-center" style="text-decoration: underline">
        <h5>Identificación del Peligro</h5>
    </div>
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row">
        <div class="columns small-3 medium-4 ">
            <label for="clasifNuevoPeligro" class="text-right middle"><b>Clasificación: </b></label>
        </div>
        <div class="columns small-9 medium-5 end">
            <select name="clasificacion" id="clasifNuevoPeligro" required="true" onchange="mostrarDescripciones(this.value,null,null)">
                <option value="">Seleccione..</option>
                @foreach($clasificacionesPeligro as $clasificacion)
                <option value="{{ $clasificacion->id }}" <?php echo ($vrClasificacion == $clasificacion->id)?"selected":"" ?>>{{$clasificacion->nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="columns small-3 medium-4 ">
            <label for="descripcion" class="text-right middle"><b>Descripción: </b></label>
        </div>
        <div class="columns small-9 medium-5 end">
            <fieldset>
                <div id="div-descripcionesPeligros"><div><br/><i style="color:#ff4d4d;">Debe Seleccionar una Clasificación</i></div></div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="columns small-3 medium-4 ">
            <label for="fuentes" class="text-right middle"><b>Fuentes: </b></label>
        </div>
        <div class="columns small-9 medium-5 end">
            <fieldset>
                <br/>
                <div id="div-fuentesPeligros">
                    <div><br/><i style="color:#ff4d4d;">Debe Seleccionar una Descripción</i></div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <br/>
        <div class="columns small-3 medium-4 ">
            <label for="especificacion" class="text-right middle"><b>Especificación: </b></label>
        </div>
        <div class="columns small-9 medium-5 end">
            <textarea style="height: 70px; font-size: 14px" id="especificacion" name="especificacion" required="true" placeholder="Detalles de las fuentes que generan el peligro, ejm: Bomba B-101 Planta de agua">{{ $vrEspecificacion }}</textarea>
        </div>
    </div>
    <div class="row">
        <div class="columns small-3 medium-4 ">
            <label for="factorH" class="text-right middle"><b>Factor Humano: </b></label>
        </div>
        <div class="columns small-9 medium-5 end">
            <textarea style="height: 70px; font-size: 14px" id="factorH" name="factorH" placeholder="Comportamientos, aptitudes y otros factores humanos que generan peligro">{{ $vrFactorH }}</textarea>
        </div>
    </div>
    <div class="row text-center">
        <div class="columns small-12" data-tabs="">
            <a class="button small alert" data-open="modal-confirm-cancelValoracion">Cancelar</a>
            <xsl:text> </xsl:text>
            <input type="submit" value="Siguiente" class="button small success" />
        </div>
    </div>
</form>    

<form method="POST" action="{{route('buscar-descripcion-peligro',['idClasificacion'=>':id','idDescripcion'=>':idDesc','idSubDesc'=>':idSubDesc'])}}" accept-charset="UTF-8" id="form-buscar-descripcion">
    {{ csrf_field() }}
    <input type="hidden" class="hide" id="vrDescripcion" name="vrDescripcion" value="{{ $vrDescripcion }}">
    <input type="hidden" class="hide" id="vrSubDescripcion" name="vrSubDescripcion" value="{{ $vrSubDescripcion }}">

</form>

<form method="POST" action="{{route('buscar-fuentes-peligro',['idClasificacion'=>':idClasificacion','idCategoria'=>':idCategoria','idSubCategoria'=>':idSubCategoria','strFuentes'=>':fuentes'])}}" accept-charset="UTF-8" id="form-buscar-fuentes">
    {{ csrf_field() }}
   <input type="hidden" id="vrFuentes" name="vrFuentes" value="{{ $vrFuentes }}">
</form>
@include('analissta.Valoracion.modalCancelarValoracion')
@include('analissta.Valoracion.scriptsValoracion')
@endsection