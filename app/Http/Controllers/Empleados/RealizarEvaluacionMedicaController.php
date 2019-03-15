<?php

namespace App\Http\Controllers\Empleados;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Empleados\EvaluacionesMedica;
use App\Http\Controllers\helpers;
use App\Http\Controllers\Empleados\EvaluacionesMedicasController;

class RealizarEvaluacionMedicaController extends Controller
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
        $data = $request->validate(['fecha'  =>  'string|required']);
        
        $evaluacion = EvaluacionesMedica::find($id);
        
        $evaluacion->update([
           'anio_realizado' =>  (string)helpers::getAnioFecha($data['fecha']), 
           'mes_realizado'  =>  (string)helpers::getMesFecha($data['fecha']),
           'dia_realizado'  =>  (string)helpers::getDiaFecha($data['fecha']),
           'estado'         =>  "Realizada"  
        ]);
        
        $nuevoAnio = helpers::getAnioFecha($data['fecha'])+1;
        $nuevaEvaluacion = new EvaluacionesMedicasController();
        $nuevaEvaluacion->store(
            new Request([
                'empresa'  =>  (string)$evaluacion->empresaCliente_id,
                'empleado' =>  (string)$evaluacion->empleado_id,
                'anio'     =>  (string)$nuevoAnio,
                'mes'      =>  helpers::getMesFecha($data['fecha']),
                'dia'      =>  helpers::getDiaFecha($data['fecha']), 
            ])
        );
        
        return back();
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
