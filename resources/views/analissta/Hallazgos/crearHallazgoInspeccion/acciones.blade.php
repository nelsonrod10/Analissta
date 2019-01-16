@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\Hallazgos\Hallazgo;
    use App\Http\Controllers\helpers;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    
    if(isset($idHallazgo)){
        $hallazgoBD = Hallazgo::find($idHallazgo);
    }
?>
@section('content')
    @section('titulo-encabezado')
        Crear Nuevo Hallazgo
    @endsection
    <div class="row columns text-center">
        <h5 style="color:white; background:#66cc00">Hallazgo para Inspección - {{$inspeccion->nombre}}</h5>
    </div>
    <div class="row text-center">
        <div class="columns small-12 small-centered label secondary">
            <h6><b>ACCIONES</b></h6>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="columns small-12 medium-8 small-centered">
            <form method="post" name="accionesHallazgo" action="{{route('crear-acciones-hallazgo-inspeccion',['idInspeccion'=>$inspeccion->id,'tipoInspeccion'=>$inspeccion->medida,'idHallazgo'=>$idHallazgo])}}">
                {{ csrf_field() }}
                <div class="columns small-12  small-centered end">
                    <div class="columns small-12 medium-10 small-centered ">
                        <div class="row">
                            <div class="columns small-12 medium-4"><b>Acto / Condición: </b></div>
                            <div class="columns small-12 medium-8 end">
                                <div class="row columns">
                                    <input type="radio" id="acto1" name="actoCondicion" required="true" value="Acto y Condicion Insegura" <?php echo (isset($hallazgoBD))?($hallazgoBD->actoCondicion == "Acto y Condicion Insegura")?"checked":"":""?>/>
                                    <label for="acto1">Acto y Condición Insegura</label>
                                </div>
                                <div class="row columns">
                                    <input type="radio" id="acto2" name="actoCondicion" required="true" value="Acto Inseguro" <?php echo (isset($hallazgoBD))?($hallazgoBD->actoCondicion == "Acto Inseguro")?"checked":"":""?>/>
                                    <label for="acto2">Acto Inseguro</label>
                                </div>
                                <div class="row columns">
                                    <input type="radio" id="acto3" name="actoCondicion" required="true" value="Condicion Insegura" <?php echo (isset($hallazgoBD))?($hallazgoBD->actoCondicion == "Condicion Insegura")?"checked":"":""?> />
                                    <label for="acto3">Condición Insegura</label>
                                </div>
                                <div class="row columns">
                                    <input type="radio"  id="acto4" name="actoCondicion" required="true" value="Acto Seguro" <?php echo (isset($hallazgoBD))?($hallazgoBD->actoCondicion == "Acto Seguro")?"checked":"":""?>/>
                                    <label for="acto4">Acto Seguro</label>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="columns small-12 medium-4"><b>Tipo Acción: </b></div>
                            <div class="columns small-12 medium-8 end">
                                <div class="row columns">
                                    <input type="radio" id="tipo1" checked="true" name="tipoAccion" required="true" value="Correctiva" <?php echo (isset($hallazgoBD))?($hallazgoBD->tipoAccion == "Correctiva")?"checked":"":""?>/>
                                    <label for="tipo1">Corretiva</label>
                                    <input type="radio" id="tipo2" name="tipoAccion" required="true" value="Preventiva" <?php echo (isset($hallazgoBD))?($hallazgoBD->tipoAccion == "Preventiva")?"checked":"":""?>/>
                                    <label for="tipo2">Preventiva</label>
                                </div>
                                <div class="row columns">
                                    <input type="radio" id="tipo3" name="tipoAccion" required="true" value="Oportunidad de Mejora"  <?php echo (isset($hallazgoBD))?($hallazgoBD->tipoAccion == "Oportunidad de Mejora")?"checked":"":""?>/>
                                    <label for="tipo3">Oportunidad de Mejora</label>
                                </div>

                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="columns small-12 medium-4"><b>Plan de Acción: </b></div>
                            <div class="columns small-12 medium-8 end">
                                <div class="row columns">
                                    <input type="radio" id="plan1" checked="true" name="tipoPlanAccion" required="true" value="Actividades" <?php echo (isset($hallazgoBD))?($hallazgoBD->planAccion == "Actividades")?"checked":"":""?>/>
                                    <label for="plan1">Unicamente Actividades</label>
                                </div>
                                <div class="row columns">    
                                    <input type="radio" id="plan2" name="tipoPlanAccion" required="true" value="Capacitaciones" <?php echo (isset($hallazgoBD))?($hallazgoBD->planAccion == "Capacitaciones")?"checked":"":""?>/>
                                    <label for="plan2">Unicamente Capacitaciones</label>
                                </div>
                                <div class="row columns">
                                    <input type="radio" id="plan3" name="tipoPlanAccion" required="true" value="Actividades y Capacitaciones" <?php echo (isset($hallazgoBD))?($hallazgoBD->planAccion == "Actividades y Capacitaciones")?"checked":"":""?> />
                                    <label for="plan3">Actividades y Capacitaciones</label>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <br/>
                            <div class="columns small-12 medium-4"><b class="middle">Fecha Cierre Propuesta: </b></div>
                            <div class="columns small-12 medium-4 end">
                                <input type="date" class="input-required-paso-7" min="<?php echo (isset($hallazgoBD->fechaCierre))?$hallazgoBD->fechaCierre:""?>" name="fechaCierre" required="true" placeholder="Fecha de Cierre" value="<?php echo (isset($hallazgoBD->fechaCierre))?$hallazgoBD->fechaCierre:""?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row text-center">
                    <div class="columns small-12" data-tabs="">
                        <a class="button small" href="{{ route('causas-basicas-hallazgo-inspeccion',['idInspeccion'=>$inspeccion->id,'tipoInspeccion'=>$inspeccion->medida,'idHallazgo'=>$idHallazgo]) }}">Anterior</a>
                        <a class="button small alert" data-open="reveal-cancelar-crear">Cancelar</a>
                        <input type="submit" value="Siguiente" class="button small success"/>
                    </div>
                </div>
            </form>
        </div>    
    </div>
    
    @include('analissta.Hallazgos.crearHallazgoInspeccion.modalCancelar')
@endsection

