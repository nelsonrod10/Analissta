
<!--Modal cambiar valor real presupuesto-->
<?php
    use App\Http\Controllers\PresupuestoController;
?>
<div id="modal-valor-real-{{$item->id}}" class="reveal" data-reveal>
    <div class="row columns text-center">
        <div><h6><b>Reportar Valor Real Presupuesto</b></h6></div>
        <div><b>{{PresupuestoController::getNombreOrigen($item->id)}}</b></div>
        <div>Valor presupuestado $ {{$item->valor}} COP</div>
        <br/>
        <form method="POST" action="{{route('general.update',$item)}}">
            {{ method_field('PUT') }}
            {{ csrf_field() }}
            <div class="row">
                <div class="columns small-12 text-center"><b>Valor Real</b></div>
                <div class="columns small-12 small-centered">
                    <input type="number" name="valor_real" required="" value="{{$item->valor_real}}" placeholder="Escriba aquí el valor real" />
                </div>
            </div>
            
            <div class="row columns">
                <input type="submit" class="button small" value="Confirmar"/>
                <a class="button small alert" aria-label="Close modal" data-close>Cancelar</a>
            </div>
        </form>
        
    </div>
    <button class="close-button" data-close="" aria-label="Close modal" type="button">
        <span aria-hidden="true">x</span>
    </button> 
</div>

