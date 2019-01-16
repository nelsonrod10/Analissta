<!--Modal que confirma la cancelación de crear una empresa, cuando se hace click en "Cancelar"-->
    <div id="modal-confirm-borrarEmpresa" class="reveal" data-reveal>
        <div class="row columns text-center">
            <div><i><b>¿Esta seguro de eliminar la creación de esta empresa?</b></i></div>
            <div><i>Se perderan todos los datos configurados</i></div>
            <br/>
            <div>
                <form method="POST" name="frm-cancelar-crear-empresa" action='{{ route('cancelar-crear-empresa')}}'>
                    {{ csrf_field() }}
                    <input type="submit" class="button small" value="Confirmar"/>
                    <a class="button small alert" aria-label="Close modal" data-close>Cancelar</a>
                </form>
                
            </div>
        </div>
        <button class="close-button" data-close="" aria-label="Close modal" type="button">
            <span aria-hidden="true">x</span>
        </button> 
    </div>
