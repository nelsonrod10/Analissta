<?php

namespace App\Listeners;

use App\Events\NotificarUsuario;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificarUsuario
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
     * @param  NotificarUsuario  $event
     * @return void
     */
    public function handle(NotificarUsuario $event)
    {
        //
    }
}
