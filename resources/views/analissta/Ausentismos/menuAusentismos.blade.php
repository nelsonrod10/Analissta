<?php
    use App\Ausentismos\Ausentismo;
    use App\Http\Controllers\helpers;
    use App\Empleado;
    
    $ausentismos = Ausentismo::where('sistema_id',$sistema->id)
            ->get();
    
    $arrMeses =[];
    foreach($ausentismos as $ausentismo){
        $objFechaAusentismo = new DateTime($ausentismo->fecha_inicio);
        $mes = $objFechaAusentismo->format("n");
        array_push($arrMeses, $mes);
    }
    $arrMesesOrganizado = array_unique($arrMeses);
    asort($arrMesesOrganizado);
?>

<ul class="no-bullet text-center" >
    <li class="titulo-origenes" style="background:lightgray;padding: 10px">
        Listado de Ausentismos
    </li>
</ul>

<ul class="accordion" data-accordion style="font-size:14px">
    @foreach($arrMesesOrganizado as $mesBuscado)
        <?php $textMesBuscado =  helpers::meses_de_numero_a_texto($mesBuscado -1); ?>    
        <li class="accordion-item " data-accordion-item>
          <a href="#" class="accordion-title titulo-origenes">{{$textMesBuscado}}</a>
            <div class="accordion-content" data-tab-content>
                <ol style="padding: 10px" class="text-left">
                <?php
                    foreach($ausentismos as $ausentismo):
                        $objFechaAusentismo = new DateTime($ausentismo->fecha_inicio);
                        $mesAusentismo = $objFechaAusentismo->format("n");
                        if($mesAusentismo === $mesBuscado):
                            $empleado = Empleado::where('empresaCliente_id',session('idEmpresaCliente'))
                                ->where('identificacion',$ausentismo->ausentado_id)
                                ->get();
                            $nombreEmpleado = ucwords(strtolower($empleado[0]->nombre." ".$empleado[0]->apellidos));    
                ?>
                    <li>
                        <a href="{{route('ausentismo',['id'=>$ausentismo->id])}}">
                            <b>{{substr_replace($ausentismo->clasificacion,".",15)}}, </b>
                            {{substr_replace($nombreEmpleado,"...",15)}}
                        </a>
                    </li>
                <?php
                        endif;
                    endforeach;
                ?>
                </ol>
            </div>         
        </li>
    @endforeach
</ul>

