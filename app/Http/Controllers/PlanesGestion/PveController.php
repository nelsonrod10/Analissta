<?php

namespace App\Http\Controllers\PlanesGestion;

use Illuminate\Http\Request;
use App\Http\Controllers\helpers;
use App\Http\Controllers\Controller;
use App\PlanesGestion\PveFisico;
use App\PlanesGestion\PveSeguridade;
use App\PlanesGestion\PveGenerale;
use App\EmpresaCliente;
use App\Http\Controllers\PlanesGestion\PveFisicoController;
use App\Http\Controllers\PlanesGestion\PveSeguridadController;
use App\Http\Controllers\PlanesGestion\PveGeneralController;

class PveController extends Controller
{
    public function crearPVE($peligro){
        $pve_id = 0;
        switch ((int)$peligro->clasificacion) {
            case 1: //pveFisico
                $pve_id = $this->crearPveFisico($peligro);
            break;
            case 6: //pveSeguridad
                $pve_id = $this->crearPveSeguridad($peligro);
            break;
            default: //pveGeneral
                $pve_id = $this->crearPveGeneral($peligro);
            break;
        }
        return $pve_id;
    }
    
    private function crearPveFisico($peligro){
        $existePveFisico = PveFisico::where('sistema_id',session('sistema')->id)
                ->where('categoria',$peligro->categoria)->get();
        if(count($existePveFisico) > 0){
            return $existePveFisico[0]->id;
        }
        
        $newPve = PveFisico::create([
            'anio' => helpers::getCurrentYear(),
            'sistema_id' => session('sistema')->id,
            'categoria' => $peligro->categoria
        ]);
        return $newPve->id;
    }
    
    private function crearPveSeguridad($peligro){
        $existePveSeguridad = PveSeguridade::where('sistema_id',session('sistema')->id)
                ->where('categoria',$peligro->categoria)->get();
        if(count($existePveSeguridad) > 0){
            return $existePveSeguridad[0]->id;
        }
        
        $newPve = PveSeguridade::create([
            'anio' => helpers::getCurrentYear(),
            'sistema_id' => session('sistema')->id,
            'categoria' => $peligro->categoria
        ]);
        return $newPve->id;
    }
    
    private function crearPveGeneral($peligro){
        $existePveGeneral = PveGenerale::where('sistema_id',session('sistema')->id)
                ->where('clasificacion',$peligro->clasificacion)->get();
        if(count($existePveGeneral) > 0){
            return $existePveGeneral[0]->id;
        }
        
        $newPve = PveGenerale::create([
            'anio' => helpers::getCurrentYear(),
            'sistema_id' => session('sistema')->id,
            'clasificacion' => $peligro->clasificacion
        ]);
        
        return $newPve->id;
    }
    
    public function eliminarPveFisicos($id){
        PveFisico::find($id)->delete();
        return;
    }
    
    public function eliminarPveSeguridades($id){
        PveSeguridade::find($id)->delete();
        return;
    }
    
    public function eliminarPveGenerales($id){
        PveGenerale::find($id)->delete();
        return;
    }
    
    public function principalPve(){
        
        
        return view('analissta.Pve.vistaGeneral');
    }
    
    public function mostrarPve($tipo,$id){
        switch ($tipo) {
            case "general":
                $pve = PveGenerale::find($id);
                $clasificacion = $pve->clasificacion;
                break;
            case "seguridad":
                $pve = PveSeguridade::find($id);
                $clasificacion = 6;
                break;
            default:
                $pve = PveFisico::find($id);
                $clasificacion = 1;
                break;
        }
        
        
        
        return view('analissta.Pve.verPve')->with(['pve'=>$pve,'clasificacion'=>$clasificacion]);
    }
    
    public function editarPve($clasificacion,$id){
        switch ($clasificacion) {
            case 1:
                $pve = PveFisico::find($id);
                break;
            case 6:
                $pve = PveSeguridade::find($id);
                break;
            default:
                $pve = PveGenerale::find($id);
                break;
        }
        
        
        
        return view('analissta.Pve.editarPve')->with(['pve'=>$pve,'clasificacion'=>$clasificacion]);
    }
    
    
    public function datosPve(){
        $data = request()->validate([
            'idPve'          => 'string|required',
            'clasificacion'   => 'string|required',
        ],[
            'idPve.required'         => 'Diligencie todos los campos',
            'clasificacion.required'  => 'Diligencie todos los campos',
        ]);
        
        switch ($data['clasificacion']) {
            case 1:
                $tipo="fisico";
                $controller = new PveFisicoController();
                $controller->guardarData();
                break;
            case 6:
                $tipo="seguridad";
                $controller = new PveSeguridadController();
                $controller->guardarData();
                break;
            default:
                $tipo="general";
                $controller = new PveGeneralController();
                $controller->guardarData();
                break;
        }
        return redirect()->route('pve',['tipo'=>$tipo,'id'=>$data['idPve']]); 
    }
}
