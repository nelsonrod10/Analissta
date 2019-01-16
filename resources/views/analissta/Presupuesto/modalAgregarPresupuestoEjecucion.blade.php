<div id="modal-agregar-presupuesto-medida-{{$calendario->id}}" class="reveal" data-reveal>
    <div class="row columns text-center">
        <br/>
        <h5>Agregar Presupuesto Actividad - {{$medida->nombre}}</h5>
        <h6>{{$calendario->mes}}, Semana {{$calendario->semana}}</h6>
        <form action="{{route('presupuesto-ejecucion.store')}}" enctype="multipart/form-data" method="post">
            {{ csrf_field() }}
            <input type="hidden" class="hide" hidden="true" id="calendario_id"  name="calendario_id" value="{{$calendario->id}}" required=""/>
            <input type="hidden" class="hide" hidden="true" name="calendario_table" value="{{$calendario_table}}" required=""/>
            <div class="row">
                <div class="columns small-12">
                    <label for="item" class='text-left'><b>Descripción</b></label>
                    <select name="presupuesto_id" id="item" required="">
                        <option value="">Seleccione...</option>
                        @foreach($presupuesto as $item)
                        <option value="{{$item->id}}">{{$item->item}}</option>
                        @endforeach
                    </select>
                    <!--<input type="text" id="item" name="item" placeholder="Describa a que corresponde; ejm: Compra de materiales">-->
                </div>
            </div>
            <div class="row">
                <div class="columns small-12">
                    <label for="observaciones" class='text-left'><b>Observaciones</b></label>
                    <textarea type="text" id="observaciones" name="observaciones" style="height: auto;min-height: 80px;"placeholder="Observaciones generales" required=""></textarea>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12">
                    <label for="valor" class='text-left'><b>Valor Gastado</b></label>
                    <input type="number" id="valor" name="valor" placeholder="Valor gastado en pesos colombianos" required="">
                </div>
            </div>
            <div class="row">
                <div class="columns small-12 text-right">
                    <a class="button small alert" aria-label="Close modal" data-close>Cancelar</a>
                    <button type="submit" class="button small">Agregar Item</button>
                </div>
            </div>
            
        </form>
    </div>
    <button class="close-button" data-close="" aria-label="Close modal" type="button">
        <span aria-hidden="true">x</span>
    </button> 
</div>
