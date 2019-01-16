@extends('analissta.layouts.appProgramarMedida')
@section('content')
<?php
    use App\Proceso;
    use App\Peligro;
    use App\RequisitosLegale;
    
    $proceso = Proceso::find($actividad->proceso_id);
    $peligro = Peligro::find(session('idPeligro'));
    
    $xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    $xpath_nombrePeligro = $xml_GTC45->xpath("//peligros/clasificacion[id=$peligro->clasificacion]");
    $xpath_descPeligro = $xml_GTC45->xpath("//peligros/clasificacion[id=$peligro->clasificacion]/listDescripciones/descripcion[id=$peligro->categoria]");
    $clasificacionPeligro = $xpath_nombrePeligro[0]->nombre;
    $categoriaPeligro = $xpath_descPeligro[0]->nombre;
    
    $xml_ND = simplexml_load_file(base_path("archivosXML/ValoracionPeligro/xml_Tablas_ND.xml"));
    
    
    $efecto = $peligro->efectoPersona;
    
    $clienteCPbd = $contratistaCPbd = $directosCPbd = $visitantesCPbd = $consecuenciaCPbd=0;
    $reqLegalCPbd=$reqLegalLPbd="";
    $clienteLPbd = $contratistaLPbd = $directosLPbd = $visitantesLPbd = $consecuenciaLPbd=0;
    
    if($peligro->cortoPlazo){
        $clienteCPbd = $peligro->cortoPlazo->cliente;
        $contratistaCPbd = $peligro->cortoPlazo->contratista;
        $directosCPbd = $peligro->cortoPlazo->directos;
        $visitantesCPbd = $peligro->cortoPlazo->visitantes;
        $consecuenciaCPbd = $peligro->cortoPlazo->peorConsecuencia;      
        $reqLegalCPbd = $peligro->cortoPlazo->reqLegal;
        $descReqLegalCPbd = RequisitosLegale::where('tipo_id',$peligro->cortoPlazo->id)
                ->where('tipo_texto','Corto Plazo')
                ->get();
    }
    
    if($peligro->largoPlazo){
        $clienteLPbd = $peligro->largoPlazo->cliente;
        $contratistaLPbd = $peligro->largoPlazo->contratista;
        $directosLPbd = $peligro->largoPlazo->directos;
        $visitantesLPbd = $peligro->largoPlazo->visitantes;
        $consecuenciaLPbd = $peligro->largoPlazo->peorConsecuencia;      
        $reqLegalLPbd = $peligro->largoPlazo->reqLegal;      
        $descReqLegalLPbd = RequisitosLegale::where('tipo_id',$peligro->largoPlazo->id)
                ->where('tipo_texto','Largo Plazo')
                ->get();
    }
?>

<div class="row">
    <div class="text-center"><h5><b>Proceso | {{ ucwords($proceso->nombre) }}</b></h5></div>
    <div class="text-center"><b>Actividad | {{ ucwords($actividad->nombre) }}</b></div>
</div>
<div class="row">
    <div class="columns small-12 medium-8 small-centered">
        <form id="frm-nuevoPeligroCriterios" name="frm-nuevoPeligroCriterios" method="POST" action="{{ route('criterios-peligro',['idActividad'=>$actividad->id]) }}">
            {{ csrf_field() }}  
            <div class="row columns text-center" >
                <div style="text-decoration: underline"><b>VALORACION PELIGRO </b></div>
                <div>{{ $clasificacionPeligro }} - {{ $categoriaPeligro }}</div>
            </div>
            <div class="row columns text-center" style="text-decoration: underline">
                <b>CRITERIOS PARA CONTROLAR</b>
            </div>
            @include('analissta.Asesores.crearEmpresa.errors')
            <br/>
            @if($peligro->efectoPersona === 'Corto Plazo' || $peligro->efectoPersona === 'Largo Plazo')
                <div class="row">
                    <div class="columns small-12" style="font-size: 12px;">
                        <b>No. Trabajadores Expuestos: </b>
                    </div>
                    <div class="columns small-6 medium-3 end">
                        <label for="numbCliente-1" style="font-size: 12px;">Cliente</label>
                        <input type="number" name="cliente" id="numbCliente-1" style="font-size: 12px; width: 60%" required="true" min="0" step="1" value="<?php echo ($efecto == 'Corto Plazo')? $clienteCPbd :$clienteLPbd ?>">
                    </div>    
                    <div class="columns small-6 medium-3 end" >
                        <label for="numbContratista-1" style="font-size: 12px;">Contratista</label>
                        <input type="number" name="contratista" id="numbContratista-1" style="font-size: 12px; width: 60%" required="true" min="0" step="1" value="<?php echo ($efecto == 'Corto Plazo')? $contratistaCPbd :$contratistaLPbd ?>">
                    </div>    
                    <div class="columns small-6 medium-3 end" >
                        <label for="numbPropio-1" style="font-size: 12px;">Trabajador Directo</label>
                        <input type="number" list="lista-1" name="directos" id="numbPropio-1" style="font-size: 12px; width: 60%" required="true" min="0" step="1" value="<?php echo ($efecto == 'Corto Plazo')? $directosCPbd :$directosLPbd ?>" max='{{ $empresa->totalEmpleados }}'>
                        <datalist id='lista-1'>
                            <option value="{{ $empresa->totalEmpleados }}">Total empleados</option>
                        </datalist>
                    </div>    
                    <div class="columns small-6 medium-3 end" >
                        <label for="numbVisitante-1" style="font-size: 12px;">Visitantes</label>
                        <input type="number" name="visitante" id="numbVisitante-1" style="font-size: 12px; width: 60%" required="true" min="0" step="1" value="<?php echo ($efecto == 'Corto Plazo')? $visitantesCPbd :$visitantesLPbd ?>">
                    </div>    
                </div>
                <br/>
                <div class="row">
                    <div class="columns small-12 medium-2 end" style="font-size: 12px;">
                        <b>Peor Consecuencia: </b>
                    </div>
                    <div class="columns small-12 medium-9 end" >
                        <textarea id="peorConsecuencia-1" name="consecuencia" placeholder="Cual es la peor consecuencia" style="font-size:14px; height: 80px;"><?php echo ($efecto == 'Corto Plazo')? $consecuenciaCPbd :$consecuenciaLPbd ?></textarea>
                    </div>
                </div>
                <?php
                    if($efecto == 'Corto Plazo'){ 
                        $vrReqLegal = $reqLegalCPbd;
                    }
                    else{
                        $vrReqLegal = $reqLegalLPbd;
                    }
                ?>
                <div class="row">
                    <div class="columns small-12 end" style="font-size: 12px;">
                        <b>Requisito Legal Asignado: </b>
                        <input type="radio" required="true" id="reqLegalCriterioSI" name="legal" value="Si" data-numeroSelect="1" class="selectRecLegal" onclick="$('#row-descLegalCriterio-1').removeClass('hide')" <?php echo ($vrReqLegal == "Si")?"Checked":""?>>
                        <label for="reqLegalCriterioSI">SI</label>
                        <input type="radio" required="true" id="reqLegalCriterioNO" name="legal" value="No" data-numeroSelect="1" class="selectRecLegal" onclick="$('#row-descLegalCriterio-1').addClass('hide')"  <?php echo ($vrReqLegal == "No")?"Checked":""?> >
                        <label for="reqLegalCriterioNO">NO</label>
                    </div>
                </div>
                <div  id="row-descLegalCriterio-1" class="row hide">
                    <div class="columns small-12 medium-2 end" style="font-size: 12px;">
                        <b>Describa Requisito: </b>
                    </div>
                    <div class="columns small-12 medium-9 end" >
                        <textarea id="descLegalCriterio-1" name="descLegal"  style="font-size:14px; height: 80px;" placeholder="Describa el requisito legal: ejm Resolucion 888 de 2012, art 13" >@if(isset($descReqLegalLPbd[0])){{ $descReqLegalLPbd[0]->requisitos }}@elseif(isset($descReqLegalCPbd[0])){{ $descReqLegalCPbd[0]->requisitos }}@endif</textarea>
                    </div>
                </div>
            @endif
            
            @if($peligro->efectoPersona === 'Corto y Largo Plazo')
                <div class="row">
                    <div class="columns small-12 medium-6 " style="border-right: 2px solid gray">
                        <div class="row">
                            <div class="columns small-12 text-center" style="font-size:14px">
                                <b>Accidentes de Trabajo</b>
                            </div>
                        </div>

                        <div class="row">
                            <div class="columns small-12" style="font-size: 12px;">
                                <b>No. Trabajadores Expuestos: </b>
                            </div>
                            <div class="columns small-6 end">
                                <label for="numbCliente-2" style="font-size: 12px;">Cliente</label>
                                <input type="number" name="clienteCP" id="numbCliente-2" style="font-size: 12px; width: 60%" required="true" min="0" step="1" value="{{$clienteCPbd }}">
                            </div>    
                            <div class="columns small-6 end" >
                                <label for="numbContratista-2" style="font-size: 12px;">Contratista</label>
                                <input type="number" name="contratistaCP" id="numbContratista-2" style="font-size: 12px; width: 60%" required="true" min="0" step="1" value="{{$contratistaCPbd }}">
                            </div>    
                            <div class="columns small-6 end" >
                                <label for="numbPropio-2" style="font-size: 12px;">Trabajador Directo</label>
                                <input type="number" list="lista-corto" name="directosCP" id="numbPropio-2" style="font-size: 12px; width: 60%" required="true" min="0" step="1" value="{{$directosCPbd}}" max='{{ $empresa->totalEmpleados }}'>
                                <datalist id='lista-corto'>
                            <option value="{{ $empresa->totalEmpleados }}">Total empleados</option>
                        </datalist>
                            </div>    
                            <div class="columns small-6 end" >
                                <label for="numbVisitante-2" style="font-size: 12px;">Visitantes</label>
                                <input type="number" name="visitanteCP" id="numbVisitante-2" style="font-size: 12px; width: 60%" required="true" min="0" step="1" value="{{$visitantesCPbd}}">
                            </div>    
                        </div>
                        <br/>
                        <div class="row">
                            <div class="columns small-12 end" style="font-size: 12px;">
                                <b>Peor Consecuencia: </b>
                            </div>
                            <div class="columns small-12 end" >
                                <textarea id="peorConsecuencia-2" name="consecuenciaCP" placeholder="Cual es la peor consecuencia" style="font-size:14px; height: 80px;">{{$consecuenciaCPbd}}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 end" style="font-size: 12px;">
                                <b>Requisito Legal Asignado: </b>
                                <input type="radio" required="true" id="reqLegalCriterioSI-1" name="legalCP" value="Si" data-numeroSelect="2" class="selectRecLegal"  onclick="$('#row-descLegalCriterio-2').removeClass('hide')"  <?php echo ($reqLegalCPbd == "Si")?"Checked":""?>>
                                <label for="reqLegalCriterioSI-1">SI</label>
                                <input type="radio" required="true" id="reqLegalCriterioNO-1" name="legalCP" value="No" data-numeroSelect="2" class="selectRecLegal"  onclick="$('#row-descLegalCriterio-2').addClass('hide')"   <?php echo ($reqLegalCPbd == "No")?"Checked":""?>>
                                <label for="reqLegalCriterioNO-1">NO</label>
                            </div>
                        </div>
                        <?php
                        
                        ?>
                        <div id="row-descLegalCriterio-2" class="row hide">
                            <div class="columns small-12 end" style="font-size: 12px;">
                                <b>Describa Requisito: </b>
                            </div>
                            <div class="columns small-12 end" >
                                <textarea id="descLegalCriterio-2" name="descLegalCP"  style="font-size:14px; height: 80px;" placeholder="Describa el requisito legal: ejm Resolucion 888 de 2012, art 13" >@if(isset($descReqLegalCPbd[0])){{ $descReqLegalCPbd[0]->requisitos }}@endif</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="columns small-12 medium-6" >
                        <div class="row">
                            <div class="columns small-12 text-center" style="font-size:14px">
                                <b>Enfermedades Laborales</b>
                            </div>
                        </div>

                        <div class="row">
                            <div class="columns small-12" style="font-size: 12px;">
                                <b>No. Trabajadores Expuestos: </b>
                            </div>
                            <div class="columns small-6 end">
                                <label for="numbCliente-3" style="font-size: 12px;">Cliente</label>
                                <input type="number" name="clienteLP" id="numbCliente-3" style="font-size: 12px; width: 60%" required="true" min="0" step="1" value="{{$clienteLPbd}}">
                            </div>    
                            <div class="columns small-6 end" >
                                <label for="numbContratista-3" style="font-size: 12px;">Contratista</label>
                                <input type="number" name="contratistaLP" id="numbContratista-3" style="font-size: 12px; width: 60%" required="true" min="0" step="1" value="{{$contratistaLPbd}}">
                            </div>    
                            <div class="columns small-6 end" >
                                <label for="numbPropio-3" style="font-size: 12px;">Trabajador Directo</label>
                                <input type="number" list="lista-largo" name="directosLP" id="numbPropio-3" style="font-size: 12px; width: 60%" required="true" min="0" step="1" value="{{$directosLPbd}}" max='{{ $empresa->totalEmpleados }}'>
                                <datalist id='lista-largo'>
                            <option value="{{ $empresa->totalEmpleados }}">Total empleados</option>
                        </datalist>
                            </div>    
                            <div class="columns small-6 end" >
                                <label for="numbVisitante-3" style="font-size: 12px;">Visitantes</label>
                                <input type="number" name="visitanteLP" id="numbVisitante-3" style="font-size: 12px; width: 60%" required="true" min="0" step="1" value="{{$visitantesLPbd}}">
                            </div>    
                        </div>
                        <br/>
                        <div class="row">
                            <div class="columns small-12 end" style="font-size: 12px;">
                                <b>Peor Consecuencia: </b>
                            </div>
                            <div class="columns small-12 end" >
                                <textarea id="peorConsecuencia-3" name="consecuenciaLP" placeholder="Cual es la peor consecuencia" style="font-size:14px; height: 80px;">{{$consecuenciaLPbd}}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 end" style="font-size: 12px;">
                                <b>Requisito Legal Asignado: </b>
                                <input type="radio" required="true" id="reqLegalCriterioSI-2" name="legalLP" value="Si" data-numeroSelect="3" class="selectRecLegal" onclick="$('#row-descLegalCriterio-3').removeClass('hide')"   <?php echo ($reqLegalLPbd == "Si")?"Checked":""?>>
                                <label for="reqLegalCriterioSI-2">SI</label>
                                <input type="radio" required="true" id="reqLegalCriterioNO-2" name="legalLP" value="No" data-numeroSelect="3" class="selectRecLegal" onclick="$('#row-descLegalCriterio-3').addClass('hide')"   <?php echo ($reqLegalLPbd == "No")?"Checked":""?>>
                                <label for="reqLegalCriterioNO-2">NO</label>
                            </div>
                        </div>
                        <div id="row-descLegalCriterio-3" class='row hide'>
                            <div class="columns small-12 end" style="font-size: 12px;">
                                <b>Describa Requisito: </b>
                            </div>
                            <div class="columns small-12 end" >
                                <textarea id="descLegalCriterio-3" name="descLegalLP"  style="font-size:14px; height: 80px;" placeholder="Describa el requisito legal: ejm Resolucion 888 de 2012, art 13" >@if(isset($descReqLegalLPbd[0])){{ $descReqLegalLPbd[0]->requisitos }}@endif</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <br/>
            <div class="row text-center">
                <div class="columns small-12" data-tabs="">
                    <a class="button small" href="{{ route('valoracion-peligro',['idActividad'=>$actividad->id])}}">Anterior</a>
                    <a class="button small alert" data-open="modal-confirm-cancelValoracion">Cancelar</a>
                     
                    <input type="submit" value="Siguiente" class="button small success"/>
                </div>
            </div>
        </form>
    </div>
</div>

@include('analissta.Valoracion.modalCancelarValoracion')
@include('analissta.Valoracion.scriptsValoracion')
@endsection