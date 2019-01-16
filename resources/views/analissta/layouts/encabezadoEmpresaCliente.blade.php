@if($empresa)
<div class="row columns text-center" style="background:#0c4d78; color: white; font-size: 20px">
    <b>{{$empresa->nombre}}</b>
</div>
@endif
@if(session('sistema') !== null && $empresa->tipoValoracion == 'Matriz por Centro')
<div class="row columns text-center" style="background:#91b5d3; color: white; font-size: 20px">
    <b>{{session('sistema')->centrosTrabajo->last()->nombre}}</b>
</div>
@endif

