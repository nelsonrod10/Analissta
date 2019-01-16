<div class="small reveal" data-reveal="" id="reveal-cancelar-crear-ausentismo-accidente">
    <div class="row columns text-center">
        <i class="fi-alert" style="font-size:32px; color:red"></i>
    </div>
    <div class="row columns text-center">
        <div style="font-size:14px"><b>¿Desea cancelar la creación del ausentismo del accidente de {{ucwords(strtolower($accidente->accidentado->nombre))}} {{ucwords(strtolower($accidente->accidentado->apellidos))}}?</b></div>
        <div style="font-size:14px"><b>Se perderán todos los datos configurados</b></div>
    </div>
    <br/>
    <div class="row columns text-center">
        <a class="button small success-2" href="{{route('cancelar-crear-ausentismo-accidente',['idAccidente'=>$accidente->id,'idAusencia'=>$idAusentismo])}}" >Confirmar</a>
        <a class="button small alert" data-close="">Cancelar</a>
    </div>
</div>
