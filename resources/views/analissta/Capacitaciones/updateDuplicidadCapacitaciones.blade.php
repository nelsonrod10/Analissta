<?php
    use App\CapacitacionesDisponible;
?>
@foreach ($sistema->CapacitacionesValoracion->groupBy('nombre') as $key=>$capacitaciones)
<?php
    $arrCapacitaciones = [];
?>
<div class="row">
    <div class="columns small-12"><h2>{{$key}}</h2></div>       
    <ul>
        @foreach($capacitaciones as $capacitacion)
        <?php
            array_push($arrCapacitaciones, $capacitacion->id);
            $capacitacionesDisponibles = CapacitacionesDisponible::where(['nombre'=>$capacitacion->nombre, 'medida'=>$capacitacion->medida])->get();
        ?>
        <li>
            <div>
                {{$capacitacion->id}}
                {{$capacitacion->nombre}} - 
                <span style="color:white; background: green">{{$capacitacion->estado}}</span>
            </div>
            
        </li>
        @endforeach
    </ul>
    <div class="columns small-12">
        <form method="POST" action="{{route('update-analissta')}}">
            {{ csrf_field() }}
            <input type="hidden" class="hidden" hidden="true" name="tipo" value="Capacitaciones"/>
            <input type="hidden" class="hidden" hidden="true" name="nombre" value="{{$key}}"/>
            <input type="submit" class="button small success-2" value="Actualizar"/>
        </form>
        
    </div>
    
</div>    

@endforeach
