<?php

namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\Http\Controllers\EmpresaClienteController;
use App\EmpresaCliente;
use App\Sistema;
class SistemaController extends Controller
{
    public function getProcesosActividades(Sistema $sistema){
        if (session('sistema')!==null) {
            session()->forget('sistema');
        }
        session(['sistema'=>$sistema]);
        return view('analissta.ProcesosActividades.procesosActividades');
    }
    
    public function crearSistema(EmpresaCliente $empresa, $tipoValoracion){
        $this->eliminarSistema($empresa->id);
        
        if($tipoValoracion === "Matriz General"){
            $this->crearMatrizGeneral($empresa);
        }else{
            $this->crearMatrizPorCentro($empresa);
        }
        
        
    }
    
    public function eliminarSistema($idEmpresa){
        
        $sistemas = Sistema::where('empresaCliente_id',$idEmpresa)
                ->get();
        foreach($sistemas as $sistema){
            $sistema->centrosTrabajo()->detach();
        }
        Sistema::where('empresaCliente_id',$idEmpresa)
            ->delete();
        
        return;
    }
    
    private function crearMatrizGeneral(EmpresaCliente $empresa){
        $newSistema = Sistema::create([
           'empresaCliente_id' => $empresa->id
        ]);
        
        foreach($empresa->centrosTrabajo as $centro){
            $newSistema->centrosTrabajo()->attach($centro);
        }
    }
    
    private function crearMatrizPorCentro(EmpresaCliente $empresa){
        foreach($empresa->centrosTrabajo as $centro){
            $newSistema = Sistema::create([
                'empresaCliente_id' => $empresa->id
            ]);
            $newSistema->centrosTrabajo()->attach($centro);
        }
    }
    
    public static function getArraySistemas(){
        $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
        return $empresa->sistemaGestion;
        
    }
}
