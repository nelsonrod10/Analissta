@extends('analissta.layouts.appProgramarMedida')

@section('content')
<?php
    use App\EmpresaCliente;
    use App\ResultadosAnualesSistema;
    date_default_timezone_set('America/Bogota');
    $objFechaActual = new DateTime("NOW");;
    (string)$fechaActual = $objFechaActual->format("Y-m-d");
    (string)$anioAnterior = $objFechaActual->sub(new DateInterval("P1Y"))->format("Y");
    
    
    $nombre = $empresa!==null ? $empresa->nombre:'';
    $resultadosEmp = ResultadosAnualesSistema::where('empresaCliente_id',session('idEmpresaCliente'))
            ->where('anio',$anioAnterior)
            ->first();
    $tasaMort = $resultadosEmp!== null ? $resultadosEmp->tasaMortalidad:0;
    $ili = $resultadosEmp!== null ? $resultadosEmp->indLesionesIncapacitantes:0;
    $if = $resultadosEmp!== null ? $resultadosEmp->indFrecuencia:0;
    $is = $resultadosEmp!== null ? $resultadosEmp->indSeveridad:0;
    $tAcc = $resultadosEmp!== null ? $resultadosEmp->tasaAccidentalidad:0;
    $tenfL = $resultadosEmp!== null ? $resultadosEmp->tasaEnfLaboral:0;
    $ifEL = $resultadosEmp!== null ? $resultadosEmp->indFrecEnfLaboral:0;
    
?>
<br/>
<div class="row">
    <div class="columns small-12 text-center">
        <h4>Crear Empresa - {{ strtoupper($nombre) }}</h4>
    </div>
    <fieldset class="fieldset">
        <legend class="text-center" style="font-size:18px;"><b>Resultados Anteriores</b></legend>
        <div class="columns small-12 text-center">
            <span style="font-weight: bold;">RESULTADOS DEL AÑO {{ $anioAnterior }}</span>
            <div><i>Si no tiene resultados del año anterior ingrese en todas las opciones el número 0</i></div>
        </div>
        <br/>
        @include('analissta.Asesores.crearEmpresa.errors')
        <div style="font-size:14px">
            <br/>
            <form method="POST" name="frm-crearNueva-Empresa" enctype="multipart/form-data" action="{{ route('crear-resultados-anteriores')}}">
                {{ csrf_field() }}
                <input type="hidden" name="anio" value="{{$anioAnterior}}"/>
                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-5">
                            <label for="tasam" class="text-right middle"><b>Tasa Mortalidad:</b></label>
                        </div>    
                        <div class="columns small-12 medium-7 end">
                            <input   min="0" step="0.005" type="number" id="tasam" name="tasaMort" placeholder="# Tasa Mortalidad" value="<?php echo  old('tasaMort')!==null? old('tasaMort'):$tasaMort ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-5">
                            <label for="ili" class="text-right middle"><b>ILI:</b></label>

                        </div>    
                        <div class="columns small-12 medium-7 end">
                            <span><i style="font-size: 12px">(Indice de Lesiones Incapacitantes)</i></span>
                            <input  type="number" required="true" min="0" step="0.00005" id="ili" name="ili" placeholder="# ILI" value="<?php echo  old('ili')!==null? old('ili'):$ili ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-5">
                            <label for="if" class="text-right middle"><b>IF:</b></label>
                        </div>    
                        <div class="columns small-12 medium-7 end">
                            <span><i style="font-size: 11px">(Indice de Frecuencia)</i></span>
                            <input  type="number" required="true" min="0" step="0.00005" id="if" name="if" placeholder="# IF" value="<?php echo  old('if')!==null? old('if'):$if ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-5">
                            <label for="is" class="text-right middle"><b>IS:</b></label>
                        </div>    
                        <div class="columns small-12 medium-7 end">
                            <span><i style="font-size: 11px">(Indice de Severidad)</i></span>
                            <input  type="number" required="true" min="0" step="0.00005"  id="is" name="isev" placeholder="# IS" value="<?php echo  old('isev')!==null? old('isev'):$is ?>"/>
                        </div>
                    </div>
                </div>


                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-5">
                            <label for="tasaA" class="text-right middle"><b>Tasa Accidentalidad:</b></label>
                        </div>    
                        <div class="columns small-12 medium-7 end">
                            <input  type="number" required="true" min="0" step="0.005"  id="tasaA" name="tasaAcc"  placeholder="# Tasa Accidentalidad" value="<?php echo  old('tasaAcc')!==null? old('tasaAcc'):$tAcc ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-5">
                            <label for="tasaEnfL" class="text-right middle"><b>Tasa Enfermedad Laboral:</b></label>
                        </div>    
                        <div class="columns small-12 medium-7 end">
                            <input  type="number" required="true" min="0" step="0.005"  id="tasaEnfL" name="tasaEnfL" placeholder="# Tasa EnfermedadLaboral" value="<?php echo  old('tasaEnfL')!==null? old('tasaEnfL'):$tenfL ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-5">
                            <label for="ifEnfL" class="text-right middle"><b>IF Enfermedad Laboral:</b></label>
                        </div>    
                        <div class="columns small-12 medium-7 end">
                            <span><i style="font-size: 11px">(Indice de Frecuencia Enfermedidad Laboral)</i></span>
                            <input  type="number" required="true" min="0" step="0.00005"  id="ifEnfL" name="ifEnfL" placeholder="# IF EnfermedadLaboral" value="<?php echo  old('ifEnfL')!==null? old('ifEnfL'):$ifEL ?>"/>
                        </div>
                    </div>
                </div>
                <div class="columns small-12 text-center">
                    <a class="button small" href="{{route('afiliaciones')}}">Anterior</a>
                    <a class="button small alert" data-open="modal-confirm-borrarEmpresa">Cancelar</a>
                    <input type="submit" class="button success small" value="Finalizar"/><!--Siguiente-->
                </div>
            </form>
            <br/>
            
        </div>
    </fieldset>
</div>

@include('analissta.Asesores.crearEmpresa.modalCancelar')
@endsection


