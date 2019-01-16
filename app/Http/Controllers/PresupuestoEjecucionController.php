<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PresupuestoEjecucione;
use App\Presupuesto;

class PresupuestoEjecucionController extends Controller
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
           'calendario_id'      => 'string|required',
           'calendario_table'   => 'string|required',
           'presupuesto_id'     => 'string|required',
           'observaciones'      => 'string|required',
           'valor'              => 'numeric|required'
        ],[
            'required'          => 'Diligencie los campos obligatorios',
            'string'            => 'Formato de campo inválido',
            'numeric'           => 'Formato de campo inválido'
        ]);
        
        PresupuestoEjecucione::create([
            'sistema_id'        =>  session('sistema')->id,
            'presupuesto_id'    =>  $data['presupuesto_id'],
            'calendario_id'     =>  $data['calendario_id'],
            'tabla_calendario'  =>  $data['calendario_table'],
            'observaciones'     =>  $data['observaciones'],
            'valor'             =>  $data['valor']
        ]);
        $presupuesto = Presupuesto::find($data['presupuesto_id']);
        //$valorTotalReal = ;
        $presupuesto->update([
            'valor_real'    =>$presupuesto->itemsEjecuciones->sum('valor')    
        ]);
        return back();
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
