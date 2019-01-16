<?php

namespace App\Http\Controllers\MedidasIntervencion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\Peligro;
use App\ActividadesValoracione;
use App\CapacitacionesValoracione;
use App\InspeccionesValoracione;

use App\ActividadesDisponible;
use App\CapacitacionesDisponible;
use App\InspeccionesDisponible;

class MedidasIntervencionController extends Controller
{
    public function crearMedidaIntervencionValoracion($nombre,$tipoMedida,$medida,$flag,$idPeligro) {
        
        $arrCreate_inspecciones_actividades = [
            'sistema_id'  => session('sistema')->id, 
            'peligro_id'  => $idPeligro, 
            'medida'      => $medida, 
            'nombre'      => $nombre,
        ];
        
        $arrCreate_capacitaciones = [
            'sistema_id'  => session('sistema')->id, 
            'peligro_id'  => $idPeligro, 
            'medida'      => $medida, 
            'nombre'      => $nombre,
        ];
        
        switch ($tipoMedida) {
            case "Actividad":
                ActividadesValoracione::create($arrCreate_inspecciones_actividades);
            break;
            case "Capacitacion":
                CapacitacionesValoracione::create($arrCreate_capacitaciones);
            break;
            case "Inspeccion":
                InspeccionesValoracione::create($arrCreate_inspecciones_actividades);
            break;
            default:
            break;
        }
        if($flag === "crear-en-disponibles"){
            $this->crearMedidaIntervencionDisponible($nombre, $tipoMedida, $medida,$idPeligro);
        }
        
    }
    
    private function crearMedidaIntervencionDisponible($nombre,$tipoMedida,$medida,$idPeligro) {
        $peligro = Peligro::find($idPeligro);
        $arrCreate = [
            'sistema_id'        => session('sistema')->id, 
            'clasificacion_peligro_id' => $peligro->clasificacion, 
            'medida'                   => $medida, 
            'nombre'                   => $nombre,
        ];
        
        switch ($tipoMedida) {
            case "Actividad":
                ActividadesDisponible::create($arrCreate);
            break;
            case "Capacitacion":
                CapacitacionesDisponible::create($arrCreate);
            break;
            case "Inspeccion":
                InspeccionesDisponible::create($arrCreate);
            break;
            default:
            break;
        }
    }
    
    public function eliminarMedidaIntervencionValoracion($id, $tipoMedida){
        switch ($tipoMedida) {
            case "Actividad":
                ActividadesValoracione::find($id)->delete();
            break;
            case "Capacitacion":
                CapacitacionesValoracione::find($id)->delete();
            break;
            case "Inspeccion":
                InspeccionesValoracione::find($id)->delete();
            break;
            default:
            break;
        }
    }
    
    public static function unsetSessionVariables(){
        if (session('arrMedidas')!==null) {
            session()->forget('arrMedidas');
        }
    }
    
    public function eliminarTodasMedidasIntervencion(){
        ActividadesValoracione::where('peligro_id',session('idPeligro'))->delete();
        CapacitacionesValoracione::where('peligro_id',session('idPeligro'))->delete();
        InspeccionesValoracione::where('peligro_id',session('idPeligro'))->delete();
    }
    
    public function calendarioActividades(){
        
        
        return view('analissta.Actividades.calendario');
    }
    
    public function calendarioCapacitaciones(){
        
        
        return view('analissta.Capacitaciones.calendario');
    }
    
    public function calendarioInspecciones(){
        
        
        return view('analissta.Inspecciones.calendario');
    }
    
    
}
