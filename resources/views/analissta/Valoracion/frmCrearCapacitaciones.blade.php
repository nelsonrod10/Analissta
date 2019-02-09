<?php
    use App\CapacitacionesValoracione;
    use App\CapacitacionesDisponible;
    
    $capacitacionesValoracion = $peligro->capacitacionesValoracion->where('medida',$medidaAconfigurar);
    /*$capacitacionesValoracion = CapacitacionesValoracione::where('sistema_id',$sistema->id)
            ->where('peligro_id',session('idPeligro'))
            ->where('medida',$medidaAconfigurar)
            ->get();*/
    $capacitacionesDisponibles = CapacitacionesDisponible::where('sistema_id',$sistema->id)
            ->where('clasificacion_peligro_id',$peligro->clasificacion)
            ->where('medida',$medidaAconfigurar)
            ->get();
?>

<div class="row">
    <div class="columns small-11 small-centered" style="min-height:180px; height:auto">
        <div class="row columns text-center" style="font-size: 18px">
            <b>Capacitaciones Creadas</b>
        </div>
        <br/>
        @if(count($capacitacionesValoracion)>0)

            
            @foreach($capacitacionesValoracion as $capacitacionValoracion)
                <div class="row">
                    <div class="columns small-8 listado-Activ-Creadas">{{ $capacitacionValoracion->nombre }}</div>
                    <div class="columns small-3 end">
                        <form name="frm-eliminar-capacitacion" method="post" action="{{ route('eliminar-medida-intervencion',['idActividad'=>$actividad->id])}}">
                            {{ csrf_field() }}  
                            <input type="hidden" name="tipo" value="Capacitacion"/>
                            <input type="hidden" name="id" value="{{$capacitacionValoracion->id}}"/>
                            <input type="submit" class="button tiny alert hollow" value="Eliminar"/>
                        </form>

                    </div>
                </div>
            @endforeach
        @else
            <div class="columns small-12 text-center" style="height:150px">
                <br/><br/>
                <p style="font-size:18px"><i>No existen capacitaciones para esta intervención</i></p>
            </div>
        @endif
    </div>
</div>  
<div class="row columns text-center">
    <a class="button small hollow" data-open="div-reveal-listCapacitaciones">Ver Listado</a>
</div>

<!-- Modal para ver listado capacitaciones-->
<div id="div-reveal-listCapacitaciones" class="reveal" data-reveal="">
    <div class="row columns text-center">
        <p><b>Capacitaciones Disponibles</b></p>
        <hr/>
    </div>
    <div class="row">
        <div class="columns small-12 small-centered">
            @if(count($capacitacionesDisponibles)>0)
                @foreach($capacitacionesDisponibles as $capacitacionDisponible)
                    @php
                        $capacitacionProgramada = CapacitacionesValoracione::where('peligro_id',session('idPeligro'))
                                ->where('medida',$capacitacionDisponible->medida)
                                ->where('nombre',$capacitacionDisponible->nombre)
                                ->get();

                    @endphp
                    <div class="row">
                        <div class="columns small-7 listado-Activ-Creadas text-center">{{ $capacitacionDisponible->nombre }}</div>
                        <div class="columns small-5 text-left">
                            @if(isset($capacitacionProgramada[0]->nombre))
                            <label style="color:#009900"><i class="fi-check"></i> Seleccionada</label>
                            @else
                                <form name="frm-eliminar-capacitacion" method="post" action="{{ route('crear-medida-intervencion',['idActividad'=>$actividad->id]) }}">
                                    {{ csrf_field() }}  
                                    <input type="hidden" name="flag" value="copiar-de-disponibles">
                                    <input type="hidden" name="medida" value="{{$medidaAconfigurar}}">
                                    <input type="hidden" name="tipo" value="Capacitacion"/>
                                    <input type="hidden" name="nombre" value="{{$capacitacionDisponible->nombre}}"/>
                                    <input type="submit" class="button tiny" value="Agregar esta Capacitación"/>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center">
                    <i>No existen capacitaciones relacionadas con el peligro <b>{{ $clasificacionPeligro }}</b> y la medida de intervención <b>{{ $textoMedida }}</b>, por favor cree una capacitación</i>
                    <br/>
                </div>
            @endif
            
        </div>
    </div>
    <br/>
    
    <div class="columns row text-center">
        <hr/>
        <a class="button small alert hollow"  data-close="">Cerrar</a>
        <a class="button small success" data-open="div-reveal-newCapacitacion">Crear Nueva Capacitación</a>
    </div>
    <div class="row columns text-center">
        <div style="font-size: 11px"><i>Capacitaciones relacionadas con el peligro <b>{{ $clasificacionPeligro }}</b> y la medida de intervención <b>{{ $textoMedida }}</b></i></div>
    </div>
    <button class="close-button" data-close="" aria-label="Close modal" type="button">
        <span aria-hidden="true">x</span>
    </button>
</div>

<!-- Modal para crear capacitaciones-->
<div class="reveal" data-reveal="" id="div-reveal-newCapacitacion">
    <div class="row">
        <div class="columns small-12 text-center">
            <b>Nueva Capacitación</b>
        </div>
    </div>
    <form id="frm-nuevoPeligroMedidas" name="frm-nuevoPeligroMedidas" method="POST" action="{{ route('crear-medida-intervencion',['idActividad'=>$actividad->id]) }}">
        {{ csrf_field() }}  
        <input type="hidden" name="flag" value="crear-en-disponibles">
        <input type="hidden" name="medida" class="hide" hidden="true" readonly="true" value="{{$medidaAconfigurar}}">
        <input type="hidden" name="tipo" class="hide" hidden="true" readonly="true" value="Capacitacion">
        <div class="row">
            <div class="columns small-10 small-centered text-center">
                <label for="text-newInter" style="color:gray">Escribe el nombre de la Capacitación</label>
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

