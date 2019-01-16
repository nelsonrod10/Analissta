@extends('inicio.layouts.app')

@section('content')
    @section('items-menu')
        <ul class="nav-dropdown collapse pull-xs-right nav navbar-nav show-buttons navbar-toggleable-sm" id="exCollapsingNavbar">
            <li class="nav-item nav-btn"><a class="nav-link btn btn-primary btn-lg btn" href="{{url('')}}">VOLVER</a></li>
        </ul>
    @endsection
    <br/><br/><br/><br/>
    <section class="mbr-section msg-box3" id="msg-box3-c" data-rv-view="8" style="background-color: rgb(233, 235, 239); padding-top: 50px; padding-bottom: 50px;">
        <br/>
        <div class="container">
            <div class="row">
                <div class="col-sm-7 col-md-offset-3" style="background-color:#c9c9d6; border-radius:10px">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <br/>
                        <h5 style="color:#323438"><b>Ingrese sus datos</b></h5>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Email</label>
                            <div class="col-md-7">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-7">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">

                            <div class="col-md-6">
                                <div class="checkbox col-md-offset-6">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Recordarme
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <button type="submit" class="btn btn-primary">
                                    Ingresar
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-3">
                                <a class="btn" href="{{ route('password.request') }}">
                                    Recuperar Contraseña
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
    @section('items-footer')
        <p class="card-text mbr-section-text text-2"><a href="{{url('/')}}" class="text-white">Inicio.</a><br><a href="{{url('/')}}#header1-2a" class="text-white">Beneficios.</a><br><a href="{{url('/')}}#features3-j" class="text-white">Modulos</a><br><a href="{{url('comunidad')}}" class="text-white">Comunidad</a><br><a href="{{url('/')}}#subscribe1-26" class="text-white">Contacto</a><br></p>
    @endsection
@endsection


<!--




<form name="frm-ingreso" method="POST" action="">
                    {{ csrf_field() }}
                    <br/>
                    <h5 style="color:#323438"><b>Ingrese sus datos</b></h5>
                    <div class="form-group text-justify" >
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email" required="" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ingrese su correo electrónico"/>  
                    </div>
                    <div class="form-group text-justify">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password" name="password" required class="form-control" id="exampleInputPassword1" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Ingresar</button>        
                    </div>
                </form>
-->