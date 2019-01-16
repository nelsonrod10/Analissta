@extends('analissta.layouts.app')

@section('content')
@include('analissta.layouts.encabezadoEmpresaCliente')

<div class="row">
    <div class="columns small-12 text-center">
        <h3>Arhivos de ayuda</h3>
    </div>
    
    <div class="columns small-12 text-center">
        <i>Los siguientes archivos estan disponibles completamente gratis, esperamos sean útiles para tu trabajo</i>
    </div>
</div>
<br/>
<div class="row">
    <div class="columns small-12 medium-10 medium-centered">
        <table class="stack">
            <thead>
                <tr>
                    <th width="350">Nombre del Archivo</th>
                    <th width="250"></th>
                </tr>
            </thead>
            @foreach($files as $arc)
            <tr>
                <form method="POST" action="{{url('file')}}">
                    {{ csrf_field() }}

                    <input type="hidden" name="path" value="{{pathinfo($arc,1)}}"/>
                    <input type="hidden" name="file" value="{{pathinfo($arc,2)}}"/>
                    <td>
                        <span>{{pathinfo($arc,2)}}</span> 
                    </td>
                    <td>
                        <input type="submit" value="Descargar" class="button tiny alert"/>
                    </td>
                </form>
            </tr>
                
        @endforeach
        </table>
    </div>
</div>

    
@endsection