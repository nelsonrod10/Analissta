<form method="POST" action="{{route('comunidad-empresas.store')}}">
    {{ csrf_field() }}
    <input type="hidden" hidden="" class="hide" name="tipo" value="juridica"/>
    <div class="form-row">
        <div class="form-group col-md-6">
          <label>2. Nombre de la Empresa <small style="color:#eb6666" class="form-text text-muted">(obligatorio)</small></label>
          <input type="text" class="form-control data-persona-juridica" data-required="true" name="nombre" placeholder="Razón Social de la Empresa">
        </div>
        <div class="form-group col-md-6">
          <label>3. NIT <small style="color:#eb6666" class="form-text text-muted">(obligatorio)</small></label>
          <input type="number" class="form-control data-persona-juridica" data-required="true" name="identificacion" placeholder="NIT de la Empresa">
          <small class="form-text text-muted">El NIT será utilizado para verificar la existencia de la empresa</small>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>4. Ciudad  <small style="color:#eb6666" class="form-text text-muted">(obligatorio)</small></label>
            <input type="text" class="form-control data-persona-juridica" data-required="true" name="ciudad" placeholder="Ciudad donde se encuentra la empresa">
        </div>
        <div class="form-group col-md-6">
            <label>5. Página Web</label>
          <input type="text" class="form-control data-persona-juridica" data-required="false" name="web" placeholder="www.analissta.com">
        </div>

    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>6. Email  <small style="color:#eb6666" class="form-text text-muted">(obligatorio)</small></label>
            <input type="email" class="form-control data-persona-juridica" data-required="true" name="email" placeholder="micorreo@empresa.com">
        </div>
        <div class="form-group col-md-6">
            <label>7. Teléfono</label>
            <input type="number" class="form-control data-persona-juridica" data-required="false" name="telefono" placeholder="Teléfono de contacto">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
          <label>8. Licencia SST</label>
          <input type="text" class="form-control data-persona-juridica" data-required="false" name="licencia" placeholder="Número Licencia SST de la Empresa, si la tiene">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
          <label>9. Descripción Perfil Empresa:</label>
          <small class="form-text text-muted">Realice una descripción breve de los servicios que ofrece la empresa</small>
          <textarea style="min-height: 100px; height: auto" maxlength="400" class="form-control data-persona-juridica" data-required="false" name="perfil"></textarea>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
            <input id="politica-tratamiento" type='checkbox' name="aceptacion" value="SI" required=""/>
            <label for="politica-tratamiento">Acepta nuestra política de tratamiento de datos <a data-toggle='modal' data-target='#tramiento-datos'>(Ver Política)</a></label>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <a href="{{url('comunidad')}}" class="btn btn-danger ">Cancelar</a>  
          <button type="submit" class="btn btn-primary">Continuar</button>
        </div>
    </div>
</form>
