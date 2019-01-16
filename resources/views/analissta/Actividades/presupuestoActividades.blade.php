<?php
    use App\Presupuesto;
    $presupuesto = Presupuesto::where('sistema_id',$sistema->id)
            ->where('tabla_origen',$tabla_origen)
            ->where('origen_id',$actividad->id)
            ->get();
    $totalPresupuesto=$totalEjecutado = 0;
?>
<div class="row columns text-center">
    <h5  style="text-decoration: underline"><b>Presupuesto</b></h5>
</div>
<div class="row columns text-center" id="items-presupuesto" >
    <div class="row text-center show-for-medium">
        <div class="columns small-12 medium-3"><b style="text-decoration:underline">Item</b></div>
        <div class="columns small-12 medium-3"><b style="text-decoration:underline">Observaciones</b></div>
        <div class="columns small-12 medium-2"><b style="text-decoration:underline">Presupuesto</b></div>
        <div class="columns small-12 medium-2"><b style="text-decoration:underline">Ejecutado</b></div>
        <div class="columns small-12 medium-2"></div>
    </div>
    <hr/>
    @foreach($presupuesto as $item)
        @php
            $totalPresupuesto += $item->valor;
            $totalEjecutado += $item->valor_real;
        @endphp
        <div class="row text-center">
            <div class="columns small-12 medium-3">{{$item->item}}</div>
            <div class="columns small-12 medium-3">{{$item->observaciones}}</div>
            <div class="columns small-12 medium-2">$ {{$item->valor}} COP</div>
            <div class="columns small-12 medium-2">$ <?php echo ($item->valor_real == null)?0:$item->valor_real?> COP</div>
            <div class="columns small-12 medium-2">
                <form method="post" name="frm-presupuesto" action="{{route('eliminar-item-presupuesto')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="tipo" value="{{$tipo}}"/>
                    <input type="hidden" name="idActividad" value="{{$actividad->id}}"/>
                    <input type="hidden" name="origen" value="{{$origen}}"/>
                    <input type="hidden" name="id" value="{{$item->id}}"/>
                    <input type="submit" class="button tiny alert" value="Eliminar"/>
                </form>
            </div>
        </div>
    @endforeach
</div>

<div class="columns small-12 callout success hide" id="frm-presupuesto" >
    <form method="post" name="frm-presupuesto" action="{{route('presupuesto-actividad')}}">
        {{ csrf_field() }}
        <input type="hidden" name="tipo" value="{{$tipo}}"/>
        <input type="hidden" name="idActividad" value="{{$actividad->id}}"/>
        <input type="hidden" name="origen" value="{{$origen}}"/>
        <div class="row columns text-center">
            <b>AGREGAR ITEM</b>
        </div>
        <div class="row">
            <div class="columns small-12 medium-6">
                    <label><b>Nombre Item</b></label>
                    <input class="input-required-presupuesto-3" type="text" id="nombreRecurso" name="item" required="true" placeholder="Nombre que describe el item del presupuesto"/>
            </div>
            <div class="columns small-12 medium-6">
                    <label><b>Presupuesto</b></label>
                    <input class="input-required-presupuesto-3" type="number" id="vrRecurso" name="valor" required="true" placeholder="Presupuesto destinado para este item"/>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12">
                <label><b>Observaciones</b></label>
                <textarea class="input-required-presupuesto-3" style="min-height: 100px;height: auto"id="obsRecurso" name="observaciones" required="true" placeholder="Detalles sobre el item del presupuesto"></textarea>
            </div>
        </div>
        
        <div class="row text-center">
            <div class="columns small-12">
                <div class=""><i><b class="msj-error-programarIntervencion" style="color:red"></b></i></div>
                <a class="button small alert" id="btn-cancelar-presupuesto">Cancelar</a>
                <input type="submit" class="button small" value="Agregar"/>
            </div>
        </div>

    </form>
</div>
<hr/>
<div class="row">
    <div class="columns small-12 medium-8 text-right">
        <h5><b>Total Presupuesto</b></h5>
    </div>
    <div class="columns small-12 medium-4 text-center">
        <h5>
            <b>$ <span id="span-total-presupuesto">{{$totalPresupuesto}}</span> COP</b>
        </h5>
    </div>
</div>
<div class="row">
    <div class="columns small-12 medium-8 text-right">
        <h5><b>Total Ejecutado</b></h5>
    </div>
    <div class="columns small-12 medium-4 text-center">
        <h5>
            <b>$ <span id="span-total-presupuesto">{{$totalEjecutado}}</span> COP</b>
        </h5>
    </div>
</div>
<br/>
<div class="row columns text-right">
    <a class="button small " href="{{route('general.index')}}"><i class="fi-eye"></i> Ver Presupuesto</a>
    <a class="button small success-2" id="btn-agregar-presupuesto"><i class="fi-plus"></i> Agregar Item</a>
</div>

