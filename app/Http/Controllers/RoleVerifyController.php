<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Bouncer; 

class RoleVerifyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //esto toca cambiarlo para mover al usuario desde acá a la página que le corresponde teniendo en cuenta el siguiente codigo que esta en routes/web.php
        /*Route::get('/inicio', function(){
            //si existe se elimina la variable de session
            \App\Http\Controllers\EmpresaClienteController::unsetSessionVariables();
            $user = App\User::find(Auth::user()->id);
            if (Gate::forUser(Auth::user())->allows('verify-Asesor-role')) {
                $asesorUser = $user->asesor;
                foreach ($asesorUser as $value) {
                    $idAsesor = $value->id;
                }
                $asesor = App\Asesore::find($idAsesor);
                $empresaAsesor = $asesor->empresaAsesor;
                $empresasCliente = $asesor->empresasCliente;
                return view('analissta.inicioAsesor')->with(['datosEmpresa'=>$empresaAsesor,'empresasCliente'=>$empresasCliente]);
            }

            $usuarioUser = $user->usuario; 
            foreach ($usuarioUser as $value) {
                $idUsuario = $value->id;
            }
            $empresaCliente = App\Usuario::find($idUsuario)->empresaCliente;
            //return view('analissta.inicioCliente')->with('datosEmpresa',$empresaCliente);
            return redirect()->route('ver-empresa-cliente',['id'=>$empresaCliente->id]);
        })->name('inicio');*/
        
        $user = Auth::user();
        if($user->isA('super-admin')){
            return redirect('inicio');
        }
        if($user->isAn('asesor')){
            return redirect()->route('gestion-asesores.index');
        }
        if($user->isAn('administrador')){
            return redirect()->route('gestion-administradores.index');
        }
        if($user->isAn('digitador')){
            return redirect()->route('gestion-administradores.index');
            //return redirect()->route('gestion-evaluadores.index');
        }
        return redirect('/inicio');
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
