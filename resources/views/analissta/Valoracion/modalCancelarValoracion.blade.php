<!--Modal que confirma la cancelacion de una valoración en proceso, cuando en cualquiera de los pasos de configuracion se hace click en "Cancelar"-->
<div id="modal-confirm-cancelValoracion" class="reveal" data-reveal>
    <div class="row columns text-center">
        <div><i><b>¿Esta seguro de cancelar esta valoración?</b></i></div>
        <div><i>Se perderan todos los datos configurados</i></div>
        <br/>
        <div>
            <a class="button small" href="{{ route('cancelar-valoracion',['idActividad'=>$actividad->id])}}">Confirmar</a>
            <a class="button small alert" aria-label="Close modal" data-close>Cancelar</a>
        </div>
    </div>
    <button class="close-button" data-close="" aria-label="Close modal" type="button">
        <span aria-hidden="true">x</span>
    </button> 
</div>
