<?php
    use App\Hallazgos\Hallazgo;
?>

<ul class="no-bullet text-center" >
    <li class="titulo-origenes" style="background:lightgray;padding: 10px">
        Listado de Hallazgos
    </li>
</ul>

<ul class="accordion" data-accordion style="font-size:14px">
    @foreach($origenesBD as $origen)
        @php
            $dataOrigen = $xml_origenes->xpath("//origenes/origen[@id=$origen->origen_id]");
            $hallazgos = Hallazgo::where('sistema_id',$sistema->id)
            ->where('origen_id',$origen->origen_id)
            ->get();
        @endphp
        <li class="accordion-item " data-accordion-item>
          <a href="#" class="accordion-title titulo-origenes">{{$dataOrigen[0]->attributes()['short-name']}}</a>
          <div class="accordion-content" data-tab-content>
            <ol>
                <?php
                    
                    foreach ($hallazgos as $hallazgo):
                        $objFechaCierre = new DateTime($hallazgo->fechaCierre);
                        $diff = $objFechaActual->diff($objFechaCierre);
                        
                        if($hallazgo->cerrado === "No"):
                            if((int)$diff->format('%R%a') < 0):
                                $estado = "Vencido";
                                $color = "#cc0000";
                            elseif ((int)$diff->format('%R%a') >= 0):
                                $estado = "Abierto";
                                $color = "#f29c13";
                            endif;
                        else:    
                            $estado = "Cerrado";
                            $color = "#339900";
                        endif;
                        
                ?>            
                    <li>
                        <a href="{{route('hallazgo',['id'=>$hallazgo->id])}}">{{substr_replace($hallazgo->descripcion,"...",30)}}</a>
                        <i style="background:<?php echo $color ?>; color:white"><small>{{$estado}}</small></i>
                    </li>
                <?php

                    endforeach;
                ?>    
            </ol>
          </div>
        </li>
    @endforeach
</ul>
