<div id="reveal-agregar-evidencia-accidente" class="reveal" data-reveal>
    <div class="row columns text-center">
        <br/>
        <h5>Agregar Evidencia Accidente - {{$accidente->created_at}}</h5>
        
        <form action="{{route('gestionar-evidencia.store')}}" enctype="multipart/form-data" method="post">
            {{ csrf_field() }}
            <input type="hidden" class="hide" hidden="true"  name="origen_id" value="{{$accidente->id}}"/>
            <input type="hidden" class="hide" hidden="true" name="origen_table" value="Accidente"/>
            <div class="row columns text-center">
                <input type="file" name="file" required="">
            </div>
            <button type="submit" class="button small">Guardar Archivo</button>
        </form>
    </div>
    <button class="close-button" data-close="" aria-label="Close modal" type="button">
        <span aria-hidden="true">x</span>
    </button> 
</div>
