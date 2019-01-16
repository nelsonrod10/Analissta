<ul class="no-bullet text-center" >
    <li class="titulo-origenes" style="background:lightgray;padding: 10px">
        Capacitaciones Creadas
    </li>
</ul>

<ul class="accordion" data-accordion style="font-size:14px">
    <li class="accordion-item " data-accordion-item>
      <a href="#" class="accordion-title titulo-origenes">Obligatorio Cumplimiento</a>
      <div class="accordion-content" data-tab-content>
        <ol>
            <?php
                foreach ($sistema->Capacitaciones_Obligatorias_Sugeridas->where('medida','obligatoria') as $capObligatoria):
                    
                        switch($capObligatoria->estado):
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
                    <a href="{{route('capacitacion-obligatoria',['id'=>$capObligatoria->id])}}">{{$capObligatoria->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$capObligatoria->estado}}</small></i>
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
                foreach ($sistema->Capacitaciones_Obligatorias_Sugeridas->where('medida','sugerida') as $capSugerida):
                    
                        switch($capSugerida->estado):
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
                    <a href="{{route('capacitacion-sugerida',['id'=>$capSugerida->id])}}">{{$capSugerida->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$capSugerida->estado}}</small></i>
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
                foreach ($sistema->CapacitacionesValoracion as $capValoracion):
                    switch($capValoracion->estado):
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
                    <a href="{{route('capacitacion-valoracion',['id'=>$capValoracion->id])}}">{{$capValoracion->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$capValoracion->estado}}</small></i>
                </li>
            <?php
                endforeach;
            ?>    
          </ol>
      </div>
    </li>
    <!--Si $tipoMedida es actividad o capacitacion se revisa si hay actividades o capacitaciones generadas por hallazgos-->
    <?php
        /*if($tipoMedida === "Actividad" || $tipoMedida === "Capacitacion"):
            $medidasPorHallazgos = $archivoXMLintervencion->xpath("//listado".$tipoMedida."es/medida[@cumplimiento='Obligatorio-Hallazgo']/".strtolower($tipoMedida)."es/item");
            if(count($medidasPorHallazgos)>0):*/
    ?>
    <li class="accordion-item " data-accordion-item>
      <a href="#" class="accordion-title titulo-origenes">Capacitaciones por Hallazgos</a>
      <div class="accordion-content" data-tab-content>
        <ol>
            <?php
                //$sistema->ActividadesValoracion;
                foreach ($sistema->CapacitacionesHallazgos as $capHallazgo):
                    switch($capHallazgo->estado):
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
                    <a href="{{route('capacitacion-hallazgo',['id'=>$capHallazgo->id])}}">{{$capHallazgo->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$capHallazgo->estado}}</small></i>
                </li>
            <?php
                endforeach;
            ?>    
          </ol>
      </div>
    </li>
    
</ul>
