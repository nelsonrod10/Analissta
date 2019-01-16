@if ($errors)
    @include('inicio.layouts.errors')
@endif
<div class="row heading">
    <div class="col-sm-12 col-md-10 col-md-offset-1">
        <form name="frm-invitado" method="POST" action="{{route('comunidad-invitados.store')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-8  text-center">
                    <input required name="email" type="email" class="form-control " placeholder="invitado@email.com">
                    @if(session('success'))
                        <label class="label label-info">Invitado Almacenado, muchas gracias!</label>
                    @endif
                </div>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary">Invitar</button>
                </div>
            </div>
        </form>
    </div>
</div>
