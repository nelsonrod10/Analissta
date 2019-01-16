<form method="POST" action="{{route('comunidad-profesionales.store')}}">
    {{ csrf_field() }}
    <input type="hidden" hidden="" class="hide" name="tipo" value="natural"/>
    <div class="form-row">
        <div class="form-group col-md-6">
          <label>2. Nombres y Apellidos <small style="color:#eb6666" class="form-text text-muted">(obligatorio)</small></label>
          <input type="text" class="form-control data-persona-natural" data-required="true" name="nombre" placeholder="Nombres y Apellidos">

        </div>
        <div class="form-group col-md-6">
          <label >3. Profesión  <small style="color:#eb6666" class="form-text text-muted">(obligatorio)</small></label>
          <input type="text" class="form-control data-persona-natural" data-required="true" name="profesion" placeholder="Ingeniero, Médico, Técnico, Tecnólogo etc...">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label >4. Ciudad  <small style="color:#eb6666" class="form-text text-muted">(obligatorio)</small></label>
            <input type="text" class="form-control data-persona-natural" data-required="true" name="ciudad" placeholder="Ciudad donde reside">
        </div>
        <div class="form-group col-md-6">
            <label>5. Email  <small style="color:#eb6666" class="form-text text-muted">(obligatorio)</small></label>
          <input type="email" class="form-control data-persona-natural" data-required="true" name="email" placeholder="micorreo@correo.com">
        </div>
        
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
          <label>6. Teléfono</label>
          <input type="number" class="form-control data-persona-natural" data-required="false" name="telefono" placeholder="Teléfono de contacto">
        </div>
        <div class="form-group col-md-6">
          <label>7. Licencia SST</label>
          <input type="text" class="form-control data-persona-natural" data-required="false" name="licencia" placeholder="Número Licencia SST, si la tiene">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
          <label>8. Descripción Perfil:</label>
          <small class="form-text text-muted">Realice una descripción breve de los servicios que ofrece</small>
          <textarea style="min-height: 100px; height: auto" maxlength="400" class="form-control data-persona-natural" data-required="false" name="perfil"></textarea>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
            <input id="politica-tratamiento" type='checkbox' name="aceptacion" value="Si" required=""/>
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
