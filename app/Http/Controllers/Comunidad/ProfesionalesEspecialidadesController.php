<?php

namespace App\Http\Controllers\Comunidad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comunidad\ComunidadProfesionale;
use App\Comunidad\ComunidadEspecialidadesProfesionale;

class ProfesionalesEspecialidadesController extends Controller
{
    public function mostrarFrmEspecialidades(ComunidadProfesionale $nuevo_miembro){
        return view('comunidad-especialidades',['nuevo_miembro'=>$nuevo_miembro,'tipo'=>'Profesional']);
    }

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
            'categoria'     => 'string|required',
            'especialidades'=> 'array|required'     
        ],[
            'required'  =>  'Diligencie los campos obligatorios',
            'string'    =>  'error en el tipo de dato',
            'array'     =>  'error en el tipo de dato'
        ]);
        
        ComunidadEspecialidadesProfesionale::create([
            'comProf_id'    =>  (int)$data["miembro"],
            'categoria'     =>  $data["categoria"],
            'especialidades'=> implode(",", $data["especialidades"])
        ]);
        
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
    public function edit($id)
    {
        $especialidad = ComunidadEspecialidadesProfesionale::find($id);
        return view('inicio.registro-comunidad.especialidades.edit',['especialidad'=>$especialidad,'tipo'=>'Profesional']);
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
        $data = $request->validate([
            'especialidades'=> 'array|required'     
        ],[
            'required'  =>  'Debe seleccionar por lo menos una especialidad',
            'array'     =>  'error en el tipo de dato'
        ]);
        
        ComunidadEspecialidadesProfesionale::find($id)->update([
            'especialidades'=> implode(",", $data["especialidades"])
        ]);
        
        $especialidadesProfesional = ComunidadEspecialidadesProfesionale::find($id);
        return redirect()->route('ver-especialidad-prof',$especialidadesProfesional->Profesional);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $especialidadesProfesional = ComunidadEspecialidadesProfesionale::find($id);
        
        $especialidadesProfesional->delete();
        
        return redirect()->route('ver-especialidad-prof',$especialidadesProfesional->Profesional);
    }
}
