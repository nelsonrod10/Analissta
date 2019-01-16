<?php
    use App\ActividadesCalendario;
    use App\CapacitacionesCalendario;
    use App\InspeccionesCalendario;
?>
<div class="row hide div-presupuesto" id="div-presupuesto-{{$tipo}}-{{$medida->id}}">
    <div class='columns small-12 medium-8 small-centered'>
        <div class=" callout primary">
            <div class="row columns text-center">
                <h5 style="text-decoration:underline">Presupuesto Reportado desde las ejecuciones de la {{ucfirst($tipo)}}</h5>
            </div>
            @if($medida->itemsEjecuciones->count() > 0)
                <div class="row text-center">
                    <div class="columns small-12 medium-3"><b>Fecha Ejecución</b></div>
                    <div class="columns small-12 medium-4"><b>Observaciones</b></div>
                    <div class="columns small-12 medium-3 end"><b>Valor Reportado</b></div>
                </div>
            
                @foreach($medida->itemsEjecuciones as $itemEjecucion)
                    <?php
                        $modelo = "App\\".$itemEjecucion->tabla_calendario;
                        $calendario = $modelo::find($itemEjecucion->calendario_id);
                    ?>
                    <div class="row">
                        <div class="columns small-12 medium-3 text-center">{{$calendario->mes}}, Semana {{$calendario->semana}}</div>
                        <div class="columns small-12 medium-4 text-justify">{{$itemEjecucion->observaciones}}</div>
                        <div class="columns small-12 medium-3 text-center end">$ {{$itemEjecucion->valor}}</div>
                    </div>
                @endforeach
            @else
                <div class="row columns text-center">
                    <p><i class="fi-alert" style="font-size: 32px;"></i></p>
                    <p><b>No se ha reportado ningún valor desde las ejecuciones de la {{$tipo}}</b></p>
                </div>
            @endif
            <br/>
            <div class="row" style="border-top: 1px solid lightgray">
                <div class="columns small-12 medium-9 text-right">
                    <b>Subtotal: </b>
                    <b>$ {{$medida->itemsEjecuciones->sum('valor')}}</b>
                </div>
            </div>
            <div class="row columns text-center">
                <a class="button small alert btn-cerrar-detalles-presupuesto" data-medida="{{$tipo}}" data-id="{{$medida->id}}">Cerrar</a>
            </div>
        </div>

    </div>
</div>

