<?php

namespace App\Http\Controllers\Comunidad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comunidad\ComunidadProfesionale;

class ComunidadProfesionalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profesionales = ComunidadProfesionale::all();
        return view('analissta.Comunidad.Profesionales.index')->with(compact('profesionales'));
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
            "tipo"          =>  "string|required",
            "nombre"        =>  "string|required",
            "profesion"     =>  "string|required",
            "ciudad"        =>  "string|required",
            "email"         =>  "email|required|unique:comunidad_profesionales,email|unique:comunidad_empresas,email",
            "telefono"      =>  "string|nullable",
            "licencia"      =>  "string|nullable",
            "perfil"        =>  "string|nullable",
            "aceptacion"    =>  "string|required",
        ],[
            "required"   => "Nombre de la Empresa",
            "unique"     => "Algunos  Ingresados Datos ya existen en nuestros registros",
            "string"     => "Error en el tipo de datos",
            "numeric"    => "Error en el tipo de datos",
        ]);
        
        $nuevo_miembro = ComunidadProfesionale::create([
            "nombre"        => $data["nombre"],
            "ciudad"        => $data["ciudad"],
            "profesion"     => $data["profesion"],
            "email"         => $data["email"],
            "telefono"      => $data["telefono"],
            "licencia"      => $data["licencia"],
            "perfil"        => $data["perfil"],
            "aceptacion_tratamiento_datos"  => $data["aceptacion"]
        ]);
        
        return redirect()->route('ver-especialidad-prof',compact('nuevo_miembro'));
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
    public function edit( $id)
    {
        $miembro = ComunidadProfesionale::find($id);
        return view('inicio.registro-comunidad.editar-data.persona-natural',compact('miembro'));
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
            "profesion"     =>  "string|required",
            "ciudad"        =>  "string|required",
            "email"         =>  "email|required",
            "telefono"      =>  "string|nullable",
            "licencia"      =>  "string|nullable",
            "perfil"        =>  "string|nullable"
        ],[
            "required"   => "Nombre de la Empresa",
            "string"     => "Error en el tipo de datos",
            "numeric"    => "Error en el tipo de datos",
        ]);
        
        ComunidadProfesionale::find($id)->update([
            "nombre"        => $data["nombre"],
            "ciudad"        => $data["ciudad"],
            "profesion"     => $data["profesion"],
            "email"         => $data["email"],
            "telefono"      => $data["telefono"],
            "licencia"      => $data["licencia"],
            "perfil"        => $data["perfil"]
        ]);
        $nuevo_miembro = ComunidadProfesionale::find($id);
        return redirect()->route('ver-especialidad-prof',compact('nuevo_miembro'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(ComunidadProfesionale::find($id)){
            ComunidadProfesionale::find($id)->delete();
        }
        
        return redirect()->route("home");
    }
}
