<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<meta name="csrf-token" content="{{ csrf_token() }}">-->
    <link rel="shortcut icon" href="assets/images/hojaazul-128x134-1.png" type="image/x-icon">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Enlaces para foundation y css propios -->  
    <script type="text/javascript" src="{{ asset('foundation/js/jquery-1.11.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('foundation/js/javaScript1.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('foundation/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('foundation/css/estiloPropios.css') }}">
    <link rel="stylesheet" href="{{ asset('foundation/foundation-icons/foundation-icons.css') }}">
</head>
<body onload="nobackbutton()">
    <div class="off-canvas-wrapper">
        <div class="off-canvas-wrapper-inner" data-off-canvas-wrapper>
            <div class="off-canvas position-left" id="offCanvas" data-off-canvas>
                    aca va el menu off-canvas...
            </div>
            <div class="off-canvas-content" data-off-canvas-content>
                <!--<div class="title-bar" data-responsive-toggle="example-menu" data-hide-for="medium">
                    <button class="menu-icon" type="button" data-toggle="example-menu"></button>
                    <div class="title-bar-title">Menu</div>
                    <a class="button small success-2" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        Salir
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>-->

                <div class="top-bar" id="example-menu">
                    <div class="div-ul-menu top-bar-left">
                        <ul class="dropdown menu" data-dropdown-menu>

                        </ul>
                    </div>
                    <div class="div-ul-menu top-bar-right">
                        <ul class="dropdown menu" data-dropdown-menu>
                            <li class="li-logo">
                                <div class="div-logo">
                                    <img src="{{ asset('assets/images/analisstav3.3-2-608x128.png') }}"/>
                                </div>

                            </li>

                            <li><a href="#"></a></li>
                            <li><a href="#"><h4>{{ $datosEmpresa->nombre }}</h4></a></li>
                            <li><a href="#"></a></li><li><a href="#"></a></li><li><a href="#"></a></li><li><a href="#"></a></li><li><a href="#"></a></li>
                            <li><a href="#"></a></li><li><a href="#"></a></li><li><a href="#"></a></li><li><a href="#"></a></li><li><a href="#"></a></li>
                            <li></li>
                            <li>
                                <a><b>{{ Auth::user()->role }} Conectado</b>: {{ Auth::user()->name }} {{ Auth::user()->lastname }}</a></li>
                                @if( Auth::user()->role === 'Asesor')
                                    <!--<li>
                                        <a href="{{route('inicio')}}">Listado de Empresas </a>
                                    </li>-->
                                @endif
                            <!--<li>
                                <a class="button small success-2" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Salir
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>-->
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12 callout">
                        <div class='text-center'>
                            <h5><b>@yield('titulo-encabezado')</b></h5>
                        </div>
                        @include('analissta.layouts.encabezadoEmpresaCliente')
                            
                        
                        <div class="columns small-12 text-center">
                            <br/>
                            <h5><b>@yield('titulo-programacion')</b></h5>
                        </div>
                        <div class="columns small-12 end" style="min-height:250px;height: auto; font-size: 14px">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    
    <!--Enlaces para foundation-->
    <script src="{{ asset('foundation/bower_components/jquery/dist/jquery.js') }}"></script>
    <script src="{{ asset('foundation/bower_components/what-input/what-input.js') }}"></script>
    <script src="{{ asset('foundation/bower_components/foundation-sites/dist/foundation.js') }}"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).foundation();
            /*window.onbeforeunload = function() {
                return "¿Estás seguro que deseas salir de la actual página?";
            };*/
        });
        function nobackbutton(){
            window.location.hash="no-back-button";
            window.location.hash="Again-No-back-button"; //chrome
            window.onhashchange=function(){window.location.hash="no-back-button";};
        }
    </script>
    
    <!-- Scripts 
    <script src="{{ asset('js/app.js') }}"></script>-->
</body>
</html>
<style>
    .title-bar, .top-bar{
    background: #295ca7;
    }
    ul.menu  li a{
    color:white;
    }
    ul.menu ul li a:hover{
    color:white;
    }
    ul.menu ul li a:visited{
    color:white;
    }
    .li-logo{
    width: 15%;
    }
    .div-logo{
    width: 100%;
    }
    .li-logo img{
    width: 100%;
    max-width: 100%;
    }
    .div-ul-menu ul{
    background-color:#295ca7;
    color:white
    }
    ul li .nombre-usuario{
    font-weight:normal;
    }
</style>