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
       Indicadores Actividades
    @endsection
    @section('buttons-submenus')
        <a class="button small" data-open="modal-crear-actividad-obligatoria">Crear Actividad Obligatoria</a>
        <a class="button small" data-open="modal-crear-actividad-sugerida">Crear Actividad Sugerida</a>
        <a class="button small warning" href="{{route('indicadores-actividades')}}">Indicadores Actividades</a>
        <a class="button small alert" href="{{route('actividades')}}">Calendario Actividades</a>
    @endsection
    
    <div class="row">
        <div class="columns small-12 medium-6 text-center" >
            <canvas id="chart1" style="height: 350px"></canvas>
        </div>
        <div class="columns small-12 medium-6 text-center">
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
    <form method="POST" action="{{route('data-indicadores-actividades')}}" accept-charset="UTF-8" id="form-indicadores-actividades">
        {{ csrf_field() }}
    </form>
    
    <script>
       $(document).ready(function(){
           var form = $('#form-indicadores-actividades');
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
                            text: "Indicador % Cumplimiento Mensual Actividades"
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
                
                
                var ctx2 = document.getElementById("chart2").getContext("2d");
                window.myBar = new Chart(ctx2, {
                    type: 'bar',
                    data:{
                        labels: ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP","OCT","NOV","DIC"],
                        datasets: result.dataPve
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
                            text: "Indicador % Cumplimiento Mensual Actividades PVE"
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
                
                var ctx3 = document.getElementById("chart3").getContext("2d");
                window.myBar = new Chart(ctx3, {
                    type: 'bar',
                    data:{
                        labels: ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP","OCT","NOV","DIC"],
                        datasets: result.dataPgrp
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
                            text: "Indicador % Cumplimiento Mensual Actividades PGRP"
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
            }    
            });
                
       });
    </script>
    @include('analissta.Actividades.modalCrearActividadObligatoria')
    @include('analissta.Actividades.modalCrearActividadSugerida')
@endsection    
