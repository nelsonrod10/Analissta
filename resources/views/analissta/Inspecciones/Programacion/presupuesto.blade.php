@extends('analissta.layouts.appProgramarMedida')

<?php
use App\Presupuesto;
?>
@section('content')
    @section('titulo-encabezado')
        Programación Inspección
    @endsection
    @section('titulo-programacion')
        {{$inspeccion->nombre}}
    @endsection
    <div class="row text-center">
        <div class="columns small-12 medium-8 small-centered label secondary">
            <h6><b>PRESUPUESTO</b></h6>
        </div>
    </div>
    <br/>
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row">
        <div class="columns small-12 medium-8 small-centered">
            <div class="row show-for-medium text-center">
                <div class="columns medium-3" style="text-decoration:underline"><b>ITEM</b></div>
                <div class="columns medium-4" style="text-decoration:underline"><b>OBSERVACIONES</b></div>
                <div class="columns medium-3" style="text-decoration:underline"><b>PRESUPUESTO</b></div>
                <div class="columns medium-2" style="text-decoration:underline"><b></b></div>
            </div>
            <br/>
            <div id="div-addItem-presupuesto">
                <?php
                switch ($tipoInspeccion) {
                    case $tipoInspeccion === 'obligatoria' || $tipoInspeccion === 'sugerida':
                        $table = 'inspecciones_obligatorias_sugeridas';
                        break;  
                    case 'valoracion':
                        $table = 'inspecciones_valoraciones';
                        break;
                    default:
                        break;
                }
                $presupuesto = Presupuesto::where('sistema_id',$sistema->id)
                        ->where('tabla_origen',$table)
                        ->where('origen_id',$inspeccion->id)
                        ->get();
                $totalPresupuesto=0;
                foreach ($presupuesto as $item):
                    $totalPresupuesto += $item->valor;
                ?>
                    <div class="row">
                        <div class="columns small-12 medium-3">{{$item->item}}</div>
                        <div class="columns small-12 medium-4">{{$item->observaciones}}</div>
                        <div class="columns small-12 medium-3 text-center">$ {{$item->valor}} COP</div>
                        <div class="columns small-12 medium-2">
                            <form method="post" name="frm-presupuesto" action="{{route('eliminar-item-presupuesto')}}">
                                {{ csrf_field() }}
                                <input type="hidden" name="tipo" value="{{$tipoInspeccion}}"/>
                                <input type="hidden" name="idActividad" value="{{$inspeccion->id}}"/>
                                <input type="hidden" name="origen" value="programacion-presupuesto-inspeccion"/>
                                <input type="hidden" name="id" value="{{$item->id}}"/>
                                <input type="submit" class="button tiny alert" value="Eliminar"/>
                            </form>
                        </div>
                    </div>
                    <hr/>
                <?php
                endforeach;
                ?>
            </div>
            <div class="row">
                <div class="columns small-12 medium-8 text-right">
                    <h5><b>Total Presupuesto</b></h5>
                </div>
                <div class="columns small-12 medium-4 text-center">
                    <h5><b>$ {{$totalPresupuesto}}<span id="span-total-presupuesto">
                    <!--<xsl:value-of select="format-number(sum(presupuesto/item/valor),'#,###.###')"/>-->
                    </span> COP</b>
                    </h5>
                </div>
            </div>
            <div class="columns small-12 callout success hide" id="frm-presupuesto">
                <form method="post"  name="frmProgramarIntervencion-Paso-3" action="{{route('presupuesto-inspeccion')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="tipo" value="{{$tipoInspeccion}}"/>
                    <input type="hidden" name="idInspeccion" value="{{$inspeccion->id}}"/>
                    <input type="hidden" name="origen" value="programacion-presupuesto-inspeccion"/>
                    <div class="row columns text-center">
                        <b>AGREGAR ITEM</b>
                    </div>
                    <div class="row show-for-medium text-center">
                        <div class="columns medium-4" style="text-decoration:underline"><b>ITEM</b></div>
                        <div class="columns medium-6" style="text-decoration:underline"><b>OBSERVACIONES</b></div>
                        <div class="columns medium-2" style="text-decoration:underline"><b>PRESUPUESTO</b></div>
                    </div>
                    <div class="columns small-12 show-for-small-only" style="text-decoration:underline"><b>Item</b></div>
                    <div class="columns small-12 medium-4">
                        <input class="input-required-presupuesto-3" type="text" id="nombreRecurso" name="item" required="true" placeholder=""/>   
                    </div>
                    <div class="columns small-12 show-for-small-only" style="text-decoration:underline"><b>Observaciones</b></div>
                    <div class="columns small-12 medium-6">
                        <textArea class="input-required-presupuesto-3" id="obsRecurso" name="observaciones" required="true" placeholder=""></textarea>   
                    </div>
                    <div class="columns small-12 show-for-small-only" style="text-decoration:underline"><b>Presupuesto</b></div>
                    <div class="columns small-12 medium-2">
                        <input class="input-required-presupuesto-3" type="number" id="vrRecurso" name="valor" required="true" placeholder=""/>   
                    </div>
                    <div class="row text-center">
                        <div class="columns small-12">
                            <div class=""><i><b class="msj-error-programarIntervencion" style="color:red"></b></i></div>
                            <a class="button small alert" id="btn-cancelar-presupuesto">Cancelar</a>
                            <xsl:text> </xsl:text>
                            <input type="submit" class="button small" value="Agregar"/>
                        </div>
                    </div>
                </form>
            </div>
            <br/>
            <div id="botones-finalizar">
                <div class="row columns text-right">
                    <a class="button small success-2" id="btn-agregar-presupuesto"><i class="fi-plus"></i> Agregar Item</a>
                </div>
                <div class="row columns text-center">
                    <a class="button small" href="{{ route('ver-programacion-inspeccion',['id'=>$inspeccion->id,'tipo'=>$tipoInspeccion])}}">Anterior</a>
                    <a class="button small alert" data-open="reveal-cancelar-programacion-inspeccion">Cancelar</a>
                    <a class="button small success-2" href="{{route('finalizar-programar-inspeccion',['id'=>$inspeccion->id,'tipo'=>$tipoInspeccion])}}">Finalizar</a>
                </div>    
            </div>
            
        </div>
        
    </div>
    @include('analissta.Inspecciones.Programacion.modalCancelar')
    <script>
        $(document).ready(function(){
           $("#btn-agregar-presupuesto").on("click",function(){
              $("#botones-finalizar").addClass("hide");
              $("#frm-presupuesto").removeClass("hide");
           }); 
           
           $("#btn-cancelar-presupuesto").on("click",function(){
              $("#botones-finalizar").removeClass("hide");
              $("#frm-presupuesto").addClass("hide");
           }); 
        });
    </script>    
    
@endsection



