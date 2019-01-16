<div class="small reveal" data-reveal="" id="reveal-cancelar-programacion-inspeccion">
    <div class="row columns text-center">
        <i class="fi-alert" style="font-size:32px; color:red"></i>
    </div>
    <div class="row columns text-center">
        <div style="font-size:14px"><b>¿Confirma que desea cancelar la programación de la inspeccion {{$inspeccion->nombre}}?</b></div>
        <div style="font-size:14px"><b>Se perderán todos los datos configurados</b></div>
    </div>
    <br/>
    <div class="row columns text-center">
        <a class="button small success-2" href="{{route('cancelar-programacion-inspeccion',['id'=>$inspeccion->id,'tipo'=>$tipoInspeccion])}}" >Confirmar</a>
        <a class="button small alert" data-close="">Cancelar</a>
    </div>
</div>
