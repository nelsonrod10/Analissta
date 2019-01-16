<div id="modal-agregar-evidencia-inspeccion-{{$calendario->id}}" class="reveal" data-reveal>
    <div class="row columns text-center">
        <h5>Agregar Evidencia Inspección - {{$inspeccion->nombre}}</h5>
        <h6>{{$calendario->mes}}, Semana {{$calendario->semana}}</h6>
        <form action="{{route('gestionar-evidencia.store')}}" enctype="multipart/form-data" method="post">
            {{ csrf_field() }}
            <input type="hidden" class="hide" hidden="true" id="origen_id"  name="origen_id" value="{{$calendario->id}}"/>
            <input type="hidden" class="hide" hidden="true" name="origen_table" value="{{$origenTable}}"/>
            <div class="row columns text-center">
                <input type="file" name="file" required="">
            </div>
            <div class="row columns text-right">
                <a class="button small alert" aria-label="Close modal" data-close>Cancelar</a>
                <button type="submit" class="button small">Guardar Archivo</button>
            </div>
        </form>
        
    </div>
    <button class="close-button" data-close="" aria-label="Close modal" type="button">
        <span aria-hidden="true">x</span>
    </button> 
</div>
