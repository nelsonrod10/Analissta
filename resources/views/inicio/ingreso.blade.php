<!DOCTYPE html>
<?php
    $flag = isset($_GET["flag"])?$_GET["flag"]:"";
?>
<html >
<head>
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-114873719-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-114873719-1');
    </script>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="generator" content="Mobirise v4.6.3, mobirise.com">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="assets/images/hojaazul-128x134-1.png" type="image/x-icon">
  <meta name="description" content="Buscando una nueva opción en software para la gestión de su sistema en SST?, Bienvenido a analiSSTa">
  <title>analiSSTa</title>
  <link rel="stylesheet" href="assets/web/assets/mobirise-icons-bold/mobirise-icons-bold.css">
  <link rel="stylesheet" href="assets/web/assets/mobirise-icons/mobirise-icons.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Libre+Franklin:400,400i,600,600i">
  <link rel="stylesheet" href="assets/tether/tether.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/dropdown/css/style.css">
  <link rel="stylesheet" href="assets/theme/css/style.css">
  <link rel="stylesheet" href="assets/mobirise/css/mbr-additional.css" type="text/css">
  
  
  
</head>
<body>
  <section class="menu1" id="menu2-0" data-rv-view="0">
    <nav class="navbar navbar-dropdown navbar-fixed-top">
        <div class="container-fluid">
            <div class="mbr-table">
                <div class="mbr-table-cell logo">
                    <div class="navbar-brand">
                        <a href="{{url('/')}}" class="navbar-logo"><img src="assets/images/analisstav3.3-2-608x128.png" alt="Analissta" title="Analissta" style="height: 3rem;"></a>
                    </div>
                </div>
                <div class="mbr-table-cell text">
                    <button class="navbar-toggler pull-xs-right hidden-md-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar">
                        <div class="hamburger-icon"></div>
                    </button>
                    <ul class="nav-dropdown collapse pull-xs-right nav navbar-nav show-buttons navbar-toggleable-sm" id="exCollapsingNavbar">
                        <li class="nav-item nav-btn"><a class="nav-link btn btn-primary btn-lg btn" href="{{url('/')}}">VOLVER</a></li>
                    </ul>
                    <button hidden="" class="navbar-toggler navbar-close" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar">
                        <div class="close-icon"></div>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</section>
<br/><br/><br/><br/>
<section class="mbr-section msg-box3" id="msg-box3-c" data-rv-view="8" style="background-color: rgb(233, 235, 239); padding-top: 50px; padding-bottom: 50px;">
    <br/>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-offset-3" style="background-color:#c9c9d6; border-radius:10px">
                
                <form name="frm-ingreso" method="POST" action="">
                    {{ csrf_field() }}
                    <br/>
                    <h5 style="color:#323438"><b>Ingrese sus datos</b></h5>
                    <?php
                        if($flag === "true"):
                    ?>
                        <i style="color: red">Este usuario no existe</i>
                    <?php
                        endif;
                    ?>
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
            </div>
        </div>
    </div>
</section>
<section class="mbr-section footer1" id="footer1-28" data-rv-view="23" style="background-color: rgb(31, 128, 104); padding-top: 40px; padding-bottom: 40px;">
    <div class="container">
    <div class="row">
        <div class="mbr-cards-col col-xs-12 col-sm-6 col-lg-6">
            <div class="card">
                <div class="card-box col-xs-9 col-lg-12">
                    
                    <p class="card-text mbr-section-text text-2"><a href="{{url('/')}}" class="text-white">Inicio.</a><br><a href="index.html#header1-2a" class="text-white">Beneficios.</a><br><a href="index.html#features3-j" class="text-white">Modulos</a><br><a href="index.html#subscribe1-26" class="text-white">Contacto</a><br></p>
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <div class="mbr-cards-col col-xs-12 col-sm-6 col-lg-6">
            <div class="card">
                <div class="card-box col-xs-9 col-lg-12">
                    
                    <p class="card-text mbr-section-text text-2"></p>
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
  <script src="assets/web/assets/jquery/jquery.min.js"></script>
  <script src="assets/tether/tether.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/smooth-scroll/smooth-scroll.js"></script>
  <script src="assets/dropdown/js/script.min.js"></script>
  <script src="assets/touch-swipe/jquery.touch-swipe.min.js"></script>
  <script src="assets/jarallax/jarallax.js"></script>
  <script src="assets/cookies-alert-plugin/cookies-alert-core.js"></script>
  <script src="assets/cookies-alert-plugin/cookies-alert-script.js"></script>
  <script src="assets/theme/js/script.js"></script>
  <script src="assets/formoid/formoid.min.js"></script>
  <script>
      $("#current-year").text((new Date()).getFullYear());
  </script>
 
</body>
</html>