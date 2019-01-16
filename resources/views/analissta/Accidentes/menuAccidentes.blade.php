<?php
    use App\Accidentes\Accidente;
    use App\Http\Controllers\helpers;
    
    $accidentes = Accidente::where('sistema_id',$sistema->id)
            ->get();
    
    $arrMeses =[];
    foreach($accidentes as $accidente){
        $objFechaAccidente = new DateTime($accidente->fechaAccidente);
        $mes = $objFechaAccidente->format("n");
        array_push($arrMeses, $mes);
    }
    $arrMesesOrganizado = array_unique($arrMeses);
    asort($arrMesesOrganizado);
?>

<ul class="no-bullet text-center" >
    <li class="titulo-origenes" style="background:lightgray;padding: 10px">
        Listado de Accidentes
    </li>
</ul>

<ul class="accordion" data-accordion style="font-size:14px">
    @foreach($arrMesesOrganizado as $mesBuscado)
        <?php
            $textMesBuscado =  helpers::meses_de_numero_a_texto($mesBuscado -1);
            
        ?>    
        <li class="accordion-item " data-accordion-item>
          <a href="#" class="accordion-title titulo-origenes">{{$textMesBuscado}}</a>
          
            <div class="accordion-content" data-tab-content>
                <ul class="no-bullet">
                    <li class="text-center"><label style="background: #8284ff;color: white"><b>Accidentes</b></label>
                        <ol style="padding: 10px" class="text-left">
                        <?php
                            foreach($accidentes->where('clasificacion','Accidente') as $accidente):
                                $objFechaAccidente = new DateTime($accidente->fechaAccidente);
                                $mesAccidente = $objFechaAccidente->format("n");
                                if($mesAccidente === $mesBuscado):
                        ?>
                            <li>
                                <a href="{{route('accidente',['id'=>$accidente->id])}}">{{substr_replace($accidente->descripcion,"...",30)}}</a>
                            </li>
                        <?php
                                endif;
                            endforeach;
                        ?>
                        </ol>
                    </li>
                    <li class="text-center"><label style="background: #8284ff;color: white"><b>Casi Accidentes</b></label>
                        <ol style="padding: 10px" class="text-left">
                        <?php
                            foreach($accidentes->where('clasificacion','Casi Accidente') as $accidente):
                                $objFechaAccidente = new DateTime($accidente->fechaAccidente);
                                $mesAccidente = $objFechaAccidente->format("n");
                                if($mesAccidente === $mesBuscado):
                        ?>
                            <li>
                                <a href="{{route('accidente',['id'=>$accidente->id])}}">{{substr_replace($accidente->descripcion,"...",30)}}</a>
                            </li>
                        <?php
                                endif;
                            endforeach;
                        ?>
                        </ol>
                    </li>
                    <li class="text-center"><label style="background: #8284ff;color: white"><b>Enfermedad Laboral</b></label>
                        <ol style="padding: 10px" class="text-left">
                        <?php
                            foreach($accidentes->where('clasificacion','Enfermedad Laboral') as $accidente):
                                $objFechaAccidente = new DateTime($accidente->fechaAccidente);
                                $mesAccidente = $objFechaAccidente->format("n");
                                if($mesAccidente === $mesBuscado):
                        ?>
                            <li>
                                <a href="{{route('accidente',['id'=>$accidente->id])}}">{{substr_replace($accidente->descripcion,"...",30)}}</a>
                            </li>
                        <?php
                                endif;
                            endforeach;
                        ?>    
                        </ol>
                    </li>
                </ul>
            </div> 
            
          
        </li>
    @endforeach
</ul>

