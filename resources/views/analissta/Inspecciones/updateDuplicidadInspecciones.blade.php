<?php
    use App\InspeccionesDisponible;
?>
@foreach ($sistema->InspeccionesValoracion->groupBy('nombre') as $key=>$inspecciones)
<?php
    $arrInspecciones = [];
?>
<div class="row">
    <div class="columns small-12"><h2>{{$key}}</h2></div>       
    <ul>
        @foreach($inspecciones as $inspeccion)
        <?php
            array_push($arrInspecciones, $inspeccion->id);
            $inspeccionesDisponibles = InspeccionesDisponible::where(['nombre'=>$inspeccion->nombre, 'medida'=>$inspeccion->medida])->get();
        ?>
        <li>
            <div>
                {{$inspeccion->id}}
                {{$inspeccion->nombre}} - 
                <span style="color:white; background: green">{{$inspeccion->estado}}</span>
            </div>
            
        </li>
        @endforeach
    </ul>
    <div class="columns small-12">
        <form method="POST" action="{{route('update-analissta')}}">
            {{ csrf_field() }}
            <input type="hidden" class="hidden" hidden="true" name="tipo" value="Inspecciones"/>
            <input type="hidden" class="hidden" hidden="true" name="nombre" value="{{$key}}"/>
            <input type="submit" class="button small success-2" value="Actualizar"/>
        </form>
        
    </div>
    
</div>    

@endforeach
