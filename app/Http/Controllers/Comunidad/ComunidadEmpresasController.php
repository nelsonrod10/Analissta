<?php

namespace App\Http\Controllers\Comunidad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comunidad\ComunidadEmpresa;

class ComunidadEmpresasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas_comunidad = ComunidadEmpresa::all();
        return view('analissta.Comunidad.Empresas.index')->with(compact('empresas_comunidad'));
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
            "tipo"        =>  "string|required",
            "nombre"        =>  "string|required",
            "identificacion"=>  "numeric|required|unique:comunidad_empresas,identificacion",
            "ciudad"        =>  "string|required",
            "web"           =>  "string|nullable|unique:comunidad_empresas,web",
            "email"         =>  "string|required|unique:comunidad_profesionales,email|unique:comunidad_empresas,email",
            "telefono"      =>  "numeric|nullable",
            "licencia"      =>  "string|nullable",
            "perfil"        =>  "string|nullable",
            "aceptacion"    =>  "string|required",
        ],[
            "required"   => "Nombre de la Empresa",
            "unique"     => "Algunos Datos Ingresados ya existen en nuestros registros",
            "string"     => "Error en el tipo de datos",
            "numeric"    => "Error en el tipo de datos",
        ]);
        
        $nuevo_miembro = ComunidadEmpresa::create([
            "nombre"        => $data["nombre"],
            "identificacion"=> $data["identificacion"],
            "ciudad"        => $data["ciudad"],
            "web"           => $data["web"],
            "email"         => $data["email"],
            "telefono"      => $data["telefono"],
            "licencia"      => $data["licencia"],
            "perfil"        => $data["perfil"],
            "aceptacion_tratamiento_datos"  => $data["aceptacion"]
        ]);
        
        return redirect()->route('ver-especialidad-emp',compact('nuevo_miembro'));
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
        $miembro = ComunidadEmpresa::find($id);
        return view('inicio.registro-comunidad.editar-data.persona-juridica',compact('miembro'));
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
            "nombre"        =>  "string|required",
            "identificacion"=>  "numeric|required",
            "ciudad"        =>  "string|required",
            "web"           =>  "string|nullable",
            "email"         =>  "string|required",
            "telefono"      =>  "numeric|nullable",
            "licencia"      =>  "string|nullable",
            "perfil"        =>  "string|nullable"
        ],[
            "required"   => "Diligencie los campos obligatorios",
            "string"     => "Error en el tipo de datos",
            "numeric"    => "Error en el tipo de datos",
        ]);
        
        ComunidadEmpresa::find($id)->update([
            "nombre"        => $data["nombre"],
            "identificacion"=> $data["identificacion"],
            "ciudad"        => $data["ciudad"],
            "web"           => $data["web"],
            "email"         => $data["email"],
            "telefono"      => $data["telefono"],
            "licencia"      => $data["licencia"],
            "perfil"        => $data["perfil"]
        ]);
        $nuevo_miembro = ComunidadEmpresa::find($id);
                
        return redirect()->route('ver-especialidad-emp',compact('nuevo_miembro'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(ComunidadEmpresa::find($id)){
            ComunidadEmpresa::find($id)->delete();
        }
        
        return redirect()->back();
    }
}
