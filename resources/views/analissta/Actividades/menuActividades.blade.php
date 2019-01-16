<ul class="no-bullet text-center" >
    <li class="titulo-origenes" style="background:lightgray;padding: 10px">
        Actividades Creadas
    </li>
</ul>

<ul class="accordion" data-accordion style="font-size:14px">
    <li class="accordion-item " data-accordion-item>
      <a href="#" class="accordion-title titulo-origenes">Obligatorio Cumplimiento</a>
      <div class="accordion-content" data-tab-content>
        <ol>
            <?php
                foreach ($sistema->Actividades_Obligatorias_Sugeridas->where('medida','obligatoria') as $actObligatoria):
                        switch($actObligatoria->estado):
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
                    <a href="{{route('actividad-obligatoria',['id'=>$actObligatoria->id])}}">{{$actObligatoria->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$actObligatoria->estado}}</small></i>
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
                foreach ($sistema->Actividades_Obligatorias_Sugeridas->where('medida','sugerida') as $actSugerida):
                        switch($actSugerida->estado):
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
                    <a href="{{route('actividad-sugerida',['id'=>$actSugerida->id])}}">{{$actSugerida->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$actSugerida->estado}}</small></i>
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
                foreach ($sistema->ActividadesValoracion as $actValoracion):
                    switch($actValoracion->estado):
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
                    <a href="{{route('actividad-valoracion',['id'=>$actValoracion->id])}}">{{$actValoracion->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$actValoracion->estado}}</small></i>
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
      <a href="#" class="accordion-title titulo-origenes">Actividades por Hallazgos</a>
      <div class="accordion-content" data-tab-content>
        <ol>
            <?php
                //$sistema->ActividadesValoracion;
                foreach ($sistema->ActividadesHallazgos as $actHallazgo):
                    switch($actHallazgo->estado):
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
                    <a href="{{route('actividad-hallazgo',['id'=>$actHallazgo->id])}}">{{$actHallazgo->nombre}}</a>
                    <i style="background:<?php echo $color ?>; color:white"><small>{{$actHallazgo->estado}}</small></i>
                </li>
            <?php
                endforeach;
            ?>    
          </ol>
      </div>
    </li>
    
</ul>
