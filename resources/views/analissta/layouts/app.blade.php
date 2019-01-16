<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">
<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-114873719-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-114873719-1');
    </script>
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
<body>
    <div class="off-canvas-wrapper">
        <div class="off-canvas-wrapper-inner" data-off-canvas-wrapper>
            <div class="off-canvas position-left" id="offCanvas" data-off-canvas>
                    aca va el menu off-canvas...
            </div>
            <div class="off-canvas-content" data-off-canvas-content>
                <div class="title-bar" data-responsive-toggle="example-menu" data-hide-for="medium">
                    <button class="menu-icon" type="button" data-toggle="example-menu"></button>
                    <div class="title-bar-title">Menu</div>
                    <a class="button small success-2  float-right" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        Salir
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>

                <div class="top-bar" id="example-menu">
                    <div class="div-ul-menu top-bar-left show-for-small-only">
                        <ul class="vertical dropdown menu" data-dropdown-menu>
                            <li>
                                <a><b>{{ Auth::user()->role }} Conectado</b>: {{ Auth::user()->name }} {{ Auth::user()->lastname }}</a></li>
                                @if( Auth::user()->role === 'Asesor')
                                    <li>
                                        <a href="{{route('inicio')}}">Listado de Empresas </a>
                                    </li>
                                @endif
                            <li>
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
                            <li><a href="#"><h4 class="show-for-medium">{{ $datosEmpresa->nombre }}</h4></a></li>
                            <li><a href="#"></a></li><li><a href="#"></a></li><li><a href="#"></a></li><li><a href="#"></a></li><li><a href="#"></a></li>
                            <li><a href="#"></a></li><li><a href="#"></a></li><li><a href="#"></a></li><li><a href="#"></a></li><li><a href="#"></a></li>
                            <li></li>
                            <li>
                                <a><b>{{ Auth::user()->role }} Conectado</b>: {{ Auth::user()->name }} {{ Auth::user()->lastname }}</a></li>
                                @if( Auth::user()->role === 'Asesor')
                                    <li>
                                        <a href="{{route('inicio')}}">Listado de Empresas </a>
                                    </li>
                                @endif
                            <li>
                                <a class="button small success-2 float-right" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Salir
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                
                @yield('sistem-menu')
                <div class="row">
                    <div class="columns small-12 callout">
                        <div class='text-center'>
                            <h5><b>@yield('titulo-encabezado')</b></h5>
                        </div>
                        
                        <div class="row columns text-center">
                            <div class="expanded small button-group">
                                @yield('buttons-submenus')
                            </div>
                            
                        </div>
                        <div class="columns small-12">
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
    
    <script>
        $(document).ready(function(){
            $(document).foundation();
        });

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
<!--
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    @guest
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</div>
-->