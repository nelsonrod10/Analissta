<?php

namespace App\Http\Controllers\Valoracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MedidasIntervencion\MedidasIntervencionController;
use App\EmpresaCliente;
use App\Peligro;

class RevaloracionController extends Controller
{
    public function cargarRevaloracion($id){
        $peligro = Peligro::find($id);
        return view('analissta.Valoracion.Revaloracion.revaloracionPeligro')->with(['peligro'=>$peligro]);
    }
    
    public function cargarCriterios($id){
        $peligro = Peligro::find($id);
        return view('analissta.Valoracion.Revaloracion.criteriosRevaloracion')->with(['peligro'=>$peligro]);
    }
    
    public function cargarMedidasIntervencion($id){
        $peligro = Peligro::find($id);
        return view('analissta.Valoracion.Revaloracion.medidasRevaloracion')->with(['peligro'=>$peligro]);
    }
    
    
    public function revaloracionPeligro($idPeligro){
        //$this->eliminar_Corto_y_Largo_Plazo($idPeligro);
        $peligro = Peligro::find($idPeligro);
        if ($peligro->efectoPersona === 'Corto Plazo' || $peligro->efectoPersona === 'Largo Plazo') {
            $this->crearControlesCorto_o_largo_plazo($peligro);
            $this->crearValoracionCorto_o_largo_plazo($peligro);
        }else{
            $this->crearControlesCorto_y_largo_plazo($peligro);
            $this->crearValoracionCorto_y_largo_plazo($peligro);
        }
        
        return redirect()->route('criterios-revaloracion',['id'=>$peligro->id]);
    }
    
    
    private function crearControlesCorto_o_largo_plazo($peligro){
        if($peligro->efectoPersona === "Corto Plazo"){
            $cortoPlazo = new CortoPlazoController();
            $cortoPlazo->eliminar_corto_plazo_revaloracion($peligro->id);
            $cortoPlazo->create_corto_plazo(null, null, null, null,$peligro->id,"revaloracion");
        }else{
            $largoPlazo = new LargoPlazoController();
            $largoPlazo->eliminar_largo_plazo_revaloracion($peligro->id);
            $largoPlazo->create_largo_plazo(null, null, null, null,$peligro->id,"revaloracion");
        }
    }
    
    private function crearControlesCorto_y_largo_plazo($peligro){
        $cortoPlazo = new CortoPlazoController();
        $largoPlazo = new LargoPlazoController();
        $cortoPlazo->eliminar_corto_plazo_revaloracion($peligro->id);
        $largoPlazo->eliminar_largo_plazo_revaloracion($peligro->id);
        $cortoPlazo->create_corto_plazo(null, null, null, null,$peligro->id,"revaloracion");
        $largoPlazo->create_largo_plazo(null, null, null, null,$peligro->id,"revaloracion");
    }
    
    private function crearValoracionCorto_o_largo_plazo($peligro){
        $data = request()->validate([
            'nd'        => 'string|required',
            'ne'        => 'string|required',
            'nc'        => 'string|required',
        ],[
            'nd.required'       => 'Debe seleccionar un Nivel de Deficiencia',
            'ne.required'       => 'Debe seleccionar un Nivel de Exposición',
            'nc.required'       => 'Debe seleccionar un Nivel de Consecuencia',
        ]);
        
        if($peligro->efectoPersona === "Corto Plazo"){
            $cortoPlazo = new CortoPlazoController();
            $cortoPlazo->getObjetoPorRevaloracion($peligro);
            $cortoPlazo->create_valoracion_corto_plazo($data["nd"], $data["ne"], $data["nc"],$peligro->id);
        }else{
            $largoPlazo = new LargoPlazoController();
            $largoPlazo->getObjetoPorRevaloracion($peligro);
            $largoPlazo->create_valoracion_largo_plazo($data["nd"], $data["ne"], $data["nc"],$peligro->id);
        }
    }
    
    private function crearValoracionCorto_y_largo_plazo($peligro){
        $data = request()->validate([
            'NDcp'        => 'string|required',
            'NEcp'        => 'string|required',
            'NCcp'        => 'string|required',
            'NDlp'        => 'string|required',
            'NElp'        => 'string|required',
            'NClp'        => 'string|required',
        ],[
            'NDcp.required'       => 'Debe seleccionar un Nivel de Deficiencia',
            'NEcp.required'       => 'Debe seleccionar un Nivel de Exposición',
            'NCcp.required'       => 'Debe seleccionar un Nivel de Consecuencia',
            'NDlp.required'       => 'Debe seleccionar un Nivel de Deficiencia',
            'NElp.required'       => 'Debe seleccionar un Nivel de Exposición',
            'NClp.required'       => 'Debe seleccionar un Nivel de Consecuencia',
        ]);
        $cortoPlazo = new CortoPlazoController();
        $largoPlazo = new LargoPlazoController();
        $cortoPlazo->getObjetoPorRevaloracion($peligro);
        $largoPlazo->getObjetoPorRevaloracion($peligro);
        $cortoPlazo->create_valoracion_corto_plazo($data["NDcp"], $data["NEcp"], $data["NCcp"],$peligro->id);
        $largoPlazo->create_valoracion_largo_plazo($data["NDlp"], $data["NElp"], $data["NClp"],$peligro->id);
    }
    
    public function criteriosRevaloracion($idPeligro){
        $peligro = Peligro::find($idPeligro);
        
        if ($peligro->efectoPersona === 'Corto Plazo' || $peligro->efectoPersona === 'Largo Plazo') {
            $this->crearCriteriosCorto_o_largo_plazo($peligro);
        }else{
            $this->crearCriteriosCorto_y_largo_plazo($peligro);
        }
        
        return redirect()->route('medidas-intervencion-revaloracion',['id'=>$idPeligro]);
    }
    
    
    private function crearCriteriosCorto_o_largo_plazo($peligro){
        $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
        
        $data = request()->validate([
            'cliente'        => 'integer|required|min:0',
            'contratista'    => 'integer|required|min:0',
            'directos'       => 'integer|required|min:0|max:'.$empresa->totalEmpleados,
            'visitante'      => 'integer|required|min:0',
            'consecuencia'   => 'string|nullable',
            'legal'          => 'string|required',
            'descLegal'      => 'string|nullable',
        ],[
            'cliente.required'      => 'Debe indicar el numero de clientes',
            'contratista.required'  => 'Debe indicar el numero de contratistas',
            'directos.required'     => 'Debe indicar el numero de empleados directos',
            'visitante.required'   => 'Debe indicar el numero de visitantes',
            'directos.max'          => 'El número de empleados directos no debe superar el número '.$empresa->totalEmpleados,
        ]);
        
        if($peligro->efectoPersona === "Corto Plazo"){
            $cortoPlazo = new CortoPlazoController();
            $cortoPlazo->getObjetoPorRevaloracion($peligro);
            $cortoPlazo->create_criterios_corto_plazo($data["cliente"], $data["contratista"], $data["directos"], $data["visitante"], $data["consecuencia"], $data["legal"],$data["descLegal"],$peligro->id);
        }else{
            $largoPlazo = new LargoPlazoController();
            $largoPlazo->getObjetoPorRevaloracion($peligro);
            $largoPlazo->create_criterios_largo_plazo($data["cliente"], $data["contratista"], $data["directos"], $data["visitante"], $data["consecuencia"], $data["legal"],$data["descLegal"],$peligro->id);
        }
    }
    
    private function crearCriteriosCorto_y_largo_plazo($peligro){
        $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
        
        $data = request()->validate([
            'clienteCP'        => 'integer|required|min:0',
            'contratistaCP'    => 'integer|required|min:0',
            'directosCP'       => 'integer|required|min:0|max:'.$empresa->totalEmpleados,
            'visitanteCP'      => 'integer|required|min:0',
            'consecuenciaCP'   => 'string|nullable',
            'legalCP'          => 'string|required',
            'descLegalCP'      => 'string|nullable',
            
            'clienteLP'        => 'integer|required|min:0',
            'contratistaLP'    => 'integer|required|min:0',
            'directosLP'       => 'integer|required|min:0|max:'.$empresa->totalEmpleados,
            'visitanteLP'      => 'integer|required|min:0',
            'consecuenciaLP'   => 'string|nullable',
            'legalLP'          => 'string|required',
            'descLegalLP'      => 'string|nullable',
        ],[
            'clienteCP.required'      => 'Debe indicar el numero de clientes',
            'contratistaCP.required'  => 'Debe indicar el numero de contratistas',
            'directosCP.required'     => 'Debe indicar el numero de empleados directos',
            'visitanteCP.required'   => 'Debe indicar el numero de visitantes',
            'directosCP.max'          => 'El número de empleados directos no debe superar el número '.$empresa->totalEmpleados,
            
            'clienteLP.required'      => 'Debe indicar el numero de clientes',
            'contratistaLP.required'  => 'Debe indicar el numero de contratistas',
            'directosLP.required'     => 'Debe indicar el numero de empleados directos',
            'visitanteLP.required'   => 'Debe indicar el numero de visitantes',
            'directosLP.max'          => 'El número de empleados directos no debe superar el número '.$empresa->totalEmpleados,
        ]);
        $cortoPlazo = new CortoPlazoController();
        $largoPlazo = new LargoPlazoController();
        $cortoPlazo->getObjetoPorRevaloracion($peligro);
        $largoPlazo->getObjetoPorRevaloracion($peligro);
        $cortoPlazo->create_criterios_corto_plazo($data["clienteCP"], $data["contratistaCP"], $data["directosCP"], $data["visitanteCP"], $data["consecuenciaCP"], $data["legalCP"],$data["descLegalCP"],$peligro->id);
        $largoPlazo->create_criterios_largo_plazo($data["clienteLP"], $data["contratistaLP"], $data["directosLP"], $data["visitanteLP"], $data["consecuenciaLP"], $data["legalLP"],$data["descLegalLP"],$peligro->id);
    }
    
    public function medidasIntervencionRevaloracion($idPeligro){
        $peligro = Peligro::find($idPeligro);
        $data = request()->validate([
            'medidasIntervencion'        => 'array|required',
        ]);
        
        foreach ($data['medidasIntervencion'] as $medida) {
            $peligro->$medida = "Programar";
        }
        $peligro->save();
        session(['arrMedidas'=>$data['medidasIntervencion']]);
        return redirect()->route('configurar-medida-intervencion-revaloracion',['idPeligro'=>$peligro,'conteo'=>0]);
    }
    
    
    public function cargarConfigurarMedidaIntervencion($idPeligro,$conteo) {
        $peligro = Peligro::find($idPeligro);
        return view('analissta.Valoracion.Revaloracion.programarMedidaIntervencion')->with(['peligro'=>$peligro,'conteo'=>$conteo]);
    }
    
    public function crearMedidaIntervencion($idPeligro){
        
        $data = request()->validate([
            'flag'      => 'string',
            'medida'    => 'string',
            'tipo'      => 'string',
            'nombre'    => 'string|required'
        ],[
            'nombre.required' => 'Ingrese el nombre de la medida',
            'nombre.string'   => 'Ingrese un nombre válido'  
        ]);
        
        $medida = $data["medida"];
        $peligro = Peligro::find($idPeligro);
        $peligro->$medida = "Programado";
        $peligro->save();
        
        $newMedida = new MedidasIntervencionController();
        $newMedida->crearMedidaIntervencionValoracion($data['nombre'], $data['tipo'], $medida,$data['flag'],$idPeligro);
        return redirect()->route('configurar-medida-intervencion-revaloracion',['idPeligro'=>$idPeligro,'conteo'=>1]);
    }
    
    public function eliminarMedidaIntervencion($idPeligro){
        //idMedida, tipoMedida =>Actividad, Capacitacion, Inspeccion
        $data = request()->validate([
            'id'    => 'string|required',
            'tipo'      => 'string|required',
        ],[
            'id.required' => 'Error, intente de nuevo',
            'tipo.required' => 'Error, intente de nuevo',
        ]
        );
        
        $newMedida = new MedidasIntervencionController();
        $newMedida->eliminarMedidaIntervencionValoracion($data['id'], $data['tipo']);
        return redirect()->route('configurar-medida-intervencion-revaloracion',['idPeligro'=>$idPeligro,'conteo'=>1]);
    }
    
    public function modificarArrayMedidas($idPeligro,$medida){
        //Se realizan los cambios en el array de medidas para avanzar a la siguiente medida
        $arrActual =session('arrMedidas');
        (int)$keyMedida = array_search($medida, $arrActual);
        unset($arrActual[$keyMedida]);
        $newArray=[];
        foreach($arrActual as $valor){
            array_push($newArray, $valor);
        }
        session(['arrMedidas'=>$newArray]);
        
        return redirect()->route('configurar-medida-intervencion-revaloracion',['idPeligro'=>$idPeligro,'conteo'=>0]);
    }
    
    public function finalizarRevaloracion($idPeligro) {
        $peligro = Peligro::find($idPeligro);
        return redirect()->route('detalles-peligro',['idActividad'=>$peligro->actividad->id,'idPeligro'=>$idPeligro]);
    }
    
    public function cancelarRevaloracion($idPeligro) {
        $peligro = Peligro::find($idPeligro);
        /*$cortoPlazo = new CortoPlazoController();
        $largoPlazo = new LargoPlazoController();
        $cortoPlazo->eliminarPGRP();
        $largoPlazo->eliminarPVE();
        RequisitosLegalesController::eliminar_requisitos_legales();
        $peligro = Peligro::find($peligro->id);
        if(isset($peligro->id)){
            $peligro->delete();
        }
        
        PeligroController::unsetSessionVariables();
        MedidasIntervencionController::unsetSessionVariables();*/
        
        return redirect()->route('detalles-peligro',['idActividad'=>$peligro->actividad->id,'idPeligro'=>$idPeligro]);
    }
}
