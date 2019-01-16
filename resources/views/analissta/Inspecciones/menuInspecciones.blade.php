<ul class="no-bullet text-center" >
    <li class="titulo-origenes" style="background:lightgray;padding: 10px">
        Inspecciones Creadas
    </li>
</ul>

<ul class="accordion" data-accordion style="font-size:14px">
    <li class="accordion-item " data-accordion-item>
      <a href="#" class="accordion-title titulo-origenes">Obligatorio Cumplimiento</a>
      <div class="accordion-content" data-tab-content>
        <ol>
            <?php
                foreach ($sistema->Inspecciones_Obligatorias_Sugeridas->where('medida','obligatoria') as $inspecObligatoria):
                    
                        switch($inspecObligatoria->estado):
                            case('Programada'):
                                $color = "#cc0000";
                                break;
                            case('En ejecucion'):
                                $color = "#f29c13";
                                break;
                            case('Ejecutado'):
                                $color = "#3adb76";
                                break;
                            default:
                                $color= "white";
                        endswitch;
            ?>            
                <li>
                    <a href="{{route('inspeccion-obligatoria',['id'=>$inspecObligatoria->id])}}">{{$inspecObligatoria->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$inspecObligatoria->estado}}</small></i>
                </li>
            <?php
                    
                endforeach;
            ?>    
        </ol>
      </div>
    </li>
    <li class="accordion-item " data-accordion-item>
      <a href="#" class="accordion-title titulo-origenes">Sugerencias de Analissta</a>
      <div class="accordion-content" data-tab-content>
        <ol>
            <?php
                foreach ($sistema->Inspecciones_Obligatorias_Sugeridas->where('medida','sugerida') as $inspecSugerida):
                    
                        switch($inspecSugerida->estado):
                            case('Programada'):
                                $color = "#cc0000";
                                break;
                            case('En ejecucion'):
                                $color = "#f29c13";
                                break;
                            case('Ejecutado'):
                                $color = "#3adb76";
                                break;
                            default:
                                $color= "white";
                        endswitch;
            ?>            
                <li>
                    <a href="{{route('inspeccion-sugerida',['id'=>$inspecSugerida->id])}}">{{$inspecSugerida->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$inspecSugerida->estado}}</small></i>
                </li>
            <?php
                    
                endforeach;
            ?>    
        </ol>
      </div>
    </li>
    <li class="accordion-item " data-accordion-item>
      <a href="#" class="accordion-title titulo-origenes">Valoración IPER</a>
      <div class="accordion-content" data-tab-content>
          <ol>
            <?php
                //$sistema->ActividadesValoracion;
                foreach ($sistema->InspeccionesValoracion as $inspecValoracion):
                    switch($inspecValoracion->estado):
                        case('Programada'):
                            $color = "#cc0000";
                            break;
                        case('En ejecucion'):
                            $color = "#f29c13";
                            break;
                        case('Ejecutado'):
                            $color = "#3adb76";
                            break;
                        default:
                            $color= "white";
                    endswitch;
            ?>            
                <li>
                    <a href="{{route('inspeccion-valoracion',['id'=>$inspecValoracion->id])}}">{{$inspecValoracion->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$inspecValoracion->estado}}</small></i>
                </li>
            <?php
                endforeach;
            ?>    
          </ol>
      </div>
    </li>
</ul>
