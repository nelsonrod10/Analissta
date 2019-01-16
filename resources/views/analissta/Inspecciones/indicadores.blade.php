@extends('analissta.layouts.app2')
@section('sistem-menu')
<style>
    .titulo-origenes{
        font-size: 16px;
        font-weight: bold;
        color: #3c3737;
    }
    .a-hallazgo{
        width: auto;
        height: auto;
        max-width: 80%;
        max-height: 25px;
        overflow: hidden;

    }
    .a-hallazgo a{
        text-decoration: underline;
    }
</style>
    @include('analissta.layouts.appTopMenu')
    @include('analissta.layouts.enlacesChartjs')
@endsection

@section('content')
    @section('titulo-encabezado')
        Indicadores Inspecciones
    @endsection
    @section('buttons-submenus')
        <a class="button small" data-open="modal-crear-inspeccion-obligatoria">Crear Inspección Obligatoria</a>
        <a class="button small" data-open="modal-crear-inspeccion-sugerida">Crear Inspección Sugerida</a>
        <a class="button small warning" href="{{route('indicadores-inspecciones')}}">Indicadores Inspecciones</a>
        <a class="button small alert" href="{{route('inspecciones')}}">Calendario Inspecciones</a>
    @endsection
    <div class="row">
        <div class="columns small-12 medium-6 text-center" >
            <canvas id="chart1" style="height: 350px"></canvas>
        </div>
        <div class="columns small-12 medium-6 text-center">
            <div  class=" columns small-12 small-centered text-center" style="background: red;color:white">
                <small><i>Los meses en que se muestran los hallazgos, corresponden al mes de la <strong>Inspección</strong></i></small>
            </div>
            <canvas id="chart2" style="height: 350px"></canvas>
        </div>
    </div>    
    <br/><br/>
    <div class="row">    
        <div class="columns small-12 medium-6 text-center">
            <canvas id="chart3" style="height: 350px"></canvas>
        </div>
        <div class="columns small-12 medium-6 text-center">
            <canvas id="chart4" style="height: 350px"></canvas>
        </div>
    </div>
    <br/><br/>
    <div class="row">
        <div class="columns small-12 medium-6 text-center">
            <canvas id="chart5" style="height: 350px"></canvas>
            <canvas id="chart6" style="height: 350px"></canvas>
        </div>
    </div>
    <form method="POST" action="{{route('data-indicadores-inspecciones')}}" accept-charset="UTF-8" id="form-indicadores-inspecciones">
        {{ csrf_field() }}
    </form>
    <script>
       $(document).ready(function(){
           var form = $('#form-indicadores-inspecciones');
           $.ajax({
            type:"post",
            url:form.attr('action'),
            dataType: 'json',
            data: form.serialize(),

            success: function(result){
                console.log(result);
                var ctx1 = document.getElementById("chart1").getContext("2d");
                window.myBar = new Chart(ctx1, {
                    type: 'bar',
                    data:{
                        labels: ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP","OCT","NOV","DIC"],
                        datasets: [
                        {
                            label: '% Cumplimiento Mensual',
                            backgroundColor: result.colorCumplimiento,
                            borderColor: result.colorCumplimiento,
                            borderWidth: 1,
                            data: result.cumplimiento
                        }
                        ]
                    },
                    options: {
                        responsive: true,
                        legend: {
                            position: 'bottom',
                            display:false
                        },
                        plugins: {
                            datalabels: {
                                color: 'white',
                                display: true
                            }
                        },
                        title: {
                            display: true,
                            text: "Indicador % Cumplimiento Mensual Inspecciones"
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true

                                }
                            }]
                        }

                    }
                });
                
    /*************ACA VA LA GRAFICA DE LOS HALLAZGOS CERRADOS VS LOS ENCONTRADOS***************/
                /*var ctx2 = document.getElementById("chart2").getContext("2d");
                window.myBar = new Chart(ctx2, {
                    type: 'bar',
                    data:{
                        labels: ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP","OCT","NOV","DIC"],
                        datasets: 
                    },
                    options: {
                        responsive: true,
                        legend: {
                            position: 'bottom',
                            display:true
                        },
                        plugins: {
                            datalabels: {
                                color: 'white',
                                display: true
                            }
                        },
                        title: {
                            display: true,
                            text: "# Hallazgos encontrados por inspecciones VS # Hallazgos cerrados por Inspecciones(Mensual)"
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true

                                }
                            }]
                        }

                    }
                });*/
                
                
            }    
            });
                
       });
    </script>
    @include('analissta.Inspecciones.modalCrearInspeccionObligatoria')
    @include('analissta.Inspecciones.modalCrearInspeccionSugerida')
@endsection    
