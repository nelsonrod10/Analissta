@extends('inicio.layouts.app')


@section('content')
    <br/><br/><br/>
    <section class="mbr-section content5" id="content7-2d" data-rv-view="36" style="padding-bottom: 0px">

        <div class="mbr-overlay" style="opacity: 0; background-color: #f6f8f5"></div>

            <div class="container">
                <div class="row heading">
                    <div class="col-sm-12 col-md-10 col-md-offset-1">
                        <h1 class="mbr-section-title display-3 heading-title" style="color:#4b4c4a">Gracias por unirte a nuestra comunidad</h1>
                        <p class="mbr-section-subtitle text-1 heading-text" style="color:#4b4c4a">
                            Antes de terminar, quizas conozcas a alguien que este interesado en formar parte de nuestra comunidad. Simplemente registra el email y nosotros lo contactámos
                        </p>
                    </div>
                </div>
                @include('sugerir-invitados')
                
            </div>
    </section>
    <section class="mbr-section content5" id="content7-2d" data-rv-view="36" style="padding-top: 30px;padding-bottom:100px">
        <div class="mbr-overlay" style="opacity: 0; background-color: #f6f8f5"></div>
        <div class="container">
            <div class="row heading text-center">
                <div class="col-sm-12 col-md-10 col-md-offset-1">
                    <a class="btn btn-success" href="{{url("")}}">Finalizar Registro</a>
                </div>
            </div>
        </div>
    </section>
@endsection    