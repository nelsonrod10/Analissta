@extends('inicio.layouts.app')

@section('content')
    @section('items-menu')
        <ul class="nav-dropdown collapse pull-xs-right nav navbar-nav show-buttons navbar-toggleable-sm" id="exCollapsingNavbar">
            <li class="nav-item nav-btn"><a class="nav-link btn btn-primary btn-lg btn" href="{{url('')}}">VOLVER</a></li>
        </ul>
    @endsection
    <br/><br/><br/>
    <section class="mbr-section content5" id="content7-2d" data-rv-view="36" style="padding-bottom: 0px">

        <div class="mbr-overlay" style="opacity: 0.3; background-color: #f6f8f5"></div>

            <div class="container">
                <div class="row heading">
                    <div class="col-md-10 text-xs-center col-md-offset-1">
                        <h1 class="mbr-section-title display-3 heading-title" style="color:#4b4c4a">Formulario de Registro</h1>
                        <p class="mbr-section-subtitle text-1 heading-text text-justify" style="color:#4b4c4a">
                            Gracias por querer hacer parte de nuestra comunidad, para ello te invitamos a diligenciar el siguiente formulario.
                        </p>
                    </div>
                </div>
            </div>
    </section>
    <section class="mbr-section content5" id="content7-2d" data-rv-view="36" style="padding-top: 10px">

        <div class="mbr-overlay" style="opacity: 0; background-color: #f6f8f5"></div>
            @if ($errors)
                @include('inicio.layouts.errors')
            @endif
            <div class="container">
                <div class="row heading">
                    <div class="col-sm-12 col-md-10 col-md-offset-1">
                        <div class="row form-group">
                            <label class="col-sm-12">1. ¿ Persona Natural o Juridíca <small><i>(Empresa)</i></small> ?</label>
                            <div class="form-check col-sm-12 col-md-3 div-tipo">
                                <input class="radio-tipo" type="radio" name="tipo" id="natural" value="natural">
                                <label  style="font-weight: normal" for="natural">Persona Natural</label>
                            </div>
                            <div class="form-check col-sm-12 col-md-3 div-tipo">
                                <input class="radio-tipo" type="radio" name="tipo" id="juridica" value="juridica">
                                <label  style="font-weight: normal" for="juridica">Persona Jurídica</label>
                            </div>
                            <div class="col-sm-12 col-md-6 div-cambiar-tipo hide">
                                <a href="{{route('registro-comunidad')}}" class="btn btn-sm btn-info">Cambiar Tipo de Persona (Natural / Jurídica)</a>
                            </div>
                        </div>
                        <div id="persona-natural" class="row form-group hide info-data-general">
                            @include('inicio.registro-comunidad.data-persona-natural')
                        </div>
                        <div id="persona-juridica" class="row form-group hide info-data-general">
                            @include('inicio.registro-comunidad.data-persona-juridica')
                        </div>
                        
                    </div>
                </div>
            </div>
    </section>
    @section('items-footer')
        <p class="card-text mbr-section-text text-2"><a href="{{url('/')}}" class="text-white">Inicio.</a><br><a href="{{url('/')}}#header1-2a" class="text-white">Beneficios.</a><br><a href="{{url('/')}}#features3-j" class="text-white">Modulos</a><br><a href="{{url('comunidad')}}" class="text-white">Comunidad</a><br><a href="{{url('/')}}#subscribe1-26" class="text-white">Contacto</a><br></p>
    @endsection
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
       $(".radio-tipo").on("click",function(){
            $(".info-data-general").each(function(){
                $(this).addClass("hide");
            });
            $(".div-tipo").each(function(){
                $(this).addClass("hide");
            });
            $(".div-cambiar-tipo").removeClass("hide");
            
            $(".form-control").each(function(){
                $(this).removeAttr("required");
                if($(this).hasClass("data-persona-"+$("input[name=tipo]:checked").val()) && $(this).attr("data-required") === "true"){
                    $(this).attr("required","true");
                }
            });
            $("#persona-"+$("input[name=tipo]:checked").val()).removeClass("hide");
       }); 
    });
</script>
@endsection