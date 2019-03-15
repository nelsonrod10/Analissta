<div class="small reveal" data-reveal="" id="eliminar-empresa-{{$empresa_comunidad->id}}">
    <div class="row columns text-center">
        <i class="fi-alert" style="font-size:32px; color:red"></i>
    </div>
    <div class="row columns text-center">
        <div style="font-size:14px"><b>¿Confirma que desea eliminar a la empresa {{$empresa_comunidad->nombre}}?</b></div>
        <div style="font-size:14px"><b>Se perderán todos los datos configurados</b></div>
    </div>
    <br/>
    <div class="row columns text-center">
        <form method="POST" name="eliminar-empresa" action="{{route('analissta-empresas.destroy',$empresa_comunidad)}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <input type="submit" class="button small success-2" value="Confirmar"/>
            <a class="button small alert" data-close="">Cancelar</a>
        </form>
        
    </div>
</div>