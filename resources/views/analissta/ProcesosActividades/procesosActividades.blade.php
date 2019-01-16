@extends('analissta.layouts.app2')
@section('sistem-menu')

@include('analissta.layouts.appTopMenu')

@endsection
@section('content')
<?php
    $sistema = session('sistema');
?>

<div class="row">
    
    @include('analissta.layouts.encabezadoEmpresaCliente')
    <div class="row columns text-center">
        <h5><b>Procesos y Actividades</b></h5>
    </div>
    <br/>
    <div id="div-listadoProcesos" class="row">
        <div class="row columns text-center">
        <a class="button small success-2" data-open="reveal-nuevoProceso">Crear Nuevo Proceso</a>
        
    </div>
    @include('analissta.Asesores.crearEmpresa.errors')
    
    @if(count($sistema->procesos)>0)
        @foreach($sistema->procesos as $proceso)
            <?php 
                $actividades = App\Actividade::where('proceso_id',$proceso->id)->get();
            ?>
            <div class="columns small-6 medium-4 end" style="border-right: 1px solid lightgray">

                <div class="text-center">
                    <div style="border-bottom:1px solid gray">
                        <b>
                            Proceso | {{ ucfirst($proceso->nombre) }}
                        </b>
                    </div>
                    <div class="text-center">
                        <a class="button tiny alert" href="{{ route('agregar-actividad',['idProceso'=>$proceso->id])}}">Agregar Actividad</a>
                    </div>
                    <div>Listado Actividades</div>
                </div>
                <br/>

                <section class="text-center">
                    @if(count($actividades)>0)
                        @foreach ($actividades as $actividad)
                            <div class="row">
                                <div class="columns small-6 ">{{ ucfirst($actividad->nombre) }}</div>
                                <div class="columns small-6 ">
                                    <a class="button tiny" href="{{ route('ver-actividad-proceso',['actividad'=>$actividad->id])}}">Valoración</a>
                                    <a class="button tiny warning" data-open="del-actividad-{{ $actividad->id }}">Eliminar</a>
                                </div>
                                <div class="small reveal" id="del-actividad-{{ $actividad->id }}" data-reveal>
                                    <div class="row columns text-center">
                                        <h5>¿Esta seguro de eliminar la actividad {{ ucwords(strtolower($actividad->nombre))}}?</h5>
                                        <p><i>Perdera todos los datos de valoraciones, medidas de intervención, planes de gestión y planes de vigilancia relacionadas con esta actividad</i></p>
                                    </div>
                                    <div>
                                        <a class="button small alert" data-close>Cancelar</a>
                                        <a class="button small" href="{{ route('eliminar-actividad-proceso',['id'=>$actividad->id])}}">Confirmar</a>
                                    </div>
                                    <button class="close-button" data-close aria-label="Close modal" type="button">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                    <div class="row">
                        <div class="columns small-12 small-centered text-center"  >
                            <div class="callout alert" style="font-size:12px">
                                <div class="row columns"><i class="fi-info" style="font-size: 24px; color:#ff6600"></i></div>
                                <div class="row columns text-center"><i>No se ha creado ninguna actividad</i></div>
                                <div class="text-left">
                                    <ol>
                                        <li>Debe crear las actividades que esten asociadas a este proceso</li>
                                        <li>Finalmente, realice la valoración de cada actividad creada</li>
                                    </ol>
                                </div>
                                <div>
                                    <i>Cree una actividad haciendo click en el botón "Agregar Actividad"</i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </section>
                <br/>

            </div>
        @endforeach
    @else
        <div class="row">
            <div class="columns small-12 medium-8 small-centered text-center"  >
                <div class="callout alert">
                    <div class="row columns"><i class="fi-info" style="font-size: 28px; color:#ff6600"></i></div>
                    <div><b><i>No se ha iniciado la valoración</i></b></div>
                    <div class="text-left">
                        <ol>
                            <li>Primero debe crear un proceso de su sistema</li>
                            <li>Luego se deben crear las actividades que se realicen en cada proceso</li>
                            <li>Finalmente, realice la valoración de cada actividad creada</li>
                        </ol>
                    </div>
                    <div>
                        <i>Para iniciar cree su primer proceso haciendo click en el botón "Crear Nuevo Proceso"</i>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div id="reveal-nuevoProceso" class="reveal" data-reveal>
        <div class="row columns text-center">
            <div class="text-center" style="border-bottom:1px solid gray"><b>Crear Nuevo Proceso</b></div>
            <form method="post" id="frm-nameNuevoProceso" name="frm-nameNuevoProceso" action="{{ route('crear-proceso') }}">
                {{ csrf_field() }}
                <br/>
                <div class="columns small-12 ">
                    <label for="nombNuevoProc"><b>Nombre Proceso: </b></label>
                </div>
                <div class="columns small-9 medium-8 small-centered end">
                    <input type="text" required="true" id="nombNuevoProc" name="nombre" placeholder="Nombre del Proceso"/>
                </div>
                <div class="columns small-12 text-center">
                    <input type="submit" value="Crear" class="button small success"/>
                    <a data-close class="button small alert hollow">Cancelar</a>
                </div>
            </form>
        </div>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
</div>

@endsection
