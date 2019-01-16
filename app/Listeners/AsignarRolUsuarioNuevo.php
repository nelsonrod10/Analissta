<?php

namespace App\Listeners;

use App\Events\NuevoUsuarioCreado;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AsignarRolUsuarioNuevo
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NuevoUsuarioCreado  $event
     * @return void
     */
    public function handle(NuevoUsuarioCreado $event)
    {
        //
    }
}
