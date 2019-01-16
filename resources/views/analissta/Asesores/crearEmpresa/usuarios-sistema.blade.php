@extends('analissta.layouts.appProgramarMedida')

@section('content')
<br/>
<div class="row">
    <div class="columns small-12 text-center">
        <h4>Crear Empresa</h4>
    </div>
    <fieldset class="fieldset">
        <legend class="text-center" style="font-size:18px;"><b>Usuarios Autorizados</b></legend>
        <div class="columns small-12 text-center">
            <span style="color:#ff4d4d; font-weight: bold;"><?php //echo $msjError ?></span>
        </div>
        <div class="columns small-12 medium-10 small-centered text-center">
            <div class="warning callout">
                <div class="row columns"><i class="fi-info" style="font-size: 28px; color:#ff6600"></i></div>
                <div><i>En este paso usted va a ingresar los datos de los <b>usuarios autorizados</b> para tener acceso al software, recuerde que los perfiles son: </i></div>
                <div class="text-left" style="font-size: 13px">
                    <ol>
                        <li><b>Administrador: </b>Usuario que tendrá acceso a todas las funciones del software; podrá: ingresar, eliminar, crear y actualizar información</li>
                        <li><b>Digitador: </b>Usuario que únicamente podrá ingresar información al sistema</li>
                    </ol>
                </div>
                <div><i><b>Puede omitir este paso y crear los usuarios más adelante</b></i></div>
            </div>
        </div>
        <div style="font-size:14px">
            <div class="row columns text-center">
                <h5>Usuarios Creados</h5>
            </div>
            <div class="row text-center">
                <div class="columns small-2 medium-2"><b>Nombre</b></div>
                <div class="columns small-2 medium-2"><b>Identificación</b></div>
                <div class="columns small-2 medium-2"><b>Cargo</b></div>
                <div class="columns small-2 medium-1"><b>Rol</b></div>
                <div class="columns small-2 medium-2"><b>Email</b></div>
                <div class="columns small-2 medium-2"><b>Teléfono</b></div>
                <div class="columns small-2 medium-1"><b></b></div>
            </div>
            <div class="row text-center">
                <?php
               
                    /*if($filas >= 1):
                        while($datosCentroBD = mysqli_fetch_array($resultado)):*/
                ?>        
                    <div class="columns small-2 medium-2"><?php //echo $datosCentroBD["nombreUsuario"]?></div>
                    <div class="columns small-2 medium-2"><?php //echo $datosCentroBD["numIdUsuario"]?></div>
                    <div class="columns small-2 medium-2"><?php //echo $datosCentroBD["cargoUsuario"]?></div>
                    <div class="columns small-2 medium-1"><?php //echo $datosCentroBD["rolUsuario"]?></div>
                    <div class="columns small-2 medium-2"><?php //echo $datosCentroBD["mailUsuario"]?></div>
                    <div class="columns small-2 medium-2"><?php //echo $datosCentroBD["telUsuario"]?></div>
                    <div class="columns small-2 medium-1"><a class="button tiny alert" href="index.php?flagGET=eliminarUsuario&usuario=<?php //echo $datosCentroBD["idusuarioSistema"]?>">Eliminar</a></div>
                <?php
                        //endwhile;
                    //else:
                ?>
                    <br/>
                    <div class="row columns small text-center">
                        <h6>No existe ningún usuario</h6>
                    </div>
                <?php
                    //endif;

                    //$objConexion->Cerrar();
                ?>
            </div>
            <br/>
            <fieldset class="fieldset">
                <form method="POST" name="frm-crearNueva-Empresa" enctype="multipart/form-data" action="{{ route('inicio')}}">
                    <input type="text" value="7" name="pasoActual" readonly="true" hidden="true" class="hide"/>
                    <div class="row columns text-center">
                        <h5 style="text-decoration:underline">Crear Nuevo Usuario</h5>
                    </div>
                    <div class="columns small-12 medium-6">
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="nombUsu" class="text-right middle"><b>Nombre del Usuario:</b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <span id="span-infoFrmIngresoUsu" style="color:#ff4d4d; font-weight: bold; font-size: 12px"><?php //echo $nombEmpErr ?></span>
                                <input class="input-required" required="true" type="text" id="nombUsu" name="nombUsu" placeholder="Nombre del Usuario" value="<?php //echo $nombEmpresa ?>"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="idUsu" class="text-right middle"><b># Identificación:</b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <span id="span-infoFrmIngresoUsu" style="color:#ff4d4d; font-weight: bold; font-size: 12px"><?php //echo $nombEmpErr ?></span>
                                <input class="input-required" required="true" type="number" id="idUsu" name="idUsu" min="1" placeholder="# Número de Identificacion" value="<?php //echo $nombEmpresa ?>"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="cargoUsu" class="text-right middle"><b>Cargo:</b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <span id="span-infoFrmIngresoUsu" style="color:#ff4d4d; font-weight: bold; font-size: 12px"><?php //echo $nombEmpErr ?></span>
                                <input class="input-required" type="text" required="true" id="cargoUsu" name="cargoUsu" placeholder="Cargo Actual" value="<?php //echo $nombEmpresa ?>"/>
                            </div>
                        </div>
                    </div>


                    <div class="columns small-12 medium-6">
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="rolUsu" class="text-right middle"><b>Rol Usuario:</b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <span id="span-infoFrmIngresoUsu" style="color:#ff4d4d; font-weight: bold; font-size: 12px"><?php //echo $nombEmpErr ?></span>
                                <select class="input-required" required="true" id="rolUsu" name="rolUsu">
                                    <option value="">Rol Usuario...</option>
                                    <option value="Administrador">Administrador</option>
                                    <option value="Digitador">Digitador</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="mailUsu" class="text-right middle"><b>Email Usuario:</b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <span id="span-infoFrmIngresoUsu" style="color:#ff4d4d; font-weight: bold; font-size: 12px"><?php //echo $nombEmpErr ?></span>
                                <input class="input-required" required="true" type="email" id="mailUsu" name="mailUsu" placeholder="email@dominio.com" value="<?php //echo $nombEmpresa ?>"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-4">
                                <label for="telUsu" class="text-right middle"><b>Teléfono:</b></label>
                            </div>    
                            <div class="columns small-12 medium-8 end">
                                <span id="span-infoFrmIngresoUsu" style="color:#ff4d4d; font-weight: bold; font-size: 12px"><?php //echo $nombEmpErr ?></span>
                                <input class="input-required" required="true" type="number" id="telUsu" name="telUsu" min="1" placeholder="Teléfono Usuario" value="<?php //echo $nombEmpresa ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="columns small-12 text-center">
                        <input type="submit" class="button success small" value="Agregar Usuario"/>
                    </div>
                </form>
            </fieldset>
            <br/>
            <div class="columns small-12 text-center">
                <a class="button small" href="{{ route('resultados-anteriores')}}">Anterior</a>
                <a class="button small alert" data-open="modal-confirm-borrarEmpresa">Cancelar</a>
                <a type="submit" class="button success small" href="{{ route('inicio')}}">Finalizar</a>
            </div>
        </div>
    </fieldset>
</div>
<!--Modal que confirma la cancelación de crear una empresa, cuando se hace click en "Cancelar"-->
    <div id="modal-confirm-borrarEmpresa" class="reveal" data-reveal>
        <div class="row columns text-center">
            <div><i><b>¿Esta seguro de eliminar la creación de esta empresa?</b></i></div>
            <div><i>Se perderan todos los datos configurados</i></div>
            <br/>
            <div>
                <a class="button small" href="{{ route('inicio')}}">Confirmar</a>
                <a class="button small alert" aria-label="Close modal" data-close>Cancelar</a>
            </div>
        </div>
        <button class="close-button" data-close="" aria-label="Close modal" type="button">
            <span aria-hidden="true">x</span>
        </button> 
    </div>
@endsection

