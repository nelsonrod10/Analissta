@extends('inicio.layouts.app')

@section('content')
    @section('items-menu')
        <ul class="nav-dropdown collapse pull-xs-right nav navbar-nav show-buttons navbar-toggleable-sm" id="exCollapsingNavbar">
            <li class="nav-item nav-btn"><a class="nav-link btn btn-primary btn-lg btn" href="{{url('')}}">VOLVER</a></li>
        </ul>
    @endsection
    <br/><br/><br/>
    <section class="mbr-section mbr-section-hero mbr-section-full header8 mbr-parallax-background mbr-after-navbar" id="header8-29" data-rv-view="2" style="background-image: url({{asset("assets/images/comunidad-1.jpg")}});">
        <div class="mbr-overlay" style="opacity: 0.5; background-color: rgb(45, 45, 45);">
        </div>
            <div class="mbr-table-cell">
                <div class="container">
                        <div id="title">
                            <div class="mbr-table-cell mbr-left-padding-md-up col-md-6 text-xs-center text-md-left">
                                <h3 class="mbr-section-title display-2" style="color: white;"><strong>Únete a nuestra comunidad de profesionales</strong></h3>
                                <div class="mbr-section-subtitle sub-2">
                                    Justo en este instante, alguien está necesitando un profesional como tu...
                                </div>
                            </div>
                        </div>
                </div> 
            </div> 
        <div class="mbr-arrow mbr-arrow-floating" aria-hidden="true"><a href="#content7-2d"><i class="mbri-down"></i></a></div>            
    </section>
    <section class="mbr-section content5" id="content7-2d" data-rv-view="36">

        <div class="mbr-overlay" style="opacity: 0.3; background-color: #f6f8f5"></div>

            <div class="container">
                <div class="row heading">
                    <div class="col-md-10 text-xs-center col-md-offset-1">

                        <h1 class="mbr-section-title display-3 heading-title" style="color:#4b4c4a">La comunidad de profesionales SST</h1>
                        <p class="mbr-section-subtitle text-1 heading-text text-justify" style="color:#4b4c4a">
                            En Analissta queremos conformar la comunidad más grande de profesionales en el área de SST
                            <br>
                            Sabemos que existen muchos grupos de profesionales en redes sociales, pero también tenemos claro que las empresas buscan esos profesionales en sitios que les brinden confianza.
                            <br>
                            
                        </p>

                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row heading">
                    <div class="col-md-10 text-xs-center col-md-offset-1">
                        <p>Si eres un profesional o empresa que presta servicios relacionados con Seguridad y Salud en el Trabajo te invitamos a hacer parte de nuestra comunidad.</p>
                        <a class="btn btn-info btn-lg" href="{{route('registro-comunidad')}}">Registro</a>
                    </div>
                </div>
            </div>
    </section>
    <section class="mbr-section content5" id="content7-2d" data-rv-view="36" style="padding-top: 0px;padding-bottom:50px">

        <div class="mbr-overlay" style="opacity: 0; background-color: #f6f8f5"></div>

            <div class="container">
                <div class="row heading">
                    <div class="col-sm-12 col-md-10 col-md-offset-1">
                        <h1 class="mbr-section-title display-3 heading-title" style="color:#4b4c4a">¿Sabes de alguien que pueda interesarle?</h1>
                        <p class="mbr-section-subtitle text-1 heading-text" style="color:#4b4c4a">
                            Quizas conozcas a alguien que este interesado en formar parte de nuestra comunidad. Simplemente registra el email y nosotros lo contactámos
                        </p>
                    </div>
                </div>
                @include('sugerir-invitados')
            </div>
    </section>
    @section('items-footer')
        <p class="card-text mbr-section-text text-2"><a href="{{url('/')}}" class="text-white">Inicio.</a><br><a href="{{url('/')}}#header1-2a" class="text-white">Beneficios.</a><br><a href="{{url('/')}}#features3-j" class="text-white">Modulos</a><br><a href="{{url('comunidad')}}" class="text-white">Comunidad</a><br><a href="{{url('/')}}#subscribe1-26" class="text-white">Contacto</a><br></p>
    @endsection
@endsection
