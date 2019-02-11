<?php

namespace App\Http\Controllers;
use App\ActividadesValoracione;
use App\ActividadesDisponible;

use App\CapacitacionesValoracione;
use App\CapacitacionesDisponible;

use App\InspeccionesValoracione;
use App\InspeccionesDisponible;

use App\Peligro;

use Illuminate\Http\Request;

class ActualizacionesController extends Controller
{
    public function realizarActualizacion(Request $request){
        //$medidaPrincipal,$peligro
        $data = $request->validate([
            "tipo"  =>  "required|string",
            "nombre" =>  "required|string"
        ]);
        
        
        switch($data["tipo"]){
            case("Actividades"):
                $medidas = ActividadesValoracione::where('nombre',$data["nombre"])->get();
                $medidasDisponibles = ActividadesDisponible::where('nombre',$data["nombre"])->get();
                foreach($medidas as $medidaValoracion){
                    if($medidaValoracion->estado == "Programada"){
                        $medidaPrincipal = $medidaValoracion;
                    }else{
                        $medidaPrincipal = $medidas->first();
                    }
                    $peligro = Peligro::find($medidaValoracion->peligro_id);
                    $peligro->actividadesValoracion()->attach($medidaPrincipal->id);
                    if($medidaValoracion->id !== $medidaPrincipal->id){
                        $medidaValoracion->delete();
                    }
                }
                
                
                foreach($medidasDisponibles as $medidaDisponible){
                    if($medidasDisponibles->count() > 1){
                        $medidaDisponible->delete();
                    }
                }
                
                $medidasDisponibles->first()->actividades_valoracione_id = $medidaPrincipal->id;
                $medidasDisponibles->first()->save();
               
            break;
            case("Capacitaciones"):
                $medidas = CapacitacionesValoracione::where('nombre',$data["nombre"])->get();
                $medidasDisponibles = CapacitacionesDisponible::where('nombre',$data["nombre"])->get();
                foreach($medidas as $medidaValoracion){
                    if($medidaValoracion->estado == "Programada"){
                        $medidaPrincipal = $medidaValoracion;
                    }else{
                        $medidaPrincipal = $medidas->first();
                    }
                    $peligro = Peligro::find($medidaValoracion->peligro_id);
                    $peligro->capacitacionesValoracion()->attach($medidaPrincipal->id);
                    if($medidaValoracion->id !== $medidaPrincipal->id){
                        $medidaValoracion->delete();
                    }
                }
                
                
                foreach($medidasDisponibles as $medidaDisponible){
                    if($medidasDisponibles->count() > 1){
                        $medidaDisponible->delete();
                    }
                }
                
                $medidasDisponibles->first()->capacitaciones_valoracione_id = $medidaPrincipal->id;
                $medidasDisponibles->first()->save();
            break;
            case("Inspecciones"):
                $medidas = InspeccionesValoracione::where('nombre',$data["nombre"])->get();
                $medidasDisponibles = InspeccionesDisponible::where('nombre',$data["nombre"])->get();
                foreach($medidas as $medidaValoracion){
                    if($medidaValoracion->estado == "Programada"){
                        $medidaPrincipal = $medidaValoracion;
                    }else{
                        $medidaPrincipal = $medidas->first();
                    }
                    $peligro = Peligro::find($medidaValoracion->peligro_id);
                    $peligro->inspeccionesValoracion()->attach($medidaPrincipal->id);
                    if($medidaValoracion->id !== $medidaPrincipal->id){
                        $medidaValoracion->delete();
                    }
                }
                
                
                foreach($medidasDisponibles as $medidaDisponible){
                    if($medidasDisponibles->count() > 1){
                        $medidaDisponible->delete();
                    }
                }
                
                $medidasDisponibles->first()->inspecciones_valoracione_id = $medidaPrincipal->id;
                $medidasDisponibles->first()->save();
            break;
        }
        
        return back();
    }
}
