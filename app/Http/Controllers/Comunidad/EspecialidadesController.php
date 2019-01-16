<?php

namespace App\Http\Controllers\Comunidad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Comunidad\EmpresasEspecialidadesController;
use App\Http\Controllers\Comunidad\ProfesionalesEspecialidadesController;

class EspecialidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $data = $request->validate([
            'miembro'       => 'string|required',
            'tipo'          => 'string|required',
            'categoria'     => 'string|required',
            'especialidades'=> 'array|required'     
        ],[
            'required'  =>  'Diligencie los campos obligatorios',
            'string'    =>  'error en el tipo de dato',
            'array'     =>  'error en el tipo de dato'
        ]);
        
        if($data["tipo"] == "Profesional"){
            $especialidad = new ProfesionalesEspecialidadesController;
            $especialidad->store($request);
        }
        if($data["tipo"] == "Empresa"){
            $especialidad = new EmpresasEspecialidadesController;
            $especialidad->store($request);
        }
        
        return redirect()->back();
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
    public function edit(Request $request,$id)
    {
        $data = $request->validate([
            'tipo'  => 'string|required'
        ]);
        
        if($data["tipo"] == "Profesional"){
            $especialidad = new ProfesionalesEspecialidadesController;
            return $especialidad->edit($id);
        }
        if($data["tipo"] == "Empresa"){
            $especialidad = new EmpresasEspecialidadesController;
            return $especialidad->edit($id);
        }
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
    public function destroy(Request $request,$id)
    {
        $data = $request->validate([
            'tipo'  => 'string|required'
        ]);
        
        if($data["tipo"] == "Profesional"){
            $especialidad = new ProfesionalesEspecialidadesController;
            return $especialidad->destroy($id);
        }
        if($data["tipo"] == "Empresa"){
            $especialidad = new EmpresasEspecialidadesController;
            return $especialidad->destroy($id);
        }
    }
}
