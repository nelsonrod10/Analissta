@extends('inicio.layouts.app')

@section('content')
    @section('items-menu')
        <ul class="nav-dropdown collapse pull-xs-right nav navbar-nav show-buttons navbar-toggleable-sm" id="exCollapsingNavbar">
            <!--<li class="nav-item"><a class="nav-link link" href="{{url('/')}}">INICIO</a></li>-->
            <li class="nav-item"><a class="nav-link link" href="#header1-2a">BENEFICIOS</a></li>
            <li class="nav-item">
                <a class="nav-link link" href="#features3-j">CARACTERISTICAS Y PRECIOS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link link" href="{{url('videos')}}">VIDEOS</a>
            </li>
            <li class="nav-item"><a class="nav-link link" href="http://demo.analissta.com">DEMO</a></li>
            <li class="nav-item"><a class="nav-link link" href="{{url('comunidad')}}">COMUNIDAD</a></li>
            <li class="nav-item"><a class="nav-link link" href="#form1-2c">CONTACTO</a></li>
            <li class="nav-item nav-btn"><a class="nav-link btn btn-primary btn-lg btn" href="{{ route('login')}}">INGRESAR</a></li>

        </ul>

    @endsection
    <section class="mbr-section mbr-section-hero mbr-section-full header8 mbr-parallax-background mbr-after-navbar" id="header8-29" data-rv-view="2" style="background-image: url(assets/images/fondopaginicio2-1280x720.png);">
        <div class="mbr-overlay" style="opacity: 0.3; background-color: rgb(45, 45, 45);">
        </div>
            <div class="mbr-table-cell">
                <div class="container">
                        <div id="title">
                            <div class="mbr-figure image-size" style="width: 63%;"><img src="assets/images/analisstav3.3-3-1164x245.png"></div>
                            <div class="mbr-table-cell mbr-left-padding-md-up col-md-6 text-xs-center text-md-left">
                                <h3 class="mbr-section-title display-2" style="color: rgb(31, 128, 104);"><strong>Ágil, Confiable, Seguro...</strong></h3>
                                <div class="mbr-section-subtitle sub-2">
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        <div class="mbr-arrow mbr-arrow-floating" aria-hidden="true"><a href="#header1-2a"><i class="mbri-down"></i></a></div>
    </section>
    <section class="mbr-section content5" id="content7-2d" data-rv-view="36" style="background-image: url(assets/images/comunidad-2.jpg); padding-top: 80px; padding-bottom: 60px;">

        <div class="mbr-overlay" style="opacity: 0.6; background-color: rgb(45, 45, 45);"></div>

            <div class="container">
                <div class="row heading">
                    <div class="col-md-10 text-xs-center col-md-offset-1">
                        <h1 class="mbr-section-title display-3 heading-title">Comunidad de profesionales SST</h1>
                        <p class="mbr-section-subtitle text-1 heading-text">Eres un profesional independiente, tienes o trabajas para una empresa de asesoría o consultoría.... y prestas servicios relacionados con Sistemas de Gestión <br>¿Necesitas una opción para conocer y contactar más clientes?, Nuestra comunidad te esta esperando</p>
                        <div class='text-center'>
                            <a href='{{url('comunidad')}}' class="btn btn-success">Conocer más</a>
                        </div>
                    </div>
                </div>
            </div>

    </section>
    <section class="mbr-section mbr-section-hero mbr-section-full header1" id="header1-2a" data-rv-view="5" style="background-color: rgb(255, 255, 253);">
        <div class="mbr-table-cell">
            <div class="container">
                <div class="row heading">
                    <div class="col-md-10 col-md-offset-1 text-xs-center">
                        <h1 class="mbr-section-title display-1 heading-title">Beneficios, que solo analiSSTa puede ofrecer</h1>
                        <p class="mbr-section-subtitle sub-2 heading-text">Administre su sistema SST con toda tranquilidad y confianza.<br>Adios a las largas horas programando y actualizando su sistema, analiSSTa lo hace por usted&nbsp;</p>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="col-xs-12 col-lg-10 col-lg-offset-1">
                    <div class="row">
                        <div class="mbr-cards-col col-xs-12 col-lg-4">
                            <div class="card row">
                                <div class="card-img col-xs-3"><span class="mbrib-rocket mbr-iconfont" style="font-size: 40px; color: rgb(70, 69, 66);"></span></div>
                                <div class="card-box col-xs-9">
                                    <h4 class="card-title mbr-section-subtitle sub-2">Agilidad</h4>
                                    <p class="card-text mbr-section-text text-1">analiSSTa es tan <strong>fácil de usar</strong> que podra dedicar su tiempo a <strong>optimizar</strong> realmente su sistema.<br>Las herramientas que analiSSTa ofrece automatizarán su sistema de tal forma que podrá dedicar su <strong>tiempo</strong> a lo verdaderamente importante.</p>
                                </div>
                            </div>
                        </div>
                        <div class="mbr-cards-col col-xs-12 col-lg-4">
                            <div class="card row">
                                <div class="card-img col-xs-3"><span class="mbrib-setting mbr-iconfont" style="font-size: 40px; color: rgb(70, 69, 66);"></span></div>
                                <div class="card-box col-xs-9">
                                    <h4 class="card-title mbr-section-subtitle sub-2">Confiabilidad</h4>
                                    <p class="card-text mbr-section-text text-1">Gracias a analiSSTa toda la <strong>información</strong> de su sistema esta en constante <strong>interacción</strong>, garantizando total <strong>confiabilidad</strong> en los datos, indicadores, cronogramas, planes de trabajo, medidas de intervención etc.</p>
                                </div>
                            </div>
                        </div>
                        <div class="mbr-cards-col col-xs-12 col-lg-4">
                            <div class="card row">
                                <div class="card-img col-xs-3"><span class="mbrib-key mbr-iconfont" style="font-size: 40px; color: rgb(70, 69, 66);"></span></div>
                                <div class="card-box col-xs-9">
                                    <h4 class="card-title mbr-section-subtitle sub-2">Seguridad</h4>
                                    <p class="card-text mbr-section-text text-1">Saber que toda su información va a estar en la nube, puede darle la confianza de que va a estar protegida de cualquier manipulación del sistema.<br>analiSSTa conoce la importancia de la información por eso la conserva cuidadosamente</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="mbr-section msg-box3" id="msg-box3-c" data-rv-view="8" style="background-color: rgb(233, 235, 239); padding-top: 50px; padding-bottom: 50px;">
        <div class="container">
            <div class="row">
                <div class="wraper" style="text-align: right; background: linear-gradient(to right top, rgb(255, 52, 36), rgb(255, 229, 65)) rgb(255, 52, 36);">
                  <!-- inverse -->
                  <div class="mbr-table-cell col-md-6 content">
                      <h1 class="mbr-section-title display-2 text-center">Amigable</h1>
                      <div class="icons-block">
                          <div class="container">
                          <div class="row">
                            <div class="col-md-4 icons-item col-md-6">
                              <div class="card-img" style="border-color: rgb(255, 255, 255); border-width: 2px;"><span class="mbri-edit2 mbr-iconfont mbr-iconfont-features4" style="color: rgb(255, 255, 255);"></span></div>
                              <p class="round-text text-1 text-center">anliSSTa es completamente modular, por lo tanto no necesita ninguna curva de aprendizaje, ¿Tiene redes sociales, gmail, hotmail etc?.... entonces usa analiSSTa</p>
                            </div>
                            <div class="col-md-4 icons-item col-md-6" style="display: block;">
                              <div class="card-img" style="border-color: rgb(255, 255, 255); border-width: 2px;"><span class="mbri-devices mbr-iconfont mbr-iconfont-features4" style="color: rgb(255, 255, 255);"></span></div>
                              <p class="round-text text-1 text-center">Todo en uno. Olvide todos los archivos en los que lleva actualmente su sistema, toda la información en un solo lugar e interactuando para entregarle resultados.</p>
                            </div>
                            <div class="col-md-4 icons-item" style="display: none;">
                              <div class="card-img" style="border-color: rgb(255, 255, 255); border-width: 2px;"><a href="https://mobirise.com" class="mbri-timer mbr-iconfont mbr-iconfont-features4" style="color: rgb(255, 255, 255); font-size: 40px;"></a></div>
                              <p class="round-text text-1 text-center">Publish your website to a local drive, FTP or host on Amazon S3, Github.</p>
                            </div>
                          </div>
                          </div>
                      </div>
                  </div>
                  <div class="mbr-table-cell mbr-figure col-md-6" style="width: 42%;"><img src="assets/images/mbr-1631x1080.jpg"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="mbr-section mbr-section-hero features3" id="features3-j" data-rv-view="11" style="background-color: rgb(255, 255, 253); padding-top: 40px; padding-bottom: 40px;">
        <div class="container">
            <div class="row heading">
                <div class="col-md-10 col-md-offset-1 text-xs-center">
                    <h1 class="mbr-section-title display-3 heading-title">MODULOS</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="col-xs-12 col-lg-10 col-lg-offset-1 features">
                <div class="row">
                    <div class="mbr-cards-col col-xs-12 col-md-6">
                        <div class="card">
                            <div class="card-box col-xs-9">
                                <div class="card-box-inner">
                                    <h4 class="card-title mbr-section-subtitle sub-2">Valoración</h4>
                                </div>
                                <p class="card-text mbr-section-text text-1">Cree su empresa con todos los datos necesarios para realizar una valoración de su sistema: Centros de trabajo, base de datos de empleados, procesos, actividades, valoración del riesgo. De lo demás se encarga <strong>analiSSTa</strong></p>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="mbr-cards-col col-xs-12 col-md-6">
                        <div class="card">
                            <div class="card-box col-xs-9">
                                <div class="card-box-inner">
                                    <h4 class="card-title mbr-section-subtitle sub-2">Planes Anuales (Actividades, Capacitaciones e Inspecciones)</h4>
                                </div>
                                <p class="card-text mbr-section-text text-1">Finalizada la valoración analiSSTa creara <strong>automáticamente</strong> los planes anuales de Actividades, Capacitaciones e Inspecciones, lo único que debe hacer es programarlas y <strong>listo!!!</strong></p>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mbr-cards-col col-xs-12 col-md-6">
                        <div class="card">
                            <div class="card-box col-xs-9">
                                <div class="card-box-inner">
                                    <h4 class="card-title mbr-section-subtitle sub-2">Hallazgos</h4>
                                </div>

                                <p class="card-text mbr-section-text text-1">Configure fácilmente hallazgos que se generen durante la administración del sistema, todos los hallazgos estarán relacionados <strong>automáticamente</strong> con los planes de acción que se creen, eso significa <strong>agilidad y confiabilidad.</strong></p>

                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="mbr-cards-col col-xs-12 col-md-6">
                        <div class="card">
                            <div class="card-box col-xs-9">
                                <div class="card-box-inner">
                                    <h4 class="card-title mbr-section-subtitle sub-2">Accidentalidad y Ausentismo</h4>
                                </div>
                                <p class="card-text mbr-section-text text-1">Una de las variables más críticas en todo sistema, pero gracias a <strong>analiSSTa&nbsp;</strong>este proceso será <strong>sencillo, confiable y seguro </strong>ya que el reporte y seguimiento es completamente efectivo lo que redundará en la productividad de su empresa.</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mbr-cards-col col-xs-12 col-md-6">
                        <div class="card">
                            <div class="card-box col-xs-9">
                                <div class="card-box-inner">
                                    <h4 class="card-title mbr-section-subtitle sub-2">Gestión de Riesgo y Vigilancia Epidemiologica</h4>
                                </div>
                                <p class="card-text mbr-section-text text-1">analiSSTa generá estos planes sin que usted tenga que hacer nada (no exageramos), basándose en los datos obtenidos en la valoración.</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="mbr-cards-col col-xs-12 col-md-6">
                        <div class="card">
                            <div class="card-box col-xs-9">
                                <div class="card-box-inner">
                                    <h4 class="card-title mbr-section-subtitle sub-2">Indicadores y Presupuesto</h4>
                                </div>
                                <p class="card-text mbr-section-text text-1">Además de hacer la configuración y administración del sistema mucho más ágil, analiSSTa también sabe lo importante que es para la empresa tener mediciones exactas que ayuden a tomar la <strong>decisiones correctas. </strong>Los indicadores y el presupuesto están a su disposición para lograr estas metas.</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mbr-section mbr-section-hero features3" id="features3-k" data-rv-view="11" style="background-color: rgb(255, 255, 253); padding-top: 10px; padding-bottom: 20px;">
        <div class="container">
            <div class="row heading">
                <div class="col-md-10 col-md-offset-1 text-xs-center">
                    <h1 class="mbr-section-title display-3 heading-title">CARACTERISTICAS DETALLADAS</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b> Soporte virtual (técnico y SST) 24/7</b></div>
                <div class="col-xs-12 col-md-9"> Podrás realizar consultas asociadas al uso de analiSSTA a cualquier hora</div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b> Contexto organizacional </b></div>
                <div class="col-xs-12 col-md-9">  Permite registrar la información de tu organización e identificar necesidades internas y externas en SST </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b> Perfil sociodemográfico </b></div>
                <div class="col-xs-12 col-md-9">   Con la información de tus colaboradores podrás generar el perfil sociodemográfico e identificar necesidades SST </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b> Distribución por centros de trabajo </b></div>
                <div class="col-xs-12 col-md-9"> Gestiona tu sistema de gestión SST por cada centro de trabajo o de manera global </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b> Usuarios según perfil </b></div>
                <div class="col-xs-12 col-md-9"> Todos los trabajadores tendrán la oportunidad de fortalecer su participación con usuarios y permisos teniendo en cuenta su nivel organizacional </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b>IPVCR interactivo </b></div>
                <div class="col-xs-12 col-md-9"> IPVCR = Identifica Peligros, Valora y Controla Riesgos, y extrae automáticamente:
                    <ul>
                        <li>Plan de trabajo</li>
                        <li>Plan de capacitaciones</li>
                        <li>Plan de inspecciones</li>
                        <li>Programas de gestión de riesto prioritario PGRP</li>
                        <li>Programas de vigilancia epidemiológica PVE</li>
                    </ul>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b>  Actividades, Capacitaciones, Inspecciones</b></div>
                <div class="col-xs-12 col-md-9">Para todas las Actividades, Capacitaciones e Inspecciones, sólo deberás programar y asignar responsables para la ejecución, luego revisa y mide tus resultados </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b> Programas de gestión de riesgo prioritario - PGRP- </b></div>
                <div class="col-xs-12 col-md-9"> Derivado de la IPVCR el software reconoce los riesgos prioritarios con el potencial de generar ACCIDENTES y  crea automáticamente los PGRP, solo configúralos y revisa tus resultados </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b> Programas de vigilancia epidemiológica  -PVE- </b></div>
                <div class="col-xs-12 col-md-9"> Derivado de la IPVCR el software reconoce los riesgos prioritarios con el potencial de generar ENFERMEDADES y  crea automáticamente los PVE, solo configúralos y revisa tus resultados </div>
            </div>

            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b>Hallazgos </b></div>
                <div class="col-xs-12 col-md-9"> Desde aquí podrás gestionar todos tus hallazgos de diferentes orígenes </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b> Ausentismo </b></div>
                <div class="col-xs-12 col-md-9"> Registra las ausencias de tu organización, analiSSTa calculará automáticamente su costo </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b> Accidentalidad </b></div>
                <div class="col-xs-12 col-md-9"> Registra los accidentes de trabajo, aplica una metodología probada y calcula su costo </div>
            </div>
            <hr/>
            <!--<div class="row">
                <div class="col-xs-12 col-md-3"><b> Proveedores y contratistas </b></div>
                <div class="col-xs-12 col-md-9">  Crea la base de datos de tus proveedores, evalúalos y divulga los resultados </div>
            </div>
            <hr/>-->
            <div class="row">
                <div class="col-xs-12 col-md-3"><b> Requisitos legales SST </b></div>
                <div class="col-xs-12 col-md-9"> Mediante la IPVCR podrás identificar simultáneamente los requisitos legales aplicables </div>
            </div>
            <hr/>

            <!--<div class="row">
                <div class="col-xs-12 col-md-3"><b> Notificaciones </b></div>
                <div class="col-xs-12 col-md-9"> Todos los usuarios del software recibirán extractos de sus responsabilidades y monitoreo del cumplimiento de las actividades que tienen asignadas </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b> Evaluación de desempeño </b></div>
                <div class="col-xs-12 col-md-9"> Teniendo en cuenta la ejecución que tus supervisores registren, evalúa su cumplimiento y el valor que le aportan a tu SG-SST </div>
            </div>
            <hr/>-->
            <div class="row">
                <div class="col-xs-12 col-md-3"><b>Presupuesto SST </b></div>
                <div class="col-xs-12 col-md-9"> Mediante programación de actividades, capacitaciones e inspecciones podrás construir simultáneamente el presupuesto SST</div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b>Indicadores de desempeño </b></div>
                <div class="col-xs-12 col-md-9"> Extrae gráficos de control con información de fácil lectura de tus indicadores de tu SG-SST </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b> Cargue de Información documentada </b></div>
                <div class="col-xs-12 col-md-9"> En los diferentes módulos podrás almacenar los registros que se levanten como resultado del SG-SST</div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-md-3"><b> Información documentada guía </b></div>
                <div class="col-xs-12 col-md-9"> El software tiene información documentada que te servirá de guía para crear programas, procedimientos y formatos.  </div>
            </div>
            <hr/>
        </div>
    </section>

    <section class="mbr-section mbr-section-hero mbr-section-full pricing-table3" id="pricing-table3-17" data-rv-view="14" style="background-color: rgb(233, 235, 239); padding-top: 20px; padding-bottom: 20px;" >
        <div class="mbr-section mbr-section-nopadding mbr-price-table">
            <div class="main-row" style="max-width: 1500px;">
                <div class="col-xs-12">
                    <div class="mbr-plan card text-xs-center">
                        <div class="mbr-plan-header card-block primary" style="background: linear-gradient(45deg, rgb(46, 118, 99), rgb(57, 147, 124), rgb(80, 206, 174)) rgb(57, 147, 124);">
                          <div class="card-title">
                            <h3 class="mbr-section-title display-2">analiSSTa a la medida</h3>
                            <h3 class="mbr-plan-title">Olvidate de pagar paquetes o planes que no se ajustan a tu necesidad.</h3>
                            <br/>
                            <h5 class="mbr-plan-title">analiSSTa ofrece un sistema de pago que realmente te va a servir, simplemente digita el número total de empleados de tu empresa</h5>
                          </div>

                          <div class="card-title">
                              <br/>
                              <div>
                                  <input id="empleados" type="number" min="1" max="1000" step="1" class="text-xs-center" placeholder="¿Cuantos empleados tiene tu empresa?" style="width: 40%;height: 40px;border-radius: 10px;color:black"/>
                              </div>
                              <div>
                                  <b id="error-empleados" style="color:#ff3333"></b>
                              </div>
                            <br/>
                          </div>

                          <span class="bottom_line"></span>
                          <div class="card-text info-precios hidden">
                              <div class="mbr-price">
                                  <span class="mbr-price-figure">$ <span id="precio-mensual"></span> COP</span>
                                <small class="mbr-price-term">Mensual</small>
                              </div>
                              <small class="mbr-plan-price-desc">Primer mes completamente gratis!!</small>
                          </div>
                        </div>
                        <div id="div-calculos-precios" class="mbr-plan-body info-precios hidden" hidden="">
                            <div class="mbr-plan-list" style="font-size:20px">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><b>Pero eso no es todo, aún tenémos más para ti</b></li>
                                    <li class="list-group-item">Si decides adquirir nuestro plan anual, te ofrecemos un descuento del <span id="descuento" style="font-size: 28px;font-weight: bold"></span> <span style="font-size: 28px;font-weight: bold">%</span>, paga: </li>
                                </ul>
                                <div class="card-text">
                                    <div class="mbr-price">
                                        <span class="mbr-price-figure">$ <span id="precio-anual"></span> COP</span>
                                      <small class="mbr-price-term">Anual</small>
                                    </div>
                                    <small class="mbr-plan-price-desc">Primer mes completamente gratis!!</small>
                                </div>
                                <div class="card-text">
                                    <small class="mbr-plan-price-desc">**Para cualquier pago que escojas, si lo deseas te ofrecemos una inducción presencial de 8 horas por $250.000 COP <b>(Opcional)</b>**</small>
                                </div>
                            </div>
                            <div class="mbr-plan-btn"><a href="#form1-2c" class="btn btn-sm btn-primary">ME INTERESA</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mbr-section content5" id="content7-2d" data-rv-view="36" style="background-image: url(assets/images/mbr-2-1620x1080.jpg); padding-top: 80px; padding-bottom: 60px;">

        <div class="mbr-overlay" style="opacity: 0.3; background-color: rgb(45, 45, 45);"></div>

            <div class="container">
                <div class="row heading">
                    <div class="col-md-10 text-xs-center col-md-offset-1">

                        <h1 class="mbr-section-title display-3 heading-title">Estamos de lazamiento...</h1>
                        <p class="mbr-section-subtitle text-1 heading-text">Únase a nuestra oferta de lanzamiento.<br>Diligencie el siguiente formulario y reciba el primer meses completamente GRATIS !!!<br></p>

                    </div>
                </div>
            </div>

    </section>

    <section class="mbr-section mbr-section-hero form1" id="form1-2c" data-rv-view="26" style="background-color: rgb(138, 205, 188); padding-top: 40px; padding-bottom: 40px;">
        <div class="container">
            <div class="row heading">
                <div class="col-md-12 text-xs-center">

                    <h1 class="mbr-section-title display-3 heading-title">Únase a esta revolución</h1>


                </div>
            </div>
        </div>
        <div class="container">
            <div class="col-xs-12 form">

                <div class="row">
                    <div class="col-xs-12 col-md-8 col-md-offset-2">
                        <h5 class="form-title mbr-section-subtitle text-1">Ingrese los siguientes datos, y haga parte de nuestra oferta de lanzamiento<br>primer meses completamente GRATIS</h5>
                    </div>
                </div>

                <div class="row" data-form-type="formoid">
                    <div data-form-alert="true">
                        <div hidden="" data-form-alert-success="true" class="alert alert-form alert-success text-xs-center">Gracias por su interés....Pronto estaremos en contacto</div>
                    </div>
                    <form action="https://mobirise.com/" method="post" data-form-title="Únase a esta revolución">
                        <input type="hidden" value="XY1blUNdNu7j2j1ZuSpXq81yoa5jlSpt4O29DQiFB7qAoOXUgjx8PllqdSD6jtmIL4R0E9uHI2mLZoZP23peH4em4X697i0b5u0ccPlBaJAwxVUdEh0IryKhqzSJ0U+O" data-form-email="true">
                        <div class="col-xs-12 col-md-8 col-md-offset-2 form-wrap">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="form-control-label">Email*</label>
                                    <input type="email" class="form-control" name="email" required="" data-form-field="Email" placeholder="Email*">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <label class="form-control-label">si tiene alguna duda escribala en este espacion. Con gusto la resolveremos</label>
                                <textarea class="form-control" name="message" rows="7" data-form-field="Message" placeholder="si tiene alguna duda escribala en este espacion. Con gusto la resolveremos"></textarea>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-separator"></div>
                        <div class="col-xs-12 col-md-8 col-md-offset-2 form-button-wrap">
                            <div class="text-xs-center text-md-right"><button type="submit" class="btn btn-lg btn-primary">Enviar</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @section('items-footer')
        <p class="card-text mbr-section-text text-2"><a href="{{url('/')}}" class="text-white">Inicio.</a><br><a href="#header1-2a" class="text-white">Beneficios.</a><br><a href="#features3-j" class="text-white">Modulos</a><br><a href="#features3-k" class="text-white">Características y Precios</a><br><a href="{{url('videos')}}" class="text-white">Instructivos</a><br><a href="{{url('comunidad')}}" class="text-white">Comunidad</a><br><a href="#form1-2c" class="text-white">Contacto</a><br></p>
    @endsection
    <form method="POST" action="{{url('/calcular-precios/:empleados')}}" accept-charset="UTF-8" id="form-calcular-precios">
        {{ csrf_field() }}
    </form>

@endsection
@section('scripts')
<script>
    $(document).ready(function(){

        $("#empleados").on("change",function(e){
            $("#div-calculos-precios").attr("hidden","");
            if($(this).val() === "" || $(this).val() === "0"){
                  $(".info-precios").each(function(){
                        $(this).addClass("hidden");
                  });
                  $("#error-empleados").html("El valor no puede ser 0 o nulo");
                  e.preventDefaul();
              }
            calcularPrecio($(this).val());
        });
        $("#empleados").on("keyup",function(e){
            $("#div-calculos-precios").attr("hidden","");
            if($(this).val() === "" || $(this).val() === "0"){
                  $(".info-precios").each(function(){
                        $(this).addClass("hidden");
                  });
                  $("#error-empleados").html("El valor no puede ser 0 o nulo");
                  e.preventDefaul();
              }
            calcularPrecio($(this).val());
        });
    });
    $("#current-year").text((new Date()).getFullYear());

    function calcularPrecio(empleados){
      $(".info-precios").each(function(){
            $("#error-empleados").html("");
            $(this).removeClass("hidden");
      });

      var form = $('#form-calcular-precios');
      var url = form.attr('action').replace(':empleados',empleados);
      var data = form.serialize();
      $.post(url,data,function(result){
          $("#div-calculos-precios").removeAttr("hidden");
          $("#precio-mensual").html(result.calculo.mensualidad);
          $("#precio-anual").html(result.calculo.anualidad);
          $("#descuento").html(result.calculo.descuento);
      });

      /*$.ajax({
            type:"POST",
            url:"index.php?flagGET=calcularPrecios",
            dataType: 'json',
            data: {numEmpleados:empleados},

            success: function(data){
                $("#precio-mensual").html(data.precioMensual);
                $("#precio-anual").html(data.precioAnual);
                $("#descuento").html(data.descuento);
            }
       });*/
    }
</script>
@endsection
