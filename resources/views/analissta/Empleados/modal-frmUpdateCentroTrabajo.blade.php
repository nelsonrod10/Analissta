<div id="modal-cambiar-centro-trabajo-{{$empleado->id}}" class="reveal" data-reveal>
    <div class="row columns text-center">
        <br/>
        <div class="row columns text-center">
            <h5>¿Desea cambiar el centro de trabajo de </h5>
            <h5>{{ucwords($empleado->nombre)}} {{ucwords($empleado->apellidos)}}?</h5>
        </div>
        <br/>
        <form action="{{route('cambiar-centro-trabajo',$empleado)}}" enctype="multipart/form-data" method="post">
            {{ csrf_field() }}
            
            <div class="row ">
                <div class="columns small-12 medium-4">
                    <label for="centro">Seleccione un centro de Trabajo: </label>
                </div>
                <div class="columns small-12 medium-8">
                <select id="centro" name="centro" required>
                    <option value=''>Seleccione un centro...</option>
                    @foreach($empresa->centrosTrabajo as $centro)
                        <option value='{{$centro->id}}' <?php echo ($centro->id === $empleado->centrosTrabajos_id)?'selected':''?>>{{$centro->nombre}}</option>
                    @endforeach
                </select>
                </div>    
            </div>
            <div class="row columns text-right">
                <a class="button small alert" aria-label="Close modal" data-close>Cancelar</a>
                <button type="submit" class="button small">Actualizar Centro</button>
            </div>    
        </form>
        
    </div>
    <button class="close-button" data-close="" aria-label="Close modal" type="button">
        <span aria-hidden="true">x</span>
    </button> 
</div>