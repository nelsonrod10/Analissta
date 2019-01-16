<?php

namespace App\Providers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\EmpresaCliente;
use App\Sistema;

use App\Http\Controllers\helpers;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        View::composer('*',function($view){
            $empresa=$sistema=$datosEmpresa=null;
            if(Auth::user()){
                $datosEmpresa = helpers::getAsesor();
            }
            if(session('idEmpresaCliente')){
                
                $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
            }
            
            if(session('sistema')!==null){
                $sistema = Sistema::find(session('sistema')->id);
            }
            $view->with(compact('empresa','datosEmpresa','sistema'));
            
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
