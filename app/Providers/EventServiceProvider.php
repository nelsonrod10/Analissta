<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\NuevoUsuarioCreado' => [
            'App\Listeners\AsignarRolUsuarioNuevo',
            'App\Listeners\NotificarUsuarioNuevo',
        ],
        
        'App\Events\NotificarUsuario' => [
            'App\Listeners\NotificarUsuario',
        ],
        
        'App\Events\RolesHabilidades' => [
            'App\Listeners\CrearRolesHabilidades',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
