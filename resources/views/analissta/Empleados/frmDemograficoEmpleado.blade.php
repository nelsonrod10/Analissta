@extends('analissta.layouts.appProgramarMedida')

@section('content')
<?php
    use App\Http\Controllers\helpers;
    
    $rangoSalario = helpers::calcularRangoSalario($empleado->salarioMes);
?>
<br/>
<div class="row">
    <fieldset class="fieldset " >
        <form method="POST" name="frm-Demografico-Empleado" enctype="multipart/form-data" action="{{ route('perfil-socio-demografico',['id'=>$empleado->id]) }}">
            {{ csrf_field() }}
            <div class="row columns text-center">
                <h5>Perfil Sociodemográfico - {{ ucwords(strtolower($empleado->nombre)) }} {{ ucwords(strtolower($empleado->apellidos)) }}</h5>
            </div>
            @include('analissta.Asesores.crearEmpresa.errors')
            <div class="row">
                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="estado" class="text-right middle"><b>Estado Civíl:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select name="estadoCivil" required>
                                <option value="">Seleccione...</option>
                                <option value="Soltero" <?php echo $empleado->estadoCivil == "Soltero"?"selected":"" ?>>Soltero</option>
                                <option value="Casado" <?php echo $empleado->estadoCivil=="Casado"?"selected":"" ?>>Casado</option>
                                <option value="Union Libre" <?php echo $empleado->estadoCivil=="Union Libre"?"selected":"" ?>>Unión Libre</option>
                                <option value="Separado" <?php echo $empleado->estadoCivil=="Separado"?"selected":"" ?>>Separado</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="personasAcargo" class="text-right middle"><b>Personas a cargo:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select name="personasAcargo" required>
                                <option value="">Seleccione...</option>
                                <option value="Ninguna" <?php echo $empleado->personasAcargo=="Ninguna"?"selected":"" ?>>Ninguna</option>
                                <option value="1-3 Personas" <?php echo $empleado->personasAcargo=="1-3 Personas"?"selected":"" ?>>1-3 Personas</option>
                                <option value="4-6 Personas" <?php echo $empleado->personasAcargo=="4-6 Personas"?"selected":"" ?>>4-6 Personas</option>
                                <option value="Mas de 7 Personas" <?php echo $empleado->personasAcargo=="Mas de 7 Personas"?"selected":"" ?>>Mas de 7 Personas</option>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="escolaridad" class="text-right middle"><b>Nivel de escolaridad:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select name="escolaridad" required>
                                <option value="">Seleccione...</option>
                                <option value="Primaria" <?php echo $empleado->escolaridad=="Primaria"?"selected":"" ?>>Primaria</option>
                                <option value="Secundaria" <?php echo $empleado->escolaridad=="Secundaria"?"selected":"" ?>>Secundaria</option>
                                <option value="Tecnico/Tecnologo" <?php echo $empleado->escolaridad=="Tecnico/Tecnologo"?"selected":"" ?>>Tecnico/Tecnologo</option>
                                <option value="Universitario" <?php echo $empleado->escolaridad=="Universitario"?"selected":"" ?>>Universitario</option>
                                <option value="Especialista/Maestro" <?php echo $empleado->escolaridad=="Especialista/Maestro"?"selected":"" ?>>Especialista/Maestro</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="tipoVivienda" class="text-right middle"><b>Tenencia de Vivienda:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select name="tipoVivienda" required>
                                <option value="">Seleccione...</option>
                                <option value="Propia" <?php echo $empleado->tipoVivienda=="Propia"?"selected":"" ?>>Propia</option>
                                <option value="Arrendada" <?php echo $empleado->tipoVivienda=="Arrendada"?"selected":"" ?>>Arrendada</option>
                                <option value="Familiar" <?php echo $empleado->tipoVivienda=="Familiar"?"selected":"" ?>>Familiar</option>
                                <option value="Compartida con otros" <?php echo $empleado->tipoVivienda=="Compartida con otros"?"selected":"" ?>>Compartida con otros</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="tiempo" class="text-right middle"><b>Uso Tiempo Libre:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select name="tiempoLibre" required>
                                <option value="">Seleccione...</option>
                                <option value="Otro Trabajo" <?php echo $empleado->tiempoLibre=="Otro Trabajo"?"selected":"" ?>>Otro Trabajo</option>
                                <option value="Labores Domesticas" <?php echo $empleado->tiempoLibre=="Labores Domesticas"?"selected":"" ?>>Labores Domésticas</option>
                                <option value="Recreacion y deporte" <?php echo $empleado->tiempoLibre=="Recreacion y Deporte"?"selected":"" ?>>Recreación y deporte</option>
                                <option value="Estudio" <?php echo $empleado->tiempoLibre=="Estudio"?"selected":"" ?>>Estudio</option>
                                <option value="Ninguno" <?php echo $empleado->tiempoLibre=="Ninguno"?"selected":"" ?>>Ninguno</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="ingresos" class="text-right middle"><b>Promedio de Ingresos:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <input type="text" readonly="true" value="{{ $rangoSalario }}"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="antigEmpresa" class="text-right middle"><b>Antiguedad en la empresa:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select name="antiguedadEmpresa" disabled="">
                                <option value="">Seleccione...</option>
                                <option value="Menos de 1" <?php echo $empleado->antiguedadEmpresa=="Menos de 1"?"selected":"" ?>>Menos de 1 año</option>
                                <option value="De 1 a 5" <?php echo $empleado->antiguedadEmpresa=="De 1 a 5"?"selected":"" ?>>De 1 a 5 años</option>
                                <option value="De 5 a 10" <?php echo $empleado->antiguedadEmpresa=="De 5 a 10"?"selected":"" ?>>De 5 a 10 años</option>
                                <option value="De 10 a 15" <?php echo $empleado->antiguedadEmpresa=="De 10 a 15"?"selected":"" ?>>De 10 a 15 años</option>
                                <option value="Mas de 15" <?php echo $empleado->antiguedadEmpresa=="Mas de 15"?"selected":"" ?>>Mas de 15 años</option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="antigCargo" class="text-right middle"><b>Antiguedad Cargo Actual:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select name="antiguedadCargo" required>
                                <option value="">Seleccione...</option>
                                <option value="Menos de 1" <?php echo $empleado->antiguedadCargo=="Menos de 1"?"selected":"" ?>>Menos de 1 año</option>
                                <?php if($empleado->antiguedadEmpresa !== "Menos de 1"): ?>
                                <option value="De 1 a 5" <?php echo $empleado->antiguedadCargo=="De 1 a 5"?"selected":"" ?>>De 1 a 5 años</option>
                                <option value="De 5 a 10" <?php echo $empleado->antiguedadCargo=="De 5 a 10"?"selected":"" ?>>De 5 a 10 años</option>
                                <option value="De 10 a 15" <?php echo $empleado->antiguedadCargo=="De 10 a 15"?"selected":"" ?>>De 10 a 15 años</option>
                                <option value="Mas de 15" <?php echo $empleado->antiguedadCargo=="Mas de 15"?"selected":"" ?>>Mas de 15 años</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="contrato" class="text-right middle"><b>Tipo de Contratación:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select name="tipoContrato" required>
                                <option value="">Seleccione...</option>
                                <option value="Directo Definido" <?php echo $empleado->tipoContrato=="Directo Definido"?"selected":"" ?>>Directo Definido</option>
                                <option value="Directo Indefinido" <?php echo $empleado->tipoContrato=="Directo Indefinido"?"selected":"" ?>>Directo Indefinido</option>
                                <option value="En Mision" <?php echo $empleado->tipoContrato=="En Mision"?"selected":"" ?>>En Misión</option>
                                <option value="Prestacion de servicios" <?php echo $empleado->tipoContrato=="Prestacion de servicios"?"selected":"" ?>>Prestación de Servicios</option>
                                <option value="Servicios Profesionales" <?php echo $empleado->tipoContrato=="Servicios Profesionales"?"selected":"" ?>>Servicios Profesionales</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="enfermedades" class="text-right middle"><b>Alguna enfermedad:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select name="diagnosticoEnfermedad" required>
                                <option value="">Seleccione...</option>
                                <option value="Si" <?php echo $empleado->diagnosticoEnfermedad=="Si"?"selected":"" ?>>Si</option>
                                <option value="No" <?php echo $empleado->diagnosticoEnfermedad=="No"?"selected":"" ?>>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="fumador" class="text-right middle"><b>Fumador:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select name="fumador" required>
                                <option value="">Seleccione...</option>
                                <option value="Si" <?php echo $empleado->fumador=="Si"?"selected":"" ?>>Si</option>
                                <option value="No" <?php echo $empleado->fumador=="No"?"selected":"" ?>>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="alcohol" class="text-right middle"><b>Consumo Alcohol:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select name="consumoAlcohol" required>
                                <option value="">Seleccione...</option>
                                <option value="No Consume" <?php echo $empleado->consumoAlcohol=="No Consume"?"selected":"" ?>>No Consume</option>
                                <option value="Semanal" <?php echo $empleado->consumoAlcohol=="Semanal"?"selected":"" ?>>Semanal</option>
                                <option value="Quincenal" <?php echo $empleado->consumoAlcohol=="Quincenal"?"selected":"" ?>>Quincenal</option>
                                <option value="Mensual" <?php echo $empleado->consumoAlcohol=="Mensual"?"selected":"" ?>>Mensual</option>
                                <option value="Ocasional" <?php echo $empleado->consumoAlcohol=="Ocasional"?"selected":"" ?>>Ocasional</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="deporte" class="text-right middle"><b>Practica Deportiva:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select name="deportista" required>
                                <option value="">Seleccione...</option>
                                <option value="No practica" <?php echo $empleado->deportista=="No Practica"?"selected":"" ?>>No practica</option>
                                <option value="Diario" <?php echo $empleado->deportista=="Diario"?"selected":"" ?>>Diario</option>
                                <option value="Semanal" <?php echo $empleado->deportista=="Semanal"?"selected":"" ?>>Semanal</option>
                                <option value="Quincenal" <?php echo $empleado->deportista=="Quincenal"?"selected":"" ?>>Quincenal</option>
                                <option value="Mensual" <?php echo $empleado->deportista=="Mensual"?"selected":"" ?>>Mensual</option>
                                <option value="Ocasional" <?php echo $empleado->deportista=="Ocasional"?"selected":"" ?>>Ocasional</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-4">
                            <label for="consentimiento" class="text-right middle"><b>Firma de Consentimiento:</b></label>
                        </div>    
                        <div class="columns small-12 medium-8 end">
                            <select name="firmaConsentimiento" required>
                                <option value="">Seleccione...</option>
                                <option value="Si" <?php echo $empleado->firmaConsentimiento=="Si"?"selected":"" ?>>Si</option>
                                <option value="No" <?php echo $empleado->firmaConsentimiento=="No"?"selected":"" ?>>No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="columns small-12 text-center">
                    <a  class="button alert small" href="{{ route('mostrar-empleados')}}">Cancelar</a>
                    <input type="submit" class="button success small" value="Guardar"/>
                </div>
            </div>    
        </form>
    </fieldset>
</div>


@endsection
