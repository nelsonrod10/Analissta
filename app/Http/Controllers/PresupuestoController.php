<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Presupuesto;
use App\ActividadesValoracione;
use App\ActividadesHallazgo;
use App\ActividadesObligatoriasSugerida;

class PresupuestoController extends Controller
{
    public function index()
    {
        return view('analissta.Presupuesto.index');
    }
    
    public function show($id)
    {
        
    }
    
    public function update(Request $request, $id){
        $data = $request->validate([
            'valor_real' => 'required|string'
        ]);
        Presupuesto::find($id)->update(['valor_real' => $data['valor_real']]);
        //$presupuesto;
        return redirect()->back();
    }
    
    public function verMedidaIntervencion(Presupuesto $presupuesto,$tipo){
        $tabla = ucwords(str_replace("_", " ", $presupuesto->tabla_origen));
        $modelo = "App\\".substr(ucwords(str_replace(" ", "", $tabla)),0,-1);
        $medida = $modelo::find($presupuesto->origen_id)->medida;
        switch ($medida) {
            case "hallazgos":
                $medida = "hallazgo";
                break;
            case ($medida !== "hallazgos" && $medida !== "obligatoria" && $medida !== "sugerida"):
                $medida = "valoracion";
                break;
        }
        
        return redirect()->route("$tipo-$medida",['id'=>$presupuesto->origen_id]);
    }
    
    public function eliminarItemPresupuesto(){
        $data = request()->validate([
            'tipo'              => 'required',
            'idActividad'       => 'required',
            'origen'            => 'required',
            'id'                => 'string|required',
        ]);
        Presupuesto::find($data["id"])->delete();
        return redirect()->route($data["origen"],['id'=>$data["idActividad"],'tipo'=>$data["tipo"]]);
    }
    
    public function eliminarItemPresupuestoCapacitacion(){
        $data = request()->validate([
            'tipo'              => 'required',
            'idActividad'       => 'required',
            'origen'            => 'required',
            'arrDataCentros'    => 'required',
            'id'                => 'string|required',
        ]);
        Presupuesto::find($data["id"])->delete();
        return redirect()->route($data["origen"],['id'=>$data["idActividad"],'tipo'=>$data["tipo"],'arrDataCentros'=>$data["arrDataCentros"]]);
    }
    
    public static function getNombreOrigen($id){
        $presupuesto = Presupuesto::find($id);
        $tabla = ucwords(str_replace("_", " ", $presupuesto->tabla_origen));
        $modelo = "App\\".substr(ucwords(str_replace(" ", "", $tabla)),0,-1);
        return $modelo::find($presupuesto->origen_id)->nombre;
    }
}
