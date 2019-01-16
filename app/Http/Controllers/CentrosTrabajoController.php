<?php

namespace App\Http\Controllers;
use App\Http\Controllers\EmpresaClienteController;
use App\CentrosTrabajo;
use App\EmpresaCliente;
use App\Sistema;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CentrosTrabajoController extends Controller
{
    public function crearCentrosTrabajo($origen){
        $data = request()->validate([
            'nombCentro'    => 'string|required',
            'empleados'     => 'integer|required|min:1',
            'riesgo'        => 'string|required|min:1|max:5',
            'ciudad'        => 'string|required',
            'direccion'     => 'string|required',
            'telefono'      => 'string|required',
        ],[
            'nombCentro.required'   => 'Debe ingresar un nombre para el centro',
            'empleados.required'    => 'Debe indicar cuantos empleados tiene el centro',
            'riesgo.required'       => 'Seleccione el nivel de riesgo del centro',
            'ciudad.required'       => 'Debe ingresar la ciudad del centro',
            'direccion.required'    => 'Indique la dirección del centro',
            'telefono.required'     => 'Ingrese el teléfono del centro',
            
            'nombCentro.unique'     => 'El nombre del Centro ya existe',
            'riesgo.min'            => 'El nivel de riesgo debe ser mínimo 1',
            'riesgo.max'            => 'El nivel de riesgo debe ser maximo 5',
        ]);
        
        $newCentro = CentrosTrabajo::create([
            'empresaCliente_id'     =>  session('idEmpresaCliente'),
            'nombre'                =>  $data["nombCentro"],
            'totalEmpleados'        =>  $data["empleados"],
            'nivelRiesgo'           =>  $data["riesgo"],
            'ciudad'                =>  $data["ciudad"],
            'direccion'             =>  $data["direccion"],
            'telefono'              =>  $data["telefono"],
        ]);
        
        if($origen == 'ver-empresa-cliente'){
            $this->crearSistemaNuevoCentro($newCentro);
        }
        
        
        return redirect()->route($origen,['id'=>session('idEmpresaCliente')]);
    }
    
    private function crearSistemaNuevoCentro($centro){
        $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
        if($empresa->tipoValoracion === "Matriz General"){
            $sistema = $empresa->sistemaGestion;
            $sistema[0]->centrosTrabajo()->attach($centro);
        }else{
            $newSistema = Sistema::create([
                'empresaCliente_id' => session('idEmpresaCliente')
            ]);
            $newSistema->centrosTrabajo()->attach($centro);
            EmpresaClienteController::finalizarCrearNuevoCentroTrabajo($newSistema->id);
        }
    }
    
    public function eliminarCentrosTrabajo($id){
        $centro = CentrosTrabajo::find($id);
        $centro->delete();
        return redirect()->route('centros-trabajo');
    }
    
    public function getUpdateDatosCentro($id){
        $centro = CentrosTrabajo::find($id);
        return view('analissta.Sistema.updateDatosCentros')->with(['centro'=>$centro]);
    }
    
    public function updateDatosCentro($id){
        $data = request()->validate([
            'nombre'        => 'string|required',
            'empleados'     => 'integer|required|min:1',
            'ciudad'        => 'string|required',
            'direccion'     => 'string|required',
            'telefono'      => 'string|required',
        ],[
            'nombre.required'       => 'Debe ingresar un nombre para el centro',
            'empleados.required'    => 'Debe indicar cuantos empleados tiene el centro',
            'ciudad.required'       => 'Debe ingresar la ciudad del centro',
            'direccion.required'    => 'Indique la dirección del centro',
            'telefono.required'     => 'Ingrese el teléfono del centro',
        ]);
        
        $centro = CentrosTrabajo::find($id);
        
        $centro->nombre                =  $data["nombre"];
        $centro->totalEmpleados        =  $data["empleados"];
        $centro->ciudad                =  $data["ciudad"];
        $centro->direccion             =  $data["direccion"];
        $centro->telefono              =  $data["telefono"];
        $centro->save();
        //
        return view('analissta.empresa');
    }
    
    public function getFrmNuevoCentro(){
        return view('analissta.Sistema.frmCrearCentroTrabajo');
    }
}
