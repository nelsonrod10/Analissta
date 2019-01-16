<div class="expanded button-group show-for-medium">
    <a class="button" href="{{ route('ver-empresa-cliente',['id'=>$empresa->id]) }}">Empresa</a>
    <a class="button" href="{{ route('procesos-actividades',['sistema'=>session('sistema')]) }}">Valoración</a>
    <a class="button" href="{{ route('actividades')}}">Actividades</a>
    <a class="button" href="{{ route('capacitaciones')}}">Capacitaciones</a>
    <a class="button" href="{{ route('inspecciones')}}">Inspecciones</a>
    <a class="button" href="{{ route('hallazgos')}}">Hallazgos</a>
    <a class="button" href="{{ route('accidentes')}}">Accidentalidad</a>
    <a class="button" href="{{ route('ausentismos')}}">Ausentismo</a>
    <a class="button" href="{{ route('pgrps')}}">PGRP</a>
    <a class="button" href="{{ route('pves')}}">PVE</a>
    <a class="button" href="{{ route('general.index')}}">Presupuesto</a>
    <a class="button" href="{{url('/files')}}">Descargas</a>
</div>

