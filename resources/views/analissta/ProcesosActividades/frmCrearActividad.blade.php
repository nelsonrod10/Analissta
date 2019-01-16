@extends('analissta.layouts.app2')

@section('content')
        @include('analissta.layouts.encabezadoEmpresaCliente')
        <br/>
        <div id="div-nuevaActividad" class="row">
            <div class="columns small-12 medium-7 small-centered">
                <div class="text-center" style="border-bottom:1px solid gray"><b>Proceso | </b><b> {{ $proceso->nombre }}</b></div>
                <div class="text-center" ><b>Crear Nueva Actividad</b></div>
                <br/>
                <div class="row">
                    <form method="post" id="frm-nuevaActividad" name="frm-nuevaActividad" action="{{ route('crear-actividad',['idProceso'=>$proceso->id])}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="columns small-3 medium-2 ">
                                <label for="nombNuevaAct" class="text-right middle"><b>Actividad: </b></label>
                            </div>
                            <div class="columns small-9 medium-8 end">
                                <input type="text" required="true" id="nombNuevaAct" name="nombre"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-3 medium-2 ">
                                <label for="descNuevaAct" class="text-right middle"><b>Descripción: </b></label>
                            </div>
                            <div class="columns small-9 medium-8 end">
                                <textarea required="true" id="descNuevaAct" name="descripcion"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-3 medium-2 ">
                                <label for="maqNuevaAct" class="text-right middle"><b>Equipos<p><small>(Maquinaria/Herramientas): </small></p></b></label>
                            </div>
                            <div class="columns small-9 medium-8 end">
                                <textarea required="true" id="maqNuevaAct" name="equipos"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-10 small-centered end">
                                <div class="columns small-12 medium-6 text-center">
                                    <label class=""><b>Rutinaria</b></label>
                                    <input type="radio" required="true" id="rutinaNuevaActSI" name="rutina" value="Si"/><label for="rutinaNuevaActSI">SI</label>
                                    <input type="radio" required="true" id="rutinaNuevaActNO" name="rutina" value="No"/><label for="rutinaNuevaActNO">NO</label>
                                </div>
                                <div class="columns small-12 medium-6 text-center">
                                    <label class=""><b>Tipo Actividad</b></label>
                                    <input type="checkbox" class="checkTipoActividad" id="interna" name="interna" value="interna"/><label for="interna">Interna</label>
                                    <input type="checkbox" class="checkTipoActividad" id="externa" name="externa" value="externa"/><label for="externa">Externa</label>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="columns small-12 text-center">
                                <input type="submit" value="Crear Actividad" class="button small success"/>
                                <a class="button small alert hollow" href="{{ route('procesos-actividades',['sistema'=>session('sistema')])}}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function(){
        $("#frm-nuevaActividad").on("submit",function(e){
            var flagCheck=0;
           $(".checkTipoActividad").each(function(){
                if($(this).is("input:checked")){
                   flagCheck=1;
                }
             });

             if(flagCheck === 0){
                 alert("Debe seleccionar por lo menos un Tipo de Actividad (Interna / Externa)");
                 e.preventDefault();
             }

        });

         $("#frm-updateActividad").on("submit",function(e){
            var flagCheck=0;
           $(".checkTipoActividad").each(function(){
                if($(this).is("input:checked")){
                   flagCheck=1;
                }
             });

             if(flagCheck === 0){
                 alert("Debe seleccionar por lo menos un Tipo de Actividad (Interna / Externa)");
                 e.preventDefault();
             }

        });
    });
    
</script>
@endsection

