<?php

namespace App\Listeners;

use App\Events\RolesHabilidades;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Bouncer;
class CrearRolesHabilidades
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
     * @param  RolesHabilidades  $event
     * @return void
     */
    public function handle(RolesHabilidades $event)
    {
        Bouncer::allow('desarrollador')->to(['crear-empresa','configurar-empresa','crear-asesor','crear-usuarios-administrador','crear-usuarios-digitador','administrar-comunidad']);
        Bouncer::allow('super-admin')->to(['crear-empresa','configurar-empresa','crear-asesor','crear-usuarios-administrador','crear-usuarios-digitador','administrar-comunidad']);
        Bouncer::allow('asesor')->to(['crear-empresa','configurar-empresa','crear-asesor','crear-administrador','editar-configuracion-empresa']);
        Bouncer::allow('administrador')->to(['configurar-empresa','crear-administrador','editar-configuracion-empresa']);//acá todas las relacionadas con analissta
        Bouncer::allow('digitador')->to(['reportar-accidentes']);//acá todas las relacionadas con analissta
        Bouncer::assign('desarrollador')->to(1);
        Bouncer::assign('super-admin')->to(1);
        Bouncer::assign('super-admin')->to(2);
    }
}
