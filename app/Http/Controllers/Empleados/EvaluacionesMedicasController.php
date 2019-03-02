<?php

namespace App\Http\Controllers\Empleados;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Empleado;
use App\Empleados\EvaluacionesMedica;

class EvaluacionesMedicasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('analissta.Empleados.EvaluacionesMedicas.index');
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
            'empresa'  =>  'required|string',
            'empleado' =>  'required|string',
            'anio'     =>   'required|string',
            'mes'      =>   'required|string',
            'dia'      =>   'required|string', 
        ]);
        
        $evaluacion = EvaluacionesMedica::where('empleado_id',(int)$data['empleado'])->first();
        
        if($evaluacion && (string)$evaluacion->estado === "N/A" && (string)$evaluacion->anio_sugerido === $data["anio"]){
            $this->update(new Request([
                'anio'     =>   $data["anio"],
                'mes'      =>   $data["mes"],
                'dia'      =>   $data["dia"], 
            ]),$evaluacion->id);
            
            return;
        }
        
        EvaluacionesMedica::create([
            'empresaCliente_id'   =>  $data['empresa'],
            'empleado_id'   =>  $data['empleado'],
            'anio_sugerido' =>  $data['anio'],
            'mes_sugerido'  =>  $data['mes'],
            'dia_sugerido'  =>  $data['dia'],
        ]);
        
        return;
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
            'anio'     =>   'required|string',
            'mes'      =>   'required|string',
            'dia'      =>   'required|string', 
        ]);
        
        $evaluacion = EvaluacionesMedica::find($id);
        
        $evaluacion->update([
            'anio_sugerido'  => $data["anio"],
            'mes_sugerido'   => $data["mes"],
            'dia_sugerido' => $data["dia"],
        ]);
        
        return;
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
