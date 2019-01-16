<?php
    use App\Http\Controllers\helpers;
?>

<ul class="no-bullet text-center" >
    <li class="titulo-origenes" style="background:lightgray;padding: 10px">
        Planes de Gestión
    </li>
</ul>

<ul class="accordion" data-accordion style="font-size:14px">
    <li class="accordion-item " data-accordion-item>
        <a href="#" class="accordion-title titulo-origenes">Físico</a>
        <div class="accordion-content" data-tab-content>
            <ol>
                <?php
                foreach($sistema->pgrpFisico as $fisico):
                    $categFisico = $xmlPeligros->xpath("//peligros/clasificacion[id=1]/listDescripciones/descripcion[id=$fisico->categoria]");
                ?>
                    <li>
                        <a href="{{route('pgrp',['tipo'=>'fisico','id'=>$fisico->id])}}">{{ucwords(strtolower($categFisico[0]->nombre))}}</a>
                        @if($fisico->estado == 'Programado')
                        <i style="background:#3adb76; color:white"><small>{{$fisico->estado}}</small></i>
                        @endif
                    </li>
                <?php    
                endforeach;
                ?>        
            </ol>
        </div>
    </li>    
    
    <li class="accordion-item " data-accordion-item>
        <a href="#" class="accordion-title titulo-origenes">Seguridad</a>
        <div class="accordion-content" data-tab-content>
            <ol>
                <?php
                foreach($sistema->pgrpSeguridad as $seguridad):
                    $categSeguridad = $xmlPeligros->xpath("//peligros/clasificacion[id=6]/listDescripciones/descripcion[id=$seguridad->categoria]");
                ?>
                    <li>
                        <a href="{{route('pgrp',['tipo'=>'seguridad','id'=>$seguridad->id])}}">{{ucwords(strtolower($categSeguridad[0]->nombre))}}</a>
                        @if($seguridad->estado == 'Programado')
                        <i style="background:#3adb76; color:white"><small>{{$seguridad->estado}}</small></i>
                        @endif
                    </li>
                <?php    
                endforeach;
                ?>        
            </ol>
        </div>
    </li> 
    <li class="accordion-item " data-accordion-item>
        <a href="#" class="accordion-title titulo-origenes">Generales</a>
        <div class="accordion-content" data-tab-content>
            <ol>
                <?php
                foreach($sistema->pgrpGeneral as $general):
                    $clasifGeneral = $xmlPeligros->xpath("//peligros/clasificacion[id=$general->clasificacion]");
                ?>
                    <li>
                        <a href="{{route('pgrp',['tipo'=>'general','id'=>$general->id])}}">{{ucwords(strtolower($clasifGeneral[0]->nombre))}}</a>
                        @if($general->estado == 'Programado')
                        <i style="background:#3adb76; color:white"><small>{{$general->estado}}</small></i>
                        @endif
                    </li>
                <?php    
                endforeach;
                ?>        
            </ol>
        </div>
    </li> 
    
</ul>

