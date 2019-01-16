@extends('analissta.layouts.appProgramarMedida')
<?php
    $idAusentismo = $accidente->ausentismo->ausentismo_id;
?>
@section('content')
    @section('titulo-encabezado')
        Reportar Ausentismo Accidente
    @endsection
    <div class="row text-center">
        <div class="columns small-12 medium-8 small-centered">
            <h5>Se esta configurando la Ausencia para el Accidente sufrido por: </h5>
            <h5>{{ucwords(strtolower($accidente->accidentado->nombre))}} {{ucwords(strtolower($accidente->accidentado->apellidos))}}</h5>
        </div>
    </div> 
    <div class="row text-center">
        <div class="columns small-12 medium-10 small-centered label secondary">
            <h6><b>DIAGNOSTICO AUSENTISMO</b></h6>
        </div>
    </div>
    <br/>
    <form id="frm-diagnostico-ausentismo-accidente" name="frm-diagnostico-ausentismo-accidente" method="POST" action="{{ route('diagnostico-ausentismo-accidente',["idAccidente"=>$accidente->id])}}">
        {{ csrf_field() }}
        <input type="hidden" class="hide" hidden="true" name="idAusencia" value="{{$idAusentismo}}"/>
        <br/>
        @include('analissta.Asesores.crearEmpresa.errors')
        <div class="row">
            <div class="columns small-12 medium-10 small-centered">
                <div class="row">
                    <div class="columns small-12 text-center">
                        <i style="color:red">Digite el código <b>valido</b> del diagnóstico y automáticamente se mostrarán los demás datos</i>
                    </div>
                    <div class="columns small-12 medium-3"><b class="middle">Código Diagnóstico: </b></div>
                    <div class="columns small-12 medium-6 end">
                        <input type="text" id="codigoDiag" list="datalist-codigos" name="diagnostico" placeholder="Digite Codigo ejm (M729)" required="true"/>
                        <datalist id="datalist-codigos"></datalist>
                    </div>
                </div>
                <div style="z-index:-5">
                <div class="row">
                    <div class="columns small-12 medium-3"><b class="middle">Sistema: </b></div>
                    <div class="columns small-12 medium-9 end">
                        <input id="sistema"  class="input-diagnostico" type="text" name="sistema" required="true" readonly="true" placeholder="Debe digitar un codigo para que aparezca el sistema correspondiente"/>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-3"><b class="middle">Sub Sistema: </b></div>
                    <div class="columns small-12 medium-9 end">
                        <input id="subsistema" class="input-diagnostico" type="text" name="subSistema" required="true" readonly="true" placeholder="Debe digitar un codigo para que aparezca el Subsistema correspondiente"/>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-3"><b class="middle">Descripción: </b></div>
                    <div class="columns small-12 medium-9 end">
                        <textarea id="descripcion"  class="input-diagnostico" name="descripcion" required="true" readonly="true" placeholder="Debe digitar un codigo para que aparezca la descripción correspondiente" style="min-height:80px; height:auto">
                        </textarea>
                    </div>
                </div>
                </div>
                <div class="row">
                    <div class="columns small-12 medium-3"><b class="middle">Nombre EPS: </b></div>
                    <div class="columns small-12 medium-6 end">
                        <input type="text" name="eps" placeholder="Nombre EPS" required="true"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row text-center">
            <div class="columns small-12" data-tabs="">
                <a class="button small" href="{{ route('datos-generales-ausentismo-accidente',["idAccidente"=>$accidente->id]) }}">Anterior</a>
                <a class="button small alert" data-open="reveal-cancelar-crear-ausentismo-accidente">Cancelar</a>
                <input type="submit" value="Finalizar" class="button small success"/>
            </div>
        </div>
    </form> 
    
    <form method="POST" action="{{route('buscar-diagnostico',['diagnostico'=>':diagnostico'])}}" accept-charset="UTF-8" id="form-buscar-diagnostico">
        {{ csrf_field() }}
    </form>

    <form method="POST" action="{{route('cargar-datos-diagnostico',['diagnostico'=>':diagnostico'])}}" accept-charset="UTF-8" id="form-cargar-datos-diagnostico">
        {{ csrf_field() }}
    </form>
    
    <script>
        $(document).ready(function(){
            $("#codigoDiag").on("keyup",function(e){
                var valorDigitado = $(this).val();
                if(valorDigitado === ""){
                    $("#datalist-codigos").html("");
                    return;
                }
                var form = $('#form-buscar-diagnostico');
                var url = form.attr('action').replace(':diagnostico',valorDigitado); 
                var data = form.serialize();

                $.post(url,data,function(result){
                   $('#datalist-codigos').html(result);
                });
                e.preventDefault();
            });

            $("#codigoDiag").on("change",function(e){
                var valorDigitado = $(this).val();
                $('#sistema').val("");
                $('#subsistema').val("");
                $('#descripcion').val("");
                if(valorDigitado === ""){
                    e.preventDefault();
                    return;
                }
                var form = $('#form-cargar-datos-diagnostico');
                var url = form.attr('action').replace(':diagnostico',valorDigitado); 
                var data = form.serialize();
                
                $.post(url,data,function(result){
                    $('#sistema').val(result.diagnostico.lineaSistemas);
                    $('#subsistema').val(result.diagnostico.lineaSubsistema);
                    $('#descripcion').val(result.diagnostico.lineaDescripcion);
                });
                e.preventDefault();
             });
                    
           $("#frm-diagnostico-ausentismo-accidente").on("submit",function(e){
                var entradaVacia = 0;
                $(".input-diagnostico").each(function(){
                   if($(this).val() === ""){
                       entradaVacia =1;
                   }
                });
                if(entradaVacia === 1){
                    alert("Debe Seleccionar un codigo de diagnóstico válido");
                    e.preventDefault();
                    return;
                }
             }); 
        });
    </script>
    @include('analissta.Ausentismos.crearAusentismo.modalCancelar')
@endsection