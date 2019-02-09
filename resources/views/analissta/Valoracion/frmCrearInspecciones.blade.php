<?php
    use App\InspeccionesValoracione;
    use App\InspeccionesDisponible;
    
    $inspeccionesValoracion = $peligro->inspeccionesValoracion->where('medida',$medidaAconfigurar);
    /*$inspeccionesValoracion = InspeccionesValoracione::where('sistema_id',$sistema->id)
            ->where('peligro_id',session('idPeligro'))
            ->where('medida',$medidaAconfigurar)
            ->get();*/
    
    $inspeccionesDisponibles = InspeccionesDisponible::where('sistema_id',$sistema->id)
            ->where('clasificacion_peligro_id',$peligro->clasificacion)
            ->where('medida',$medidaAconfigurar)
            ->get();
?>

<div class="row">
    <div class="columns small-11 small-centered" style="min-height:180px; height:auto">
        <div class="row columns text-center" style="font-size: 18px">
            <b>Inspecciones Creadas</b>
        </div>
        <br/>
        @if(count($inspeccionesValoracion)>0)

            
            @foreach($inspeccionesValoracion as $inspeccionValoracion)
                <div class="row">
                    <div class="columns small-8 listado-Activ-Creadas">{{ $inspeccionValoracion->nombre }}</div>
                    <div class="columns small-3 end">
                        <form name="frm-eliminar-inspeccion" method="post" action="{{ route('eliminar-medida-intervencion',['idActividad'=>$actividad->id])}}">
                            {{ csrf_field() }}  
                            <input type="hidden" name="tipo" value="Inspeccion"/>
                            <input type="hidden" name="id" value="{{$inspeccionValoracion->id}}"/>
                            <input type="submit" class="button tiny alert hollow" value="Eliminar"/>
                        </form>

                    </div>
                </div>
            @endforeach
        @else
            <div class="columns small-12 text-center" style="height:150px">
                <br/><br/>
                <p style="font-size:18px"><i>No existen inspecciones para esta intervención</i></p>
            </div>
        @endif
    </div>
</div>  
<div class="row columns text-center">
    <a class="button small hollow" data-open="div-reveal-listInspecciones">Ver Listado</a>
</div>

<!-- Modal para ver listado inspecciones-->
<div id="div-reveal-listInspecciones" class="reveal" data-reveal="">
    <div class="row columns text-center">
        <p><b>Inspecciones Disponibles</b></p>
        <hr/>
    </div>
    <div class="row">
        <div class="columns small-12 small-centered">
            @if(count($inspeccionesDisponibles)>0)
                @foreach($inspeccionesDisponibles as $inspeccionDisponible)
                    @php
                        $inspeccionProgramada = InspeccionesValoracione::where('peligro_id',session('idPeligro'))
                                ->where('medida',$inspeccionDisponible->medida)
                                ->where('nombre',$inspeccionDisponible->nombre)
                                ->get();

                    @endphp
                    <div class="row">
                        <div class="columns small-7 listado-Activ-Creadas text-center">{{ $inspeccionDisponible->nombre }}</div>
                        <div class="columns small-5 text-left">
                            @if(isset($inspeccionProgramada[0]->nombre))
                            <label style="color:#009900"><i class="fi-check"></i> Seleccionada</label>
                            @else
                                <form name="frm-eliminar-inspeccion" method="post" action="{{ route('crear-medida-intervencion',['idActividad'=>$actividad->id]) }}">
                                    {{ csrf_field() }}  
                                    <input type="hidden" name="flag" value="copiar-de-disponibles">
                                    <input type="hidden" name="medida" value="{{$medidaAconfigurar}}">
                                    <input type="hidden" name="tipo" value="Inspeccion"/>
                                    <input type="hidden" name="nombre" value="{{$inspeccionDisponible->nombre}}"/>
                                    <input type="submit" class="button tiny" value="Agregar esta Inspección"/>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center">
                    <i>No existen inspecciones relacionadas con el peligro <b>{{ $clasificacionPeligro }}</b> y la medida de intervención <b>{{ $textoMedida }}</b>, por favor cree una inspección</i>
                    <br/>
                </div>
            @endif
            
        </div>
    </div>
    <div class="columns row text-center">
        <hr/>
        <a class="button small alert hollow"  data-close="">Cerrar</a>
        <a class="button small success" data-open="div-reveal-newInspeccion">Crear Nueva Inspección</a>
    </div>
    <div class="row columns text-center">
        <div style="font-size: 11px"><i>Inspecciones relacionadas con el peligro <b>{{ $clasificacionPeligro }}</b> y la medida de intervención <b>{{ $textoMedida }}</b></i></div>
    </div>
    <button class="close-button" data-close="" aria-label="Close modal" type="button">
        <span aria-hidden="true">x</span>
    </button>
</div>

<!-- Modal para crear inspecciones-->
<div class="reveal" data-reveal="" id="div-reveal-newInspeccion">
    <div class="row">
        <div class="columns small-12 text-center">
            <b>Nueva Inspección</b>
        </div>
    </div>
    <form id="frm-nuevoPeligroMedidas" name="frm-nuevoPeligroMedidas" method="POST" action="{{ route('crear-medida-intervencion',['idActividad'=>$actividad->id]) }}">
        {{ csrf_field() }}  
        <input type="hidden" name="flag" value="crear-en-disponibles">
        <input type="hidden" name="medida" class="hide" hidden="true" readonly="true" value="{{$medidaAconfigurar}}">
        <input type="hidden" name="tipo" class="hide" hidden="true" readonly="true" value="Inspeccion">
        <div class="row">
            <div class="columns small-10 small-centered text-center">
                <label for="text-newInter" style="color:gray">Escribe el nombre de la Inspección</label>
                <input id="text-newInter" name="nombre" type="text" required="true"/>
            </div>
        </div>    
        <div class="row">
            <div class="columns small-12 text-center">
                <a class="button small alert hollow" data-close="">Cerrar</a>

                <input type="submit" class="button small success" value="Guardar"/>
            </div>
        </div>
    </form>

    <button class="close-button" data-close="" aria-label="Close modal" type="button">
        <span aria-hidden="true">x</span>
    </button>
</div>

