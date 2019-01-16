@extends('analissta.layouts.appProgramarMedida')

@section('content')
<?php
    use App\EmpresaCliente;
    
    $nombre = $empresa!==null ? $empresa->nombre:'';
    $ARL = $empresa!==null ? $empresa->ARL:'';
    $fechaARL = $empresa!==null ? $empresa->fechaAfiliacionARL:'';
    $CCF = $empresa!==null ? $empresa->cajaCompensacion:'';
    $fechaCCF = $empresa!==null ? $empresa->fechaAfiliacionCajaComp:'';
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = new DateTime("NOW");;
    (string)$fechaActual = $objFechaActual->format("Y-m-d");
?>
<br/>
<div class="row">
    <div class="columns small-12 text-center">
        <h4>Crear Empresa - {{ strtoupper($nombre) }}</h4>
    </div>
    <fieldset class="fieldset">
        <legend class="text-center" style="font-size:18px;"><b>Afiliaciones</b></legend>
        @include('analissta.Asesores.crearEmpresa.errors')
        <div style="font-size:14px">
            <br/>
            <form method="POST" name="frm-crearNueva-Empresa" enctype="multipart/form-data" action="{{route('crear-afiliaciones')}}">
                {{ csrf_field() }}
                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-5">
                            <label for="arl" class="text-right middle"><b>Nombre ARL:</b></label>
                        </div>    
                        <div class="columns small-12 medium-7 end">
                            <input  list="lista-arl" required="true" type="text" id="arl" name="arl" placeholder="ARL de la Empresa" value="<?php echo  old('arl')!==null? old('arl'):$ARL ?>"/>
                            <datalist id="lista-arl">
                                <option value="Positiva">Positiva</option>
                                <option value="Bolivar">Bolivar</option>
                                <option value="Aurora">Aurora</option>
                                <option value="Liberty">Liberty</option>
                                <option value="Mapfre">Mapfre</option>
                                <option value="Colmena">Colmena</option>
                                <option value="Alfa">Alfa</option>
                                <option value="Colpatria">Colpatria</option>
                                <option value="Equidad">Equidad</option>
                                <option value="Sura">Sura</option>
                            </datalist>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-5">
                            <label for="fechaARL" class="text-right middle"><b>Fecha de Afiliación a ARL:</b></label>
                        </div>    
                        <div class="columns small-12 medium-7 end">
                            <input  type="date" required="true" id="fechaARL" name="fechaARL" min="{{ $fechaARL }}" max="{{ $fechaActual }}"placeholder="Fecha de Afiliación a la ARL" value="<?php echo  old('fechaARL')!==null? old('fechaARL'):$fechaARL ?>"/>
                        </div>
                    </div>
                </div>


                <div class="columns small-12 medium-6">
                    <div class="row">
                        <div class="columns small-12 medium-5">
                            <label for="ccf" class="text-right middle"><b>Caja de Compensación:</b></label>
                        </div>    
                        <div class="columns small-12 medium-7 end">
                            <input list="lista-ccf" type="text" id="ccf" name="ccf" placeholder="CCF de la Empresa" value="<?php echo  old('ccf')!==null? old('ccf'):$CCF ?>"/>
                            <datalist id="lista-ccf">
                                <option value="Cafaba">Cafaba</option>
                                <option value="Cafam">Cafam</option>
                                <option value="Cajacopi">Cajacopi</option>
                                <option value="Cajamag">Cajamag</option>
                                <option value="Cajasai">Cajasai</option>
                                <option value="Cajasan">Cajasan</option>
                                <option value="Cofrem">Cofrem</option>
                                <option value="Colsubsidio">Colsubsidio</option>
                                <option value="Comcaja">Comcaja</option>
                                <option value="Comfaboy">Comfaboy</option>
                                <option value="Comfaca">Comfaca</option>
                                <option value="Comfacasanare">Comcasanare</option>
                                <option value="Comfacauca">Comfacauca</option>
                                <option value="Comfacesar">Comfacesar</option>
                                <option value="Comfacor">Comfacor</option>
                                <option value="Comfama">Comfama</option>
                                <option value="Comfamar">Comfamar</option>
                                <option value="Comfamiliar Chocho">Comfamiliar Choco</option>
                                <option value="Comfamiliar la Guajira">Comfamiliar la Guajira</option>
                                <option value="Comfamiliar Narino">Comfamiliar Nariño</option>
                                <option value="Comfamiliar Risaralda">Comfamiliar Risaralda</option>
                                <option value="Comfamiliar Cartagena">Comfamiliar Cartagena</option>
                                <option value="Comfamiliar Huila">Comfamiliar Huila</option>
                                <option value="Comfandi">Comfandi</option>
                                <option value="Comfaoriente">Comfaoriente</option>
                                <option value="Comfaputumayo">Comfaputumayo</option>
                                <option value="Comfasucre">Comfasucre</option>
                                <option value="Comfatolima">Comfatolima</option>
                                <option value="Comfaunion">Comfaunion</option>
                                <option value="Comfenalco Antioquida">Comfenalco Antioquida</option>
                                <option value="Comfenalco Quindio">Comfenalco Quindio</option>
                                <option value="Comfenalco Santander">Comfenalco Santander</option>
                                <option value="Comfenalco">Comfenalco</option>
                                <option value="Comfenalco Valle">Comfenalco Valle</option>
                                <option value="Comfiar">Comfiar</option>
                                <option value="Compensar">Compensar</option>
                                <option value="Comfamiliares">Comfamiliares</option>
                            </datalist>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12 medium-5">
                            <label for="fechaCCF" class="text-right middle"><b>Fecha de Afiliación a CCF:</b></label>
                        </div>    
                        <div class="columns small-12 medium-7 end">
                            <input  type="date" id="fechaCCF" name="fechaCCF" min="{{ $fechaCCF }}" max="{{ $fechaActual }}"placeholder="Fecha de Afiliación a la CCF" value="<?php echo  old('fechaCCF')!==null? old('fechaCCF'):$fechaCCF ?>"/>
                        </div>
                    </div>
                </div>
                <div class="columns small-12 text-center">
                    <a class="button small" href="{{ route('tipo-valoracion')}}">Anterior</a>
                    <a class="button small alert" data-open="modal-confirm-borrarEmpresa">Cancelar</a>
                    <input type="submit" class="button success small" value="Siguiente"/>
                </div>
            </form>
            <br/>
            
        </div>
    </fieldset>
</div>    

@include('analissta.Asesores.crearEmpresa.modalCancelar')
@endsection
