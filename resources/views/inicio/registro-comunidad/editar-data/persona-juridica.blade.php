@extends('inicio.layouts.app')

@section('content')
    <br/><br/><br/>
    <section class="mbr-section content5" id="content7-2d" data-rv-view="36" style="padding-bottom: 0px">
        <div class="mbr-overlay" style="opacity: 0.3; background-color: #f6f8f5"></div>
            <div class="container">
                <div class="row heading">
                    <div class="col-md-10 text-xs-center col-md-offset-1">
                        <h5 class="mbr-section-title display-3 heading-title" style="color:#4b4c4a">Editar Datos de Registro</h5>
                    </div>
                </div>
            </div>
    </section>
    <section class="mbr-section content5" id="content7-2d" data-rv-view="36" style="padding: 0px">
        <div class="mbr-overlay" style="opacity: 0.3; background-color: #f6f8f5"></div>
        @if ($errors)
            @include('inicio.layouts.errors')
        @endif
        <div class="container">
            <div class="row heading">
                <div class="col-md-10 col-md-offset-1">
                    <form method="POST" action="{{route('comunidad-empresas.update',$miembro)}}">
                        {{ csrf_field() }}
                        {{method_field('PUT')}}
                        <input type="hidden" hidden="" class="hide" name="tipo" value="natural"/>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>1. Nombre de la Empresa <small style="color:#eb6666" class="form-text text-muted">(obligatorio)</small></label>
                                <input type="text" class="form-control" value="{{$miembro->nombre}}" name="nombre" placeholder="Razón Social de la Empresa" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>2. NIT <small style="color:#eb6666" class="form-text text-muted">(obligatorio)</small></label>
                                <input type="number" class="form-control" value="{{$miembro->identificacion}}" name="identificacion" placeholder="NIT de la Empresa" required="">
                                <small class="form-text text-muted">El NIT será utilizado para verificar la existencia de la empresa</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label >3. Ciudad  <small style="color:#eb6666" class="form-text text-muted">(obligatorio)</small></label>
                                <input type="text" class="form-control" value="{{$miembro->ciudad}}" name="ciudad" placeholder="Ciudad donde reside" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>4. Página Web</label>
                                <input type="text" class="form-control" value="{{$miembro->web}}" name="web" placeholder="www.analissta.com">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>5. Email  <small style="color:#eb6666" class="form-text text-muted">(obligatorio)</small></label>
                                <input type="email" class="form-control " value="{{$miembro->email}}" name="email" placeholder="micorreo@correo.com" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>6. Teléfono</label>
                                <input type="number" class="form-control" value="{{$miembro->telefono}}" name="telefono" placeholder="Teléfono de contacto">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>7. Licencia SST</label>
                                <input type="text" class="form-control" value="{{$miembro->licencia}}" name="licencia" placeholder="Número Licencia SST, si la tiene">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                              <label>8. Descripción Perfil Empresa:</label>
                              <small class="form-text text-muted">Realice una descripción breve de los servicios que ofrece la empresa</small>
                              <textarea style="min-height: 100px; height: auto" maxlength="400" class="form-control" name="perfil">{{$miembro->perfil}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <a class="btn btn-danger " data-toggle="modal" data-target="#exampleModalCenter">Cancelar</a>  
                                <button type="submit" class="btn btn-primary">Guardar y Continuar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>    
        </div>
    </section>
    
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Cancelar Registro</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{route('comunidad-empresas.destroy',$miembro)}}">
                {{csrf_field()}}
                {{method_field("DELETE")}}
                <div class="modal-body">
                    <p>¿Está seguro de cancelar su registro?</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-black">Confirmar :(</button>
                </div>
            </form>      
          </div>
        </div>
    </div>
    
@endsection   
