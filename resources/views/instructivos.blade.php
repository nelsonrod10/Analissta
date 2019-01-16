@extends('inicio.layouts.app')

@section('content')
    @section('items-menu')
        <ul class="nav-dropdown collapse pull-xs-right nav navbar-nav show-buttons navbar-toggleable-sm" id="exCollapsingNavbar">
            <li class="nav-item nav-btn"><a class="nav-link btn btn-primary btn-lg btn" href="{{url('')}}">VOLVER</a></li>
        </ul>
    @endsection
    <br/><br/><br/><br/>
    <section class="mbr-section subscribe1" id="subscribe1-26" data-rv-view="17" style="background-color: rgb(255, 255, 255); padding-top: 30px; padding-bottom: 120px;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-lg-8 col-lg-offset-2 text-xs-center">
                    <div class="col-xs-12 col-lg-8 col-lg-offset-2">
                        <h1 class="mbr-section-title display-2">Puedes ver lo fácil...</h1>
                    </div>
                </div>
                <div class="col-xs-6 col-lg-4">
                    <iframe src="https://www.youtube.com/embed/dX1ScSo6Nng" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
                <div class="col-xs-6 col-lg-4">
                    <iframe src="https://www.youtube.com/embed/eZSBVxiZjjk" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
                <div class="col-xs-6 col-lg-4">
                    <iframe src="https://www.youtube.com/embed/VnphwCLME-o" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
                <div class="col-xs-6 col-lg-4">
                    <iframe src="https://www.youtube.com/embed/fKIHPotIZ0w" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
                <div class="col-xs-6 col-lg-4">
                    <iframe src="https://www.youtube.com/embed/Z4f4zKjWhvs" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
                <div class="col-xs-6 col-lg-4">
                    <iframe src="https://www.youtube.com/embed/yNF50ddvXDk" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
                <div class="col-xs-6 col-lg-4">
                    <iframe src="https://www.youtube.com/embed/j_6Q3zwYt4g" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
                <div class="col-xs-6 col-lg-4">
                    <iframe src="https://www.youtube.com/embed/bfhBhEUhU_Y" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
                <div class="col-xs-6 col-lg-4">
                    <iframe src="https://www.youtube.com/embed/Mg9OtST4zSk" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
                <div class="col-xs-6 col-lg-4">
                    <iframe src="https://www.youtube.com/embed/MweymcFcfpk" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>

            </div>
            <div class="row">
                <div class="col-xs-12 col-lg-8 col-lg-offset-2 text-xs-center">
                    <div class="col-xs-12 col-lg-8 col-lg-offset-2">
                        <h2>¿Quieres conocerlo?, solicita tu DEMO</h2>
                    </div>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-xs-12 col-lg-8 col-lg-offset-2 text-xs-center">
                    <div class="col-xs-12 col-lg-8 col-lg-offset-2">
                        <div class="mbr-plan-btn"><a href="{{url('/')}}#pricing-table3-17" class="btn btn-lg btn-black-outline">ME INTERESA</a></div>
                    </div>
                </div>
            </div>    

    </section>
    @section('items-footer')
        <p class="card-text mbr-section-text text-2"><a href="{{url('/')}}" class="text-white">Inicio.</a><br><a href="{{url('/')}}#header1-2a" class="text-white">Beneficios.</a><br><a href="{{url('/')}}#features3-j" class="text-white">Modulos</a><br><a href="{{url('comunidad')}}" class="text-white">Comunidad</a><br><a href="{{url('/')}}#subscribe1-26" class="text-white">Contacto</a><br></p>
    @endsection
@endsection