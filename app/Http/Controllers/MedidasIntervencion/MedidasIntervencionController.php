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
    public function crearMedidaIntervencionValoracion($nombre,$tipoMedida,$medida,Peligro $peligro) {
        
        $arrCreate_inspecciones_actividades = [
            'sistema_id'  => session('sistema')->id, 
            'peligro_id'  => $peligro->id, 
            'medida'      => $medida, 
            'nombre'      => $nombre,
        ];
        
        $arrCreate_capacitaciones = [
            'sistema_id'  => session('sistema')->id, 
            'peligro_id'  => $peligro->id, 
            'medida'      => $medida, 
            'nombre'      => $nombre,
        ];
        
        switch ($tipoMedida) {
            case "Actividad":
                $accionCreada = ActividadesValoracione::create($arrCreate_inspecciones_actividades);
                //$accionCreada->peligro()->attach($peligro->id);
            break;
            case "Capacitacion":
                $accionCreada = CapacitacionesValoracione::create($arrCreate_capacitaciones);
                //$accionCreada->peligro()->attach($peligro->id);
            break;
            case "Inspeccion":
                $accionCreada = InspeccionesValoracione::create($arrCreate_inspecciones_actividades);
                //$accionCreada->peligro()->attach($peligro->id);
            break;
        }
        $accionCreada->peligro()->attach($peligro->id);    
        $this->crearMedidaIntervencionDisponible($nombre, $tipoMedida, $medida,$peligro,$accionCreada->id);
    }
    
    private function crearMedidaIntervencionDisponible($nombre,$tipoMedida,$medida,Peligro $peligro,$idAccionCreada) {
        //$peligro = Peligro::find($idPeligro);
        $strAccionCreada = strtolower($tipoMedida)."es_valoracione_id";
        $arrCreate = [
            'sistema_id'        => session('sistema')->id, 
            'clasificacion_peligro_id' => $peligro->clasificacion, 
            $strAccionCreada           => $idAccionCreada, 
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
    
    public function copiarMedidaDeDisponibles($idDisponible, $tipoMedida, Peligro $peligro){
        switch ($tipoMedida) {
            case "Actividad":
                $disponible = ActividadesDisponible::find($idDisponible);
                if(!ActividadesValoracione::find($disponible->actividades_valoracione_id)){    
                    $accionCreada = ActividadesValoracione::create([
                        'sistema_id'  => session('sistema')->id, 
                        'peligro_id'  => $peligro->id, 
                        'medida'      => $disponible->medida, 
                        'nombre'      => $disponible->nombre,
                    ]);
                    $disponible->actividades_valoracione_id = $accionCreada->id;
                    $disponible->save();
                }else{
                    $accionCreada = ActividadesValoracione::find($disponible->actividades_valoracione_id);
                }
            break;
            case "Capacitacion":
                $disponible = CapacitacionesDisponible::find($idDisponible);
                if(!CapacitacionesValoracione::find($disponible->capacitaciones_valoracione_id)){
                    $accionCreada = CapacitacionesValoracione::create([
                        'sistema_id'  => session('sistema')->id, 
                        'peligro_id'  => $peligro->id, 
                        'medida'      => $disponible->medida, 
                        'nombre'      => $disponible->nombre,
                    ]);
                    $disponible->capacitaciones_valoracione_id = $accionCreada->id;
                    $disponible->save();
                }else{
                    $accionCreada = CapacitacionesValoracione::find($disponible->capacitaciones_valoracione_id);
                }
            break;
            case "Inspeccion":
                $disponible = InspeccionesDisponible::find($idDisponible);
                if(!InspeccionesValoracione::find($disponible->inspecciones_valoracione_id)){
                    $accionCreada = InspeccionesValoracione::create([
                        'sistema_id'  => session('sistema')->id, 
                        'peligro_id'  => $peligro->id, 
                        'medida'      => $disponible->medida, 
                        'nombre'      => $disponible->nombre,
                    ]);
                    $disponible->inspecciones_valoracione_id = $accionCreada->id;
                    $disponible->save();
                }else{
                    $accionCreada = InspeccionesValoracione::find($disponible->inspecciones_valoracione_id);
                }
            break;
            default:
            break;
        }
        $accionCreada->peligro()->attach($peligro->id);
    }
    
    public function eliminarMedidaIntervencionValoracion($id, $tipoMedida,$idPeligro){
        switch ($tipoMedida) {
            case "Actividad":
                $medida = ActividadesValoracione::find($id);
                $peligro = Peligro::find($idPeligro);
                $peligro->actividadesValoracion()->detach($medida->id);
                if($medida->peligro->count() == 0){
                    $disponible=ActividadesDisponible::where("actividades_valoracione_id",$medida->id)->first();
                    $disponible->actividades_valoracione_id="";
                    $disponible->save();
                    $medida->delete();
                }
            break;
            case "Capacitacion":
                $medida = CapacitacionesValoracione::find($id);
                $peligro = Peligro::find($idPeligro);
                $peligro->capacitacionesValoracion()->detach($medida->id);
                if($medida->peligro->count() == 0){
                    $disponible=CapacitacionesDisponible::where("capacitaciones_valoracione_id",$medida->id)->first();
                    $disponible->capacitaciones_valoracione_id="";
                    $disponible->save();
                    $medida->delete();
                }
            break;
            case "Inspeccion":
                $medida = InspeccionesValoracione::find($id);
                $peligro = Peligro::find($idPeligro);
                $peligro->inspeccionesValoracion()->detach($medida->id);
                if($medida->peligro->count() == 0){
                    $disponible=InspeccionesDisponible::where("inspecciones_valoracione_id",$medida->id)->first();
                    $disponible->inspecciones_valoracione_id="";
                    $disponible->save();
                    $medida->delete();
                }
            break;
            default:
            break;
        }
        return;
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
