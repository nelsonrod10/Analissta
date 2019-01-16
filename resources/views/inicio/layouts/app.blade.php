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
    <link rel="shortcut icon" href="{{ asset('assets/images/hojaazul-128x134-1.png')}}" type="image/x-icon">
    <meta name="description" content="Buscando una nueva opción en software para la gestión de su sistema en SST?, Bienvenido a analiSSTa">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/web/assets/mobirise-icons-bold/mobirise-icons-bold.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/web/assets/mobirise-icons/mobirise-icons.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Libre+Franklin:400,400i,600,600i">
    <link rel="stylesheet" href="{{ asset('assets/tether/tether.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/dropdown/css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/theme/css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/mobirise/css/mbr-additional.css')}}" type="text/css">
    <style>
        a.nav-link.link{
            border-bottom: 3px solid transparent;
        }
        a.nav-link.link:hover{
            background: none;
            border-bottom: 3px solid #89bd56;
            padding: 0px;
        }
    </style>
</head>

<body>
    <section class="menu1" id="menu2-0" data-rv-view="0">
        <nav class="navbar navbar-dropdown navbar-fixed-top">
            <div class="container-fluid">
                <div class="mbr-table">
                    <div class="mbr-table-cell logo">
                        <div class="navbar-brand">
                            <a href="{{url('/')}}" class="navbar-logo"><img src="{{asset("assets/images/analisstav3.3-2-608x128.png")}}" alt="Analissta" title="Analissta" style="height: 3rem;"></a>
                        </div>
                    </div>
                    <div class="mbr-table-cell text">
                        <button class="navbar-toggler pull-xs-right hidden-md-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar">
                            <div class="hamburger-icon"></div>
                        </button>
                        @yield('items-menu')
                        <button hidden="" class="navbar-toggler navbar-close" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar">
                            <div class="close-icon"></div>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    </section>
    @yield('content')
    
    
    <section class="mbr-section footer1" id="footer1-28" data-rv-view="23" style="background-color: rgb(31, 128, 104); padding-top: 40px; padding-bottom: 40px;">
        <div class="container">
        <div class="row">
            <div class="mbr-cards-col col-xs-12 col-sm-6 col-lg-6">
                <div class="card">
                    <div class="card-box col-xs-9 col-lg-12">
                        @yield('items-footer')
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-xs-12 text-xs-center">
                    <p class="card-text mbr-section-text text-1">analiSSTA, <span id="current-year"></span></p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/web/assets/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/tether/tether.min.js')}}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/smooth-scroll/smooth-scroll.js')}}"></script>
    <script src="{{ asset('assets/dropdown/js/script.min.js')}}"></script>
    <script src="{{ asset('assets/touch-swipe/jquery.touch-swipe.min.js')}}"></script>
    <script src="{{ asset('assets/jarallax/jarallax.js')}}"></script>
    <script src="{{ asset('assets/cookies-alert-plugin/cookies-alert-core.js')}}"></script>
    <script src="{{ asset('assets/cookies-alert-plugin/cookies-alert-script.js')}}"></script>
    <script src="{{ asset('assets/theme/js/script.js')}}"></script>
    <script src="{{ asset('assets/formoid/formoid.min.js')}}"></script>
    <script>
        $("#current-year").text((new Date()).getFullYear());
    </script>
    <div id="scrollToTop" class="scrollToTop mbr-arrow-up"><a style="text-align: center;"><i class="mbr-arrow-up-icon"></i></a></div>
  <input name="cookieData" type="hidden" data-cookie-text="Lea nuestra <a data-toggle='modal' data-target='#tramiento-datos'>politica de tratamiento de datos</a>.">
  @include('inicio.layouts.modal-tratamiento-datos')
  @yield('scripts')
</body>

</html>
