<?php

namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Proceso;
use Illuminate\Validation\Rule;

class ProcesoController extends Controller
{
    public function crearProceso(){
        $data = request()->validate([
           'nombre'    => 'string|required', 
           'nombre' => Rule::unique('procesos')->where(function ($query) {
                return $query->where('sistema_id', session('sistema')->id);
            }) 
        ],[
            'nombre.required' => 'Debe ingresar el nombre del proceso',
            'nombre.string' => 'Nombre de proceso no valido',
            'nombre.unique' => 'Ya existe un proceso con el mismo nombre'
        ]);
        Proceso::create([
           'sistema_id' => session('sistema')->id,
           'nombre'            => $data['nombre'], 
        ]);
        
        return back();
    }
    
    
}
