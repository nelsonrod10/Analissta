<?php
use App\Http\Controllers\helpers;
?>
<br/>
<div class="row columns text-center">
    <h4>Revaloraciones</h4>
</div>
<div class="row">
    <div class="columns small-12 medium-8 small-centered end">
        <div class="text-center" style="border-bottom:1px solid gray"><b>Valoración</b></div>
        <?php
        if($peligro->efectoPersona === "Corto Plazo" || $peligro->efectoPersona === "Largo Plazo"):
            
            if($peligro->efectoPersona === "Corto Plazo"){
                $revaloraciones = $peligro->revaloracionCortoPlazo->where('tipo','revaloracion');
                
            }else{
                $revaloraciones = $peligro->revaloracionLargoPlazo->where('tipo','revaloracion');
                
            }
            
            foreach($revaloraciones as $revaloracion):
                
        ?>  
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Deficiencia (ND): </b>{{$revaloracion->nd}}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Exposición (NE): </b>{{$revaloracion->ne}}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Consecuencia (NC): </b>{{$revaloracion->nc}}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Probabilidad (NP): </b>{{helpers::interpretacionValoracion($revaloracion->np, "NP")}}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Riesgo Inicial (NRi): </b>{{helpers::interpretacionValoracion($revaloracion->nri, "NRI")}}
            </div>
        <?php
            endforeach;
        endif;

        if($peligro->efectoPersona === "Corto y Largo Plazo"):
            $revaloracionesCorto = $peligro->revaloracionCortoPlazo->where('tipo','revaloracion');
            $revaloracionesLargo = $peligro->revaloracionLargoPlazo->where('tipo','revaloracion');
        ?>
        <div class="columns small-12 medium-6">
            <div class="columns small-12"><b>Accidente de Trabajo</b></div>
            @foreach($revaloracionesCorto as $revaloracionCorto)
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Deficiencia (ND): </b>{{ $revaloracionCorto->nd }}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Exposición (NE): </b>{{ $revaloracionCorto->ne }}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Consecuencia (NC): </b>{{ $revaloracionCorto->nc }}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Probabilidad (NP): </b><?php echo helpers::interpretacionValoracion($revaloracionCorto->np, "NP")?>
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Riesgo Iniciao (NRi): </b><?php echo helpers::interpretacionValoracion($revaloracionCorto->nri, "NRI")?>
            </div>
            @endforeach
        </div>
        <div class="columns small-12 medium-6">
            <div class="columns small-12"><b>Enfermedad Laboral</b></div>
            @foreach($revaloracionesLargo as $revaloracionLargo)
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Deficiencia (ND): </b>{{ $revaloracionLargo->nd }}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Exposición (NE): </b>{{ $revaloracionLargo->ne }}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Consecuencia (NC): </b>{{ $revaloracionLargo->nc }}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Probabilidad (NP): </b><?php echo helpers::interpretacionValoracion($revaloracionLargo->np, "NP")?>
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Nivel Riesgo Iniciao (NRi): </b><?php echo helpers::interpretacionValoracion($revaloracionLargo->nri, "NRI")?>
            </div>
            @endforeach
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
                $criteriosRevaloraciones = $peligro->revaloracionCortoPlazo()->where('tipo','revaloracion');
                
            }else{
                $criteriosRevaloraciones = $peligro->revaloracionLargoPlazo()->where('tipo','revaloracion');
                
            }
            
            foreach($criteriosRevaloraciones as $revaloracionCriterio):
                $descRequisitos = RequisitosLegale::where('tipo_id',$revaloracionCriterio->id)
                            ->where('tipo_texto',$peligro->efectoPersona)
                            ->get();
        ?>  
            <div class="columns small-12"><b>Trabajadores Expuestos</b></div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Clientes: </b>{{$revaloracionCriterio->cliente}}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Contratistas: </b>{{$revaloracionCriterio->contratista}}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Directos: </b>{{$revaloracionCriterio->directos}}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Visitantes: </b>{{$revaloracionCriterio->visitante}}
            </div>
            <div class="columns small-12" style="font-size: 12px">
                <b>Requisito Legal Aplicable: </b>{{$revaloracionCriterio->reqLegal}}
            </div>
            <?php
                if($reqLegal === "Si"):
            ?>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Descripción Legal: </b>{{$descRequisitos[0]->requisitos}}
                </div>
            <?php
                endif;

            ?>
            <div class="columns small-12">
                <b>Consecuencias: </b><div style="font-size: 12px">{{ $revaloracionCriterio->consecuencia}}</div>
            </div>
        <?php    
            endforeach;
        endif;
        if($peligro->efectoPersona === "Corto y Largo Plazo"):
            $criteriosRevaloracionesCorto = $peligro->revaloracionCortoPlazo->where('tipo','revaloracion');
            $criteriosRevaloracionesLargo = $peligro->revaloracionLargoPlazo->where('tipo','revaloracion');
            
            foreach($criteriosRevaloracionesCorto as $criteriosRevaloracionCorto):
        ?>
            <div class="columns small-12 medium-6">
                <div class="columns small-12"><b>Accidente de Trabajo</b></div>
                <div class="columns small-12"><b>Trabajadores Expuestos</b></div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Clientes: </b>{{$criteriosRevaloracionCorto->cliente}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Contratistas: </b>{{$criteriosRevaloracionCorto->contratista}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Directos: </b>{{$criteriosRevaloracionCorto->directos}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Visitantes: </b>{{$criteriosRevaloracionCorto->visitantes}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Requisito Legal Aplicable: </b>{{$criteriosRevaloracionCorto->reqLegal}}
                </div>
                <?php
                    if($criteriosRevaloracionCorto->reqLegal === "Si"):
                        $descRequisitosCorto = RequisitosLegale::where('tipo_id',$criteriosRevaloracionCorto->id)
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
                    <b>Consecuencias: </b><div style="font-size: 12px">{{$criteriosRevaloracionCorto->peorConsecuencia}}</div>
                </div>

            </div>
            <?php
                endforeach;
                
                foreach($criteriosRevaloracionesLargo as $criteriosRevaloracionLargo):
            ?>
            <div class="columns small-12 medium-6">
                <div class="columns small-12"><b>Enfermedad Laboral</b></div>
                <div class="columns small-12"><b>Trabajadores Expuestos</b></div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Clientes: </b>{{$criteriosRevaloracionLargo->cliente}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Contratistas: </b>{{$criteriosRevaloracionLargo->contratista}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Directos: </b>{{$criteriosRevaloracionLargo->directos}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Visitantes: </b>{{$criteriosRevaloracionLargo->visitantes}}
                </div>
                <div class="columns small-12" style="font-size: 12px">
                    <b>Requisito Legal Aplicable: </b>{{$criteriosRevaloracionLargo->reqLegal}}
                </div>
                <?php
                    if($criteriosRevaloracionLargo->reqLegal === "Si"):
                        $descRequisitosLargo = RequisitosLegale::where('tipo_id',$criteriosRevaloracionLargo->id)
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
                    <b>Consecuencias: </b><div style="font-size: 12px">{{$criteriosRevaloracionLargo->peorConsecuencia}}</div>
                </div>

            </div>
        <?php
            endforeach;
        endif;
        ?>
        </div>
    <!--Dejar el siguiente DIV para que funcione la clase small-centered-->
    <div class="columns small-12 text-center"><br/></div>
</div>
