<div class="columns small-12 medium-6 small-centered text-center">
    @if ($errors->any())
    <div class="callout alert"  style="color:#ff4d4d; font-weight: bold;">
        <h5>Se han presentado los siguientes errores</h5>
        @foreach ($errors->all() as $error)
            <div>* {{ $error }}</div>
        @endforeach
    </div>    
    @endif
</div>