@extends('analissta.layouts.appProgramarMedida')
@section('content')
<?php
    use App\Proceso;
    use App\Peligro;
    
    $proceso = Proceso::find($actividad->proceso_id);
    $peligro = Peligro::find(session('idPeligro'));
    
    $xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
    $xpath_nombrePeligro = $xml_GTC45->xpath("//peligros/clasificacion[id=$peligro->clasificacion]");
    $xpath_descPeligro = $xml_GTC45->xpath("//peligros/clasificacion[id=$peligro->clasificacion]/listDescripciones/descripcion[id=$peligro->categoria]");
    $clasificacionPeligro = $xpath_nombrePeligro[0]->nombre;
    $categoriaPeligro = $xpath_descPeligro[0]->nombre;
    
    $xml_ND = simplexml_load_file(base_path("archivosXML/ValoracionPeligro/xml_Tablas_ND.xml"));
    
    $peligroBD = Peligro::find(session('idPeligro'));
    $efecto = $peligroBD->efectoPersona;
    
    $ndCPbd = $neCPbd = $ncCPbd = $npCPbd = $nriCPbd=0;
    $ndLPbd = $neLPbd = $ncLPbd = $npLPbd = $nriLPbd=0;
        
    if($peligroBD->cortoPlazo){
        $ndCPbd = $peligroBD->cortoPlazo->nd;
        $neCPbd = $peligroBD->cortoPlazo->ne;
        $ncCPbd = $peligroBD->cortoPlazo->nc;
        $npCPbd = $peligroBD->cortoPlazo->np;
        $nriCPbd = $peligroBD->cortoPlazo->nri;      
    }
    
    if($peligroBD->largoPlazo){
        $ndLPbd = $peligroBD->largoPlazo->nd;
        $neLPbd = $peligroBD->largoPlazo->ne;
        $ncLPbd = $peligroBD->largoPlazo->nc;
        $npLPbd = $peligroBD->largoPlazo->np;
        $nriLPbd = $peligroBD->largoPlazo->nri;      
    }
?>

<div class="row">
    <div class="text-center"><h5><b>Proceso | {{ ucwords($proceso->nombre) }}</b></h5></div>
    <div class="text-center"><b>Actividad | {{ ucwords($actividad->nombre) }}</b></div>
</div>
<div class="row">
    <div class="columns small-12 medium-8 small-centered">
        <form id="frm-nuevoPeligroValoracion" name="frm-nuevoPeligroValoracion" method="POST" action="{{ route('valoracion-peligro',['idActividad'=>$actividad->id]) }}">
            {{ csrf_field() }}  
            <input type="hidden" id="txt-efectoPersona" readonly="true" value="{{ $peligro->efectoPersona}}"/>
            <div class="row columns text-center" >
                <div style="text-decoration: underline"><b>VALORACION PELIGRO </b></div>
                <div>{{ $clasificacionPeligro }} - {{ $categoriaPeligro }}</div>
            </div>
            <div class="row columns text-center" style="text-decoration: underline">
                <b>VALORACION DEL RIESGO</b>
            </div>
            @include('analissta.Asesores.crearEmpresa.errors')
            <br/>
            @if($peligro->efectoPersona === 'Corto Plazo' || $peligro->efectoPersona === 'Largo Plazo')
                <?php
                    if($peligro->efectoPersona === 'Corto Plazo'){
                        $textoEfecto = "Accidentes de Trabajo";
                    }
                    if($peligro->efectoPersona === 'Largo Plazo'){
                        $textoEfecto = "Enfermedades Laborales";
                    }
                ?>
            <div class="row">
                <div class="columns small-12 text-center" style="background:orange;color:white">
                    <b> {{ $textoEfecto }} </b>
                </div>
            </div>
            <br/>
            
            <div class="row">
                <div class="columns small-12 medium-2" style="font-size: 12px;">
                    <b>Nivel Deficiencia (ND): </b>
                </div>
                <div class="columns small-10 medium-4 end">
                    <input type="text" name="nd" class="input-nd-valoracion" id="nd-1" style="font-size: 12px;"  readonly="true" required="true" value="<?php echo ($peligro->efectoPersona === 'Corto Plazo')?$ndCPbd:$ndLPbd?>"/>
                    <span><a class="button tiny" data-open="reveal-1-ND">Valorar</a></span>
                    <div id="reveal-1-ND" class="reveal" data-reveal="" style="font-size: 12px;">
                        <?php
                            if($peligro->efectoPersona === 'Corto Plazo'){
                                $nds = $xml_ND->xpath("//nd/NDestandar/nivel");
                                $textDescripcion="descripcion";
                                $ndComparar = $ndCPbd;
                                $neComparar = $neCPbd;
                                $ncComparar = $ncCPbd;
                                $npComparar = $npCPbd;
                                $nriComparar = $nriCPbd;
                            }
                            if($peligro->efectoPersona === 'Largo Plazo'){
                                $nds = $xml_ND->xpath("//nd/NDhigienico/clasificacion[id=$peligro->clasificacion]/descripcion[item/id=$peligro->categoria]/nivel");
                                $textDescripcion="descNivel";
                                $ndComparar = $ndLPbd;
                                $neComparar = $neLPbd;
                                $ncComparar = $ncLPbd;
                                $npComparar = $npLPbd;
                                $nriComparar = $nriLPbd;
                            }
                        ?>
                        <div class="row columns text-center" style="font-size:14px">
                            <b>Valoración del Riesgo para {{ $textoEfecto }}</b>
                        </div>
                            @if($peligro->efectoPersona === 'Largo Plazo')
                            <div class="row columns text-center" style="font-size:14px">
                                <b>{{ $clasificacionPeligro }} - {{ $categoriaPeligro }}</b>
                            </div>
                            @endif
                        <br/><br/>
                        @foreach($nds as $nd)
                        <div class="row">
                            <div class="columns small-1 medium-1">
                                <input <?php echo ($nd->valor == $ndComparar )?"checked":""?> type="radio" name="nd-modal-1" id="{{$nd->valor}}" value="{{$nd->valor}}" onclick="$('#nd-1').val(this.value);$('#reveal-1-ND').foundation('close'); calculosValoracion('1','{{$peligro->efectoPersona }}')"/>
                            </div>
                            <div class="columns small-11 medium-2" >
                                <label for="{{$nd->valor}}" style="font-size: 11px"><b>{{ $nd->texto }}</b></label>
                            </div>
                            <div class="columns small-12 medium-9">
                                <label for="{{$nd->valor}}" style="font-size: 12px">{{$nd->$textDescripcion}}</label>
                            </div>
                        </div>
                        <br/>
                        @endforeach
                        <br/>
                        <div class="row columns text-center">
                            <!--<a class="button tiny" onclick="seleccionarND('')">Seleccionar</a>-->
                            <a class="button small alert" data-close="">Cancelar</a>
                        </div>
                        <!--<select name="ndPeligro" id="ndPeligro" style="font-size: 12px;" onchange="calcularNP()"/>-->
                        <button class="close-button" data-close="" aria-label="Close modal" type="button">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">    
                <div class="columns small-12 medium-2" style="font-size: 12px;">
                    <b>Nivel Exposición(NE): </b>
                </div>
                <div class="columns small-12 medium-4 end">
                    <select name="ne" id="ne-1" class="input-valoracion" style="font-size: 12px;" onchange="calculosValoracion('1','{{$peligro->efectoPersona }}')" required="true">
                        <option value="">Seleccione</option>
                        <option value="1" <?php echo $neComparar === "1"?"selected":""?>>
                            Eventual (1)
                        </option>
                        <option value="2" <?php echo $neComparar === "2"?"selected":""?>>
                            Alguna vez, período corto (2)
                        </option>
                        <option value="3" <?php echo $neComparar === "3"?"selected":""?>>
                            Varias veces, período muy corto (3)
                        </option>
                        <option value="4" <?php echo $neComparar === "4"?"selected":""?>>
                            Sin interrupción, periodos largos (4)
                        </option>
                    </select>
                </div>

                <div class="columns small-12 medium-2" style="font-size: 12px;">
                    <b>Nivel Consecuencia (NC): </b> 
                </div>
                <div class="columns small-12 medium-4 end">
                    <select name="nc" id="nc-1" class="input-valoracion" style="font-size: 12px;" onchange="calculosValoracion('1','{{$peligro->efectoPersona }}')" required="true">
                        <option value="">Seleccione</option>
                        <option value="10" <?php echo $ncComparar === "10"?"selected":""?>>
                            Lesiones Leves (10)
                        </option>
                        <option value="25" <?php echo $ncComparar === "25"?"selected":""?>>
                            Incapacidad Temporal (25)
                        </option>
                        <option value="60" <?php echo $ncComparar === "60"?"selected":""?>>
                            Incapacidad Permanente, invalidez (60)
                        </option>
                        <option value="100" <?php echo $ncComparar === "100"?"selected":""?>>
                            Muerte (100)
                        </option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12" style="font-size: 12px;">
                    <b>Nivel Probabilidad (NP): </b>
                    <span id="npPeligro-1">{{$npComparar}}</span>
                </div>

                <div class="columns small-12" style="font-size: 12px;">
                    <b>Interpretacion NP: </b>
                    <span id="npIntPeligro-1"></span>
                </div>

            </div>
            <div class="row">
                <div class="columns small-12" style="font-size: 12px;">
                    <b>Nivel Riesgo Inicial (NRi): </b>
                    <span id="nriPeligro-1">{{$nriComparar}}</span>
                </div>
                <div class="columns small-12" style="font-size: 12px;">
                    <b>Interpretación NRi: </b>
                    <span id="nriIntPeligro-1"></span>
                </div>
            </div>
            <br/>
            <div class="row text-center">
                <div id="div-alerta-plan-gestion-1" class="columns small-12 medium-8 small-centered aviso-alerta-plan" style="background:red;color:white; font-size: 12px;">
                    <!--Aca va el texto de alerta por si Nri es I o II-->
                </div>
            </div>
            @endif
            
            @if($peligro->efectoPersona === 'Corto y Largo Plazo')
            <div class="row">
                <div class="columns small-12 medium-6" style="font-size: 12px;">
                    <div class="row">
                        <div class="columns small-12 text-center" style="background:orange;color:white">
                            <b>Accidentes de Trabajo</b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Nivel Deficiencia (ND): </b>
                        </div>
                        <div class="columns small-10 end">
                            <input type="text" name="NDcp" id="nd-2" class="input-nd-valoracion" style="font-size: 12px;" readonly="true" required="true" value='{{$ndCPbd}}'>
                            
                            <a class="button tiny" data-open="reveal-ND-2">Valorar</a>
                            <div id="reveal-ND-2" class="reveal" data-reveal="">
                                <?php
                                    $nds = $xml_ND->xpath("//nd/NDestandar/nivel");
                                ?>
                                <div class="row columns text-center" style="font-size:14px">
                                    <b>Valoración del Riesgo para Accidentes de Trabajo</b>
                                </div>
                                <br/><br/>
                                @foreach($nds as $nd)
                                <div class="row">
                                    <div class="columns small-1 medium-1">
                                        <input <?php echo $nd->valor == $ndCPbd?"checked":"" ?> type="radio" name="nd-modal-2" id="nd-modal-2-{{$nd->valor}}" value="{{$nd->valor}}" onclick="$('#nd-2').val(this.value);$('#reveal-ND-2').foundation('close'); calculosValoracion('2','Corto Plazo')"/>
                                    </div>
                                    <div class="columns small-11 medium-2" >
                                        <label for="nd-modal-2-{{$nd->valor}}" style="font-size: 11px"><b>{{ $nd->texto }}</b></label>
                                    </div>
                                    <div class="columns small-12 medium-9">
                                        <label for="nd-modal-2-{{$nd->valor}}" style="font-size: 12px">{{$nd->descripcion}}</label>
                                    </div>
                                </div>
                                <br/>
                                @endforeach
                                
                                <button class="close-button" data-close="" aria-label="Close modal" type="button">
                                    <span aria-hidden="true">x</span>
                                </button>
                            </div>
                        </div>
                            <!--<select name="ndPeligro" id="ndPeligro" style="font-size: 12px;" onchange="calcularNP()">-->
                    </div>    
                    <div class="row">
                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Nivel Exposición(NE): </b>
                        </div>
                        <div class="columns small-10 end">
                            <select name="NEcp" id="ne-2" class="input-valoracion" required="true" style="font-size: 12px;"  onchange="calculosValoracion('2','Corto Plazo')">
                                <option value="">Seleccione</option>
                                <option value="1" <?php echo ($neCPbd === "1")?"selected":"" ?>>Eventual (1)</option>
                                <option value="2" <?php echo ($neCPbd === "2")?"selected":"" ?>>Alguna vez, período corto (2)</option>
                                <option value="3" <?php echo ($neCPbd === "3")?"selected":"" ?>>Varias veces, período muy corto (3)</option>
                                <option value="4" <?php echo ($neCPbd === "4")?"selected":"" ?>>Sin interrupción, periodos largos (4)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Nivel Consecuencia (NC): </b>
                        </div>
                        <div class="columns small-10 end">
                            <select name="NCcp" id="nc-2" class="input-valoracion" required="true" style="font-size: 12px;"  onchange="calculosValoracion('2','Corto Plazo')">
                                <option value="">Seleccione</option>
                                <option value="10" <?php echo ($ncCPbd === "10")?"selected":"" ?>>Lesiones Leves (10)</option>
                                <option value="25" <?php echo ($ncCPbd === "25")?"selected":"" ?>>Incapacidad Temporal (25)</option>
                                <option value="60" <?php echo ($ncCPbd === "60")?"selected":"" ?>>Incapacidad Permanente, invalidez (60)</option>
                                <option value="100" <?php echo ($ncCPbd === "100")?"selected":"" ?>>Muerte (100)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Nivel Probabilidad (NP): </b><span id="npPeligro-2" >{{$npCPbd}}</span>
                        </div>
                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Interpretacion NP: </b><span id="npIntPeligro-2" ></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Nivel Riesgo Inicial (NRi): </b><span id="nriPeligro-2" >{{$nriCPbd}}</span>
                        </div>

                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Interpretación NRi: </b><span id="nriIntPeligro-2" ></span>
                        </div>
                    </div>
                    <br/>
                    <div class="row text-center">
                        <div id="div-alerta-plan-gestion-2" class="columns small-12 small-centered aviso-alerta-plan" style="background:red;color:white; font-size: 12px;">
                            <!--Aca va el texto de alerta por si Nri es I o II-->
                        </div>
                    </div>
                </div>
                <div class="columns small-12 medium-6" style="font-size: 12px;">
                    <div class="row">
                        <div class="columns small-12 text-center" style="background:#01549b;color:white">
                            <b>Enfermedades Laborales</b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Nivel Deficiencia (ND): </b>
                        </div>
                        <div class="columns small-10 end">
                        <input type="text" name="NDlp" id="nd-3" class="input-nd-valoracion" style="font-size: 12px;" readonly="true" required="true" value="{{ $ndLPbd }}">

                        <a class="button tiny" data-open="reveal-ND-3">Valorar</a>
                        
                        <div id="reveal-ND-3" class="reveal" data-reveal="">
                            <?php
                                $nds = $xml_ND->xpath("//nd/NDhigienico/clasificacion[id=$peligro->clasificacion]/descripcion[item/id=$peligro->categoria]/nivel");
                            ?>  
                            <div class="row columns text-center" style="font-size:14px">
                                <b>Valoración del Riesgo para Enfermedades Laborales</b>
                            </div>
                            <div class="row columns text-center" style="font-size:14px">
                                <b>{{ $clasificacionPeligro }} - {{ $categoriaPeligro }}</b>
                            </div>
                             <br/><br/>
                            @foreach($nds as $nd)
                            <div class="row">
                                <div class="columns small-1 medium-1">
                                    <input <?php echo $nd->valor == $ndLPbd?"checked":"" ?> type="radio" name="nd-modal-3" id="nd-modal-3-{{$nd->valor}}" value="{{$nd->valor}}" onclick="$('#nd-3').val(this.value);$('#reveal-ND-3').foundation('close');calculosValoracion('3','Largo Plazo')"/>
                                </div>
                                <div class="columns small-11 medium-2" >
                                    <label for="nd-modal-3-{{$nd->valor}}" style="font-size: 11px"><b>{{ $nd->texto }}</b></label>
                                </div>
                                <div class="columns small-12 medium-9">
                                    <label for="nd-modal-3-{{$nd->valor}}" style="font-size: 12px">{{$nd->descNivel }}</label>
                                </div>
                            </div>
                            <br/>
                            @endforeach
                                <button class="close-button" data-close="" aria-label="Close modal" type="button">
                                    <span aria-hidden="true">x</span>
                                </button>
                            </div>
                        </div>
                    </div>    
                    <div class="row">
                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Nivel Exposición(NE): </b>
                        </div>
                        <div class="columns small-10 end">
                            <select name="NElp" id="ne-3" class="input-valoracion" required="true" style="font-size: 12px;" onchange="calculosValoracion('3','Largo Plazo')">
                                <option value="">Seleccione</option>
                                <option value="1" <?php echo ($neLPbd === "1")?"selected":"" ?>>Eventual (1)</option>
                                <option value="2" <?php echo ($neLPbd === "2")?"selected":"" ?>>Alguna vez, período corto (2)</option>
                                <option value="3" <?php echo ($neLPbd === "3")?"selected":"" ?>>Varias veces, período muy corto (3)</option>
                                <option value="4" <?php echo ($neLPbd === "4")?"selected":"" ?>>Sin interrupción, periodos largos (4)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Nivel Consecuencia (NC): </b>
                        </div>
                        <div class="columns small-10 end">
                            <select name="NClp" id="nc-3" class="input-valoracion" required="true" style="font-size: 12px;" onchange="calculosValoracion('3','Largo Plazo')">
                                <option value="">Seleccione</option>
                                <option value="10" <?php echo ($ncLPbd === "10")?"selected":"" ?>>Lesiones Leves (10)</option>
                                <option value="25" <?php echo ($ncLPbd === "25")?"selected":"" ?>>Incapacidad Temporal (25)</option>
                                <option value="60" <?php echo ($ncLPbd === "60")?"selected":"" ?>>Incapacidad Permanente, invalidez (60)</option>
                                <option value="100" <?php echo ($ncLPbd === "100")?"selected":"" ?>>Muerte (100)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Nivel Probabilidad (NP): </b> <span id="npPeligro-3">{{ $npLPbd }}</span>
                        </div>

                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Interpretacion NP: </b> <span id="npIntPeligro-3"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Nivel Riesgo Inicial (NRi): </b> <span id="nriPeligro-3">{{ $nriLPbd }}</span>
                        </div>

                        <div class="columns small-12" style="font-size: 12px;">
                            <b>Interpretación NRi: </b> <span id="nriIntPeligro-3"></span>
                        </div>
                    </div>
                    <br/>
                    <div class="row text-center">
                        <div id="div-alerta-plan-gestion-3" class="columns small-12 small-centered aviso-alerta-plan" style="background:red;color:white; font-size: 12px;">
                            <!--Aca va el texto de alerta por si Nri es I o II-->
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <br/>
            <div class="row text-center">
                <div class="columns small-12" data-tabs="">
                    <a class="button small" href="{{ route('controles-peligro',['idActividad'=>$actividad->id])}}">Anterior</a>
                    <xsl:text> </xsl:text>
                    <a class="button small alert" data-open="modal-confirm-cancelValoracion">Cancelar</a>
                    <xsl:text> </xsl:text>
                    <input type="submit" value="Siguiente" class="button small success"/>
                </div>
            </div>
        </form>
    </div>
</div>

@include('analissta.Valoracion.modalCancelarValoracion')
@include('analissta.Valoracion.scriptsValoracion')
@endsection