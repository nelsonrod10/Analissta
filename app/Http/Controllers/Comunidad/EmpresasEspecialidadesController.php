<?php

namespace App\Http\Controllers\Comunidad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comunidad\ComunidadEmpresa;
use App\Comunidad\ComunidadEspecialidadesEmpresa;

class EmpresasEspecialidadesController extends Controller
{
    public function mostrarFrmEspecialidades(ComunidadEmpresa $nuevo_miembro){
        return view('comunidad-especialidades',['nuevo_miembro'=>$nuevo_miembro,'tipo'=>'Empresa']);
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
        
        ComunidadEspecialidadesEmpresa::create([
            'comEmp_id'     =>  (int)$data["miembro"],
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
        $especialidad = ComunidadEspecialidadesEmpresa::find($id);
        return view('inicio.registro-comunidad.especialidades.edit',['especialidad'=>$especialidad,'tipo'=>'Empresa']);
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
        
        ComunidadEspecialidadesEmpresa::find($id)->update([
            'especialidades'=> implode(",", $data["especialidades"])
        ]);
        
        $especialidadesEmpresa = ComunidadEspecialidadesEmpresa::find($id);
        return redirect()->route('ver-especialidad-emp',$especialidadesEmpresa->Empresa);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $especialidadesEmpresa = ComunidadEspecialidadesEmpresa::find($id);
        
        $especialidadesEmpresa->delete();
        
        return redirect()->route('ver-especialidad-emp',$especialidadesEmpresa->Empresa);
    }
}
