<div class="small reveal" data-reveal="" id="reveal-cancelar-crear">
    <div class="row columns text-center">
        <i class="fi-alert" style="font-size:32px; color:red"></i>
    </div>
    <div class="row columns text-center">
        <div style="font-size:14px"><b>¿Confirma que desea cancelar?</b></div>
        <div style="font-size:14px"><b>Se perderán todos los datos relacionados con este cierre</b></div>
    </div>
    <br/>
    <div class="row columns text-center">
        <a class="button small success-2" href="{{route('cancelar-cierre-hallazgo',['id'=>$idHallazgo,'idCierre'=>$idCierre])}}" >Confirmar</a>
        <a class="button small alert" data-close="">Cancelar</a>
    </div>
</div>
