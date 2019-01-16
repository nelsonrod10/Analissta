@extends('analissta.layouts.appProgramarMedida')
<?php
    use App\Http\Controllers\helpers;
    use App\Accidentes\Accidente;
    use App\Accidentes\AccidentesCosto;
    
    date_default_timezone_set('America/Bogota');
    $objFechaActual = helpers::getDateNow();
    $fechaActual = $objFechaActual->format("Y-m-d");
    //$xml_origenes = simplexml_load_file(base_path("archivosXML/Accidentes/xml_Origenes.xml"));
    //$origenes = $xml_origenes->xpath("//origenes/origen[@id != 2]");
    
    if(isset($idAccidente)){
        $accidenteBD = Accidente::find($idAccidente);
        $costosBD = AccidentesCosto::where('sistema_id',$sistema->id)
                ->where('accidente_id',$idAccidente)
                ->get();
    }
?>
@section('content')
    @section('titulo-encabezado')
        Reportar Nuevo Accidente
    @endsection
    <style>
            .titulo-origenes{
                font-size: 16px;
                font-weight: bold;
                color: #3c3737;
            }
            .div-descripcion{
                width: auto;
                height: auto;
                max-width: 100%;
                max-height: 25px;
                overflow: hidden;
                
            }
            .div-descripcion a{
                text-decoration: underline;
            }
            .warning-2{
                background: #f29c13;
                color:white;
            }
            .info-costos{
                font-size: 12px;
                background:#ccffcc;    
            }
        </style>
    <div class="row text-center">
        <div class="columns small-12 medium-10 small-centered label secondary">
            <h6><b>COSTOS ACCIDENTE</b></h6>
        </div>
    </div>
    <br/>
    @include('analissta.Asesores.crearEmpresa.errors')
    <div class="row">
        <div class="columns small-12 medium-10 small-centered">
            <form method="post" name="datosGeneralesAccidente" action="{{route('crear-costos-accidente',["id"=>$idAccidente])}}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="columns small-12 medium-3">
                        <b class="middle">Costos Directos: </b>
                        <div class="info-costos"><i>Valor en COP del reporte a ARL</i></div>
                    </div>
                    <div class="columns small-12 medium-3 end">
                        <input class="valor-costo" type="text" name="directos" required="true" placeholder="Valor en pesos COP"  value="<?php echo (isset($costosBD[0]->costos) && $costosBD[0]->costos !== 0)?$costosBD[0]->costos:0?>"/>
                    </div>
                    <div class="columns small-12 medium-3">
                        <b class="middle">Seguimiento: </b>
                        <div class="info-costos"><i>Tiempo de seguimiento al evento. (Seleccione el valor más aproximado)</i></div>
                    </div>
                    <div class="columns small-12 medium-3 end">
                        <select name="seguimiento" class="valor-costo">
                            <option value="0">$0</option>
                            <option value="1000000" <?php echo (isset($costosBD[0]->seguimiento) && $costosBD[0]->seguimiento == 1000000)?"selected":""?>>$1.000.000</option>
                            <option value="3000000" <?php echo (isset($costosBD[0]->seguimiento) && $costosBD[0]->seguimiento == 3000000)?"selected":""?>>$3.000.000</option>
                            <option value="7000000" <?php echo (isset($costosBD[0]->seguimiento) && $costosBD[0]->seguimiento == 7000000)?"selected":""?>>$7.000.000</option>
                            <option value="10000000" <?php echo (isset($costosBD[0]->seguimiento) && $costosBD[0]->seguimiento == 10000000)?"selected":""?>>$10.000.000</option>
                            <option value="20000000" <?php echo (isset($costosBD[0]->seguimiento) && $costosBD[0]->seguimiento == 20000000)?"selected":""?>>$20.000.000</option>
                            <option value="50000000" <?php echo (isset($costosBD[0]->seguimiento) && $costosBD[0]->seguimiento == 50000000)?"selected":""?>>$50.000.000</option>
                            <option value="100000000" <?php echo (isset($costosBD[0]->seguimiento) && $costosBD[0]->seguimiento == 100000000)?"selected":""?>>$100.000.000</option>
                            <option value="500000000" <?php echo (isset($costosBD[0]->seguimiento) && $costosBD[0]->seguimiento == 500000000)?"selected":""?>>$500.000.000</option>
                            <option value="1000000000" <?php echo (isset($costosBD[0]->seguimiento) && $costosBD[0]->seguimiento == 1000000000)?"selected":""?>>$1'000.000.000</option>
                            <option value="1500000000" <?php echo (isset($costosBD[0]->seguimiento) && $costosBD[0]->seguimiento == 1500000000)?"selected":""?>>$1'500.000.000</option>
                        </select>
                    </div>
                </div>
                <br/>
                <div class="row">    
                    <div class="columns small-12 medium-3">
                        <b class="middle">Persona de relevo/reemplazo: </b>
                        <div class="info-costos"><i>(Seleccione el valor más aproximado)</i></div>
                    </div>
                    <div class="columns small-12 medium-3 end">
                        <select name="relevo" class="valor-costo">
                            <option value="0">$0</option>
                            <option value="1000000" <?php echo (isset($costosBD[0]->persona) && $costosBD[0]->persona == 1000000)?"selected":""?>>$1.000.000</option>
                            <option value="3000000" <?php echo (isset($costosBD[0]->persona) && $costosBD[0]->persona == 3000000)?"selected":""?>>$3.000.000</option>
                            <option value="7000000" <?php echo (isset($costosBD[0]->persona) && $costosBD[0]->persona == 7000000)?"selected":""?>>$7.000.000</option>
                            <option value="10000000" <?php echo (isset($costosBD[0]->persona) && $costosBD[0]->persona == 10000000)?"selected":""?>>$10.000.000</option>
                            <option value="20000000" <?php echo (isset($costosBD[0]->persona) && $costosBD[0]->persona == 20000000)?"selected":""?>>$20.000.000</option>
                            <option value="50000000" <?php echo (isset($costosBD[0]->persona) && $costosBD[0]->persona == 50000000)?"selected":""?>>$50.000.000</option>
                            <option value="100000000" <?php echo (isset($costosBD[0]->persona) && $costosBD[0]->persona == 100000000)?"selected":""?>>$100.000.000</option>
                            <option value="500000000" <?php echo (isset($costosBD[0]->persona) && $costosBD[0]->persona == 500000000)?"selected":""?>>$500.000.000</option>
                            <option value="1000000000" <?php echo (isset($costosBD[0]->persona) && $costosBD[0]->persona == 1000000000)?"selected":""?>>$1'000.000.000</option>
                            <option value="1500000000" <?php echo (isset($costosBD[0]->persona) && $costosBD[0]->persona == 1500000000)?"selected":""?>>$1'500.000.000</option>
                        </select>
                    </div>
                    <div class="columns small-12 medium-3">
                        <b class="middle">Imagen Corporativa: </b>
                        <div class="info-costos"><i>Costo del Deterioro de la Imagen Corportariva, (Seleccione el valor más aproximado)</i></div>
                    </div>
                    <div class="columns small-12 medium-3 end">
                        <select name="imagen" class="valor-costo">
                            <option value="0">$0</option>
                            <option value="1000000" <?php echo (isset($costosBD[0]->imagen_corporativa) && $costosBD[0]->imagen_corporativa == 1000000)?"selected":""?>>$1.000.000</option>
                            <option value="3000000" <?php echo (isset($costosBD[0]->imagen_corporativa) && $costosBD[0]->imagen_corporativa == 3000000)?"selected":""?>>$3.000.000</option>
                            <option value="7000000" <?php echo (isset($costosBD[0]->imagen_corporativa) && $costosBD[0]->imagen_corporativa == 7000000)?"selected":""?>>$7.000.000</option>
                            <option value="10000000" <?php echo (isset($costosBD[0]->imagen_corporativa) && $costosBD[0]->imagen_corporativa == 10000000)?"selected":""?>>$10.000.000</option>
                            <option value="20000000" <?php echo (isset($costosBD[0]->imagen_corporativa) && $costosBD[0]->imagen_corporativa == 20000000)?"selected":""?>>$20.000.000</option>
                            <option value="50000000" <?php echo (isset($costosBD[0]->imagen_corporativa) && $costosBD[0]->imagen_corporativa == 50000000)?"selected":""?>>$50.000.000</option>
                            <option value="100000000" <?php echo (isset($costosBD[0]->imagen_corporativa) && $costosBD[0]->imagen_corporativa == 100000000)?"selected":""?>>$100.000.000</option>
                            <option value="500000000" <?php echo (isset($costosBD[0]->imagen_corporativa) && $costosBD[0]->imagen_corporativa == 500000000)?"selected":""?>>$500.000.000</option>
                            <option value="1000000000" <?php echo (isset($costosBD[0]->imagen_corporativa) && $costosBD[0]->imagen_corporativa == 1000000000)?"selected":""?>>$1'000.000.000</option>
                            <option value="1500000000" <?php echo (isset($costosBD[0]->imagen_corporativa) && $costosBD[0]->imagen_corporativa == 1500000000)?"selected":""?>>$1'500.000.000</option>
                        </select>
                    </div>
                </div>    
                <br/>
                <div class="row">
                    <div class="columns small-12 medium-3">
                        <b class="middle">Parada de Operación: </b>
                        <div class="info-costos"><i>Lucro cesante, (Seleccione el valor más aproximado)</i></div>
                    </div>
                    <div class="columns small-12 medium-3 end">
                        <select name="parada" class="valor-costo">
                            <option value="0">$0</option>
                            <option value="1000000" <?php echo (isset($costosBD[0]->operacion) && $costosBD[0]->operacion == 1000000)?"selected":""?>>$1.000.000</option>
                            <option value="3000000" <?php echo (isset($costosBD[0]->operacion) && $costosBD[0]->operacion == 3000000)?"selected":""?>>$3.000.000</option>
                            <option value="7000000" <?php echo (isset($costosBD[0]->operacion) && $costosBD[0]->operacion == 7000000)?"selected":""?>>$7.000.000</option>
                            <option value="10000000" <?php echo (isset($costosBD[0]->operacion) && $costosBD[0]->operacion == 10000000)?"selected":""?>>$10.000.000</option>
                            <option value="20000000" <?php echo (isset($costosBD[0]->operacion) && $costosBD[0]->operacion == 20000000)?"selected":""?>>$20.000.000</option>
                            <option value="50000000" <?php echo (isset($costosBD[0]->operacion) && $costosBD[0]->operacion == 50000000)?"selected":""?>>$50.000.000</option>
                            <option value="100000000" <?php echo (isset($costosBD[0]->operacion) && $costosBD[0]->operacion == 100000000)?"selected":""?>>$100.000.000</option>
                            <option value="500000000" <?php echo (isset($costosBD[0]->operacion) && $costosBD[0]->operacion == 500000000)?"selected":""?>>$500.000.000</option>
                            <option value="1000000000" <?php echo (isset($costosBD[0]->operacion) && $costosBD[0]->operacion == 1000000000)?"selected":""?>>$1'000.000.000</option>
                            <option value="1500000000" <?php echo (isset($costosBD[0]->operacion) && $costosBD[0]->operacion == 1500000000)?"selected":""?>>$1'500.000.000</option>
                        </select>
                    </div>
                    <div class="columns small-12 medium-3">
                        <b class="middle">Legales: </b>
                        <div class="info-costos"><i>Posibles Demandas por el Accidente, (Seleccione el valor más aproximado)</i></div>
                    </div>
                    <div class="columns small-12 medium-3 end">
                        <select name="legal" class="valor-costo">
                            <option value="0">$0</option>
                            <option value="1000000" <?php echo (isset($costosBD[0]->legales) && $costosBD[0]->legales == 1000000)?"selected":""?>>$1.000.000</option>
                            <option value="3000000" <?php echo (isset($costosBD[0]->legales) && $costosBD[0]->legales == 3000000)?"selected":""?>>$3.000.000</option>
                            <option value="7000000" <?php echo (isset($costosBD[0]->legales) && $costosBD[0]->legales == 7000000)?"selected":""?>>$7.000.000</option>
                            <option value="10000000" <?php echo (isset($costosBD[0]->legales) && $costosBD[0]->legales == 10000000)?"selected":""?>>$10.000.000</option>
                            <option value="20000000" <?php echo (isset($costosBD[0]->legales) && $costosBD[0]->legales == 20000000)?"selected":""?>>$20.000.000</option>
                            <option value="50000000" <?php echo (isset($costosBD[0]->legales) && $costosBD[0]->legales == 50000000)?"selected":""?>>$50.000.000</option>
                            <option value="100000000" <?php echo (isset($costosBD[0]->legales) && $costosBD[0]->legales == 100000000)?"selected":""?>>$100.000.000</option>
                            <option value="500000000" <?php echo (isset($costosBD[0]->legales) && $costosBD[0]->legales == 500000000)?"selected":""?>>$500.000.000</option>
                            <option value="1000000000" <?php echo (isset($costosBD[0]->legales) && $costosBD[0]->legales == 1000000000)?"selected":""?>>$1'000.000.000</option>
                            <option value="1500000000" <?php echo (isset($costosBD[0]->legales) && $costosBD[0]->legales == 1500000000)?"selected":""?>>$1'500.000.000</option>
                        </select>
                    </div>
                </div>   
                <br/>
                <div class="row">
                    <div class="columns small-12 medium-3">
                        <b class="middle">Productividad: </b>
                        <div class="info-costos"><i>Tiempo Productivo retrasado o perdido, (Seleccione el valor más aproximado)</i></div>
                    </div>
                    <div class="columns small-12 medium-3 end">
                        <select name="productividad" class="valor-costo">
                            <option value="0">$0</option>
                            <option value="1000000" <?php echo (isset($costosBD[0]->productividad) && $costosBD[0]->productividad == 1000000)?"selected":""?>>$1.000.000</option>
                            <option value="3000000" <?php echo (isset($costosBD[0]->productividad) && $costosBD[0]->productividad == 3000000)?"selected":""?>>$3.000.000</option>
                            <option value="7000000" <?php echo (isset($costosBD[0]->productividad) && $costosBD[0]->productividad == 7000000)?"selected":""?>>$7.000.000</option>
                            <option value="10000000" <?php echo (isset($costosBD[0]->productividad) && $costosBD[0]->productividad == 10000000)?"selected":""?>>$10.000.000</option>
                            <option value="20000000" <?php echo (isset($costosBD[0]->productividad) && $costosBD[0]->productividad == 20000000)?"selected":""?>>$20.000.000</option>
                            <option value="50000000" <?php echo (isset($costosBD[0]->productividad) && $costosBD[0]->productividad == 50000000)?"selected":""?>>$50.000.000</option>
                            <option value="100000000" <?php echo (isset($costosBD[0]->productividad) && $costosBD[0]->productividad == 100000000)?"selected":""?>>$100.000.000</option>
                            <option value="500000000" <?php echo (isset($costosBD[0]->productividad) && $costosBD[0]->productividad == 500000000)?"selected":""?>>$500.000.000</option>
                            <option value="1000000000" <?php echo (isset($costosBD[0]->productividad) && $costosBD[0]->productividad == 1000000000)?"selected":""?>>$1'000.000.000</option>
                            <option value="1500000000" <?php echo (isset($costosBD[0]->productividad) && $costosBD[0]->productividad == 1500000000)?"selected":""?>>$1'500.000.000</option>
                        </select>
                    </div>
                </div>   
                <br/>
                <div class="row columns text-center">
                    <h5><b>Costo Total: $
                            <span id="span-costoTotal">0</span> (COP)</b>
                    </h5>
                </div>
                <div class="row columns text-center">
                    <a class="button small" href="{{ route('causas-basicas-accidente',['id'=>$idAccidente]) }}">Anterior</a>
                    <a class="button small alert" data-open="reveal-cancelar-crear">Cancelar</a>
                    <input type="submit" class="button small success" value="Finalizar"/>
                </div>    
            </form>
        </div>
    </div>
    @include('analissta.Accidentes.crearAccidente.modalCancelar')
    <script>
        $(document).ready(function(){
           $(".valor-costo").on("change",function(e){
               var valorTotal=0;
              $(".valor-costo").each(function(){
                valorTotal += parseFloat($(this).val());
              }); 
              $("#span-costoTotal").text(valorTotal);
           });
        });
    </script>
    
@endsection