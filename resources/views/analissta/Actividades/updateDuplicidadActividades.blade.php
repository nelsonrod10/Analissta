<?php
    use App\ActividadesDisponible;
?>
@foreach ($sistema->ActividadesValoracion->groupBy('nombre') as $key=>$actividades)
<?php
    $arrActividades = [];
?>
<div class="row">
    <div class="columns small-12"><h2>{{$key}}</h2></div>       
    <ul>
        @foreach($actividades as $actividad)
        <?php
            array_push($arrActividades, $actividad->id);
            $actividadesDisponibles = ActividadesDisponible::where(['nombre'=>$actividad->nombre, 'medida'=>$actividad->medida])->get();
        ?>
        <li>
            <div>
                {{$actividad->id}}
                {{$actividad->nombre}} - 
                <span style="color:white; background: green">{{$actividad->estado}}</span>
            </div>
            
        </li>
        @endforeach
    </ul>
    <div class="columns small-12">
        <form method="POST" action="{{route('update-analissta')}}">
            {{ csrf_field() }}
            <input type="hidden" class="hidden" hidden="true" name="tipo" value="Actividades"/>
            <input type="hidden" class="hidden" hidden="true" name="nombre" value="{{$key}}"/>
            <input type="submit" class="button small success-2" value="Actualizar"/>
        </form>
        
    </div>
    
</div>    

@endforeach
