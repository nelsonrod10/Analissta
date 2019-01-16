<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmpresaCliente;
use App\Http\Controllers\helpers;
use App\Http\Controllers\Sistema\SistemaController;
use App\ActividadesObligatoriasSugerida;
use App\CapacitacionesObligatoriasSugerida;
use App\InspeccionesObligatoriasSugerida;
use App\Sistema;
use Auth;

class EmpresaClienteController extends Controller
{
    public function crearContextoGeneral(){
        if (session('idEmpresaCliente')!==null) {
            $this->updateContextoGeneral();
        }else{
            $this->createContextoGeneral();
        }
        return redirect()->route('empleados-jornada');
    }
    
    private function createContextoGeneral(){
        $data = $this->validateContextoGeneral("Create");
        $empresaCreada = EmpresaCliente::create([
            'asesor_id'             =>  Auth::user()->id,
            'nombre'                =>  $data["nombEmpresa"],
            'nit'                   =>  $data["nitEmpresa"],
            'telefono'              =>  $data["telEmpresa"],
            'direccion'             =>  $data["dirEmpresa"],
            'ciudad'                =>  $data["ciudadEmpresa"],
            'fechaFundacion'        =>  $data["fechaEmpresa"],
            'ciiu'                  =>  $data["ciiuEmpresa"],
            'activEconomica'        =>  $data["activEmpresa"],
            'ARL'                   =>  '',
            'fechaAfiliacionARL'    =>  $data["fechaEmpresa"],
            'cajaCompensacion'      =>  '',
            'fechaAfiliacionCajaComp' => $data["fechaEmpresa"],
            'sector'                =>  $data["sectorEmpresa"],
            'descActivEconomica'    =>  $data["descActividad"],
            'totalEmpleados'        =>  $data["empleadosEmpresa"],
        ]);
        session(['idEmpresaCliente'=>$empresaCreada->id]);
    }
    
    private function updateContextoGeneral(){
        $data = $this->validateContextoGeneral("Update");
        $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
        $empresa->nombre                =  $data["nombEmpresa"];
        $empresa->nit                   =  $data["nitEmpresa"];
        $empresa->telefono              =  $data["telEmpresa"];
        $empresa->direccion             =  $data["dirEmpresa"];
        $empresa->ciudad                =  $data["ciudadEmpresa"];
        $empresa->fechaFundacion        =  $data["fechaEmpresa"];
        $empresa->ciiu                  =  $data["ciiuEmpresa"];
        $empresa->activEconomica        =  $data["activEmpresa"];
        $empresa->fechaAfiliacionARL    =  $data["fechaEmpresa"];
        $empresa->fechaAfiliacionCajaComp = $data["fechaEmpresa"];
        $empresa->sector                =  $data["sectorEmpresa"];
        $empresa->descActivEconomica    =  $data["descActividad"];
        $empresa->totalEmpleados        =  $data["empleadosEmpresa"];
        $empresa->save();
    }
    
    private function validateContextoGeneral($tipoValoracion){
        $strUniqueNombre = $strUniqueNit = $strUniqueDir='';
        if($tipoValoracion === "Create"){
            $strUniqueNombre = 'unique:empresa_clientes,nombre';
            $strUniqueNit = 'unique:empresa_clientes,nit';
            $strUniqueDir = 'unique:empresa_clientes,direccion';
        }
        $data = request()->validate([
            'nombEmpresa' => 'string|required|'.$strUniqueNombre,
            'nitEmpresa' => 'integer|required|'.$strUniqueNit,
            'telEmpresa' => 'string|required',
            'dirEmpresa' => 'string|required|'.$strUniqueDir,
            'ciudadEmpresa' => 'string|required',
            'fechaEmpresa' => 'date|required',
            'ciiuEmpresa' => 'string|required',
            'activEmpresa' => 'string|required',
            'sectorEmpresa' => 'string|required',
            'descActividad' => 'string|required',
            'empleadosEmpresa' => 'integer|required|min:1',
            
        ],[
            'nombEmpresa.required' => 'Debe ingresar un el Nombre de la empresa',
            'nitEmpresa.required' => 'Debe ingresar un NIT',
            'telEmpresa.required' => 'Debe ingresar un Teléfono',
            'dirEmpresa.required' => 'Debe ingresar una Dirección',
            'ciudadEmpresa.required' => 'Debe ingresar una ciudad',
            'fechaEmpresa.required' => 'Debe ingresar una fecha de fundación',
            'ciiuEmpresa.required' => 'Debe ingresar un CIIU (Cámara de Comercio)',
            'activEmpresa.required' => 'Debe indicar la Actividad Económica de la empresa',
            'sectorEmpresa.required' => 'Debe indicar el que sector económico de la empresa',
            'descActividad.required' => 'Debe describir la actividad económica',
            'empleadosEmpresa.required' => 'Debe indicar el TOTAL de empleados',
            
            'nombEmpresa.unique' => 'El nombre de la Empresa ya existe',
            'nitEmpresa.unique' => 'El NIT ingresado ya existe',
            'dirEmpresa.unique' => 'Esta Dirección ya existe',
            
        ]);
        
        return $data;
    }
    
    public function deleteCrearEmpresaCliente(){
        if (session('idEmpresaCliente')!==null) {
            $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
            $empresa->delete();
            EmpresaClienteController::unsetSessionVariables();
        }
        
        return redirect()->route('inicio');
    }
    
    public function crearEmpleadosJornada(){
        $data = request()->validate([
            'directos' => 'integer|required|min:0',
            'temporales' => 'integer|required|min:0',
            'servicios' => 'integer|required|min:0',
            'jornada' => 'required',
            'inicio' => 'required',
            'salida' => 'required',
            'almuerzo' => 'required',
        ],[
            'directos.required' => 'Debe indicar cuantos empleados directos tiene la empresa',
            'temporales.required' => 'Debe indicar cuantos empleados temporales tiene la empresa',
            'servicios.required' => 'Debe indicar cuantos empleados por prestación de servicios tiene la empresa',
            'jornada.required' => 'Seleccione una jornada de trabajo',
            'inicio.required' => 'Ingrese una hora de llegada',
            'salida.required' => 'Ingrese una hora de salida',
            'almuerzo.required' => 'Seleccione el tiempo de almuerzo',
            
        ]);
        
        $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
        $empresa->totalEmpleadosDirectos        =  $data["directos"];
        $empresa->totalEmpleadosTemporales      =  $data["temporales"];
        $empresa->totalEmpleadosPrestServicios  =  $data["servicios"];
        $empresa->jornadaTrabajo                =  $data["jornada"];
        $empresa->horaLlegada                   = $data["inicio"];
        $empresa->horaSalida                    =  $data["salida"];
        $empresa->horasAlmuerzo                 =  $data["almuerzo"];
        $empresa->save();
        return redirect()->route('centros-trabajo');
    }
    
    public function crearTipoValoracion() {
        $data = request()->validate([
            'tipoValoracion' => 'required',
        ],[
            'tipoValoracion.required' => 'Debe seleccionar un tipo de valoración',
        ]);
        $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
        $empresa->tipoValoracion =  $data["tipoValoracion"];
        $empresa->save();
        
        $sistema = new SistemaController();
        $sistema->crearSistema($empresa,$data["tipoValoracion"]);
        
        return redirect()->route('afiliaciones');
    }
    
    public function crearAfiliaciones() {
        $data = request()->validate([
            'arl' => 'string|required',
            'fechaARL' => 'date|required',
            'ccf' => '',
            'fechaCCF' => '',
        ],[
            'arl.required' => 'Debe indicar la ARL de la empresa',
            'fechaARL.required' => 'Debe indicar la fecha de afiliación a la ARL',
            'fechaARL.date' => 'Revise el formato de fecha de afiliación a la ARL',
        ]);
        $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
        $empresa->ARL =  $data["arl"];
        $empresa->fechaAfiliacionARL =  $data["fechaARL"];
        $empresa->cajaCompensacion =  $data["ccf"];
        $empresa->fechaAfiliacionCajaComp =  $data["fechaCCF"];
        
        $empresa->save();
        
        return redirect()->route('resultados-anteriores');
    }
    
    public static function unsetSessionVariables(){
        if (session('idEmpresaCliente')!==null) {
            session()->forget('idEmpresaCliente');
        }
        
        if (session('sistema')!==null) {
            session()->forget('sistema');
        }
        
    }
    
    public function cargarPaginaEmpresa($id){
        
        if (session('sistema')!==null) {
            session()->forget('sistema');
        }
        
        $empresa = EmpresaCliente::find($id);
        session(['idEmpresaCliente'=>$empresa->id]);
        return view('analissta.empresa');
    }
    
    /*public function paginaPrincipalEmpresa($idUsuario){
        $datosEmpresa = App\Usuario::find($idUsuario)->empresaCliente;
    }*/
    
    public function getUpdateDatosEmpresa() {
        
        
        return view('analissta.Sistema.updateDatosEmpresa');
    }
    
    public function updateDatosEmpresa(){
        $data = request()->validate([
            'telEmpresa' => 'string|required',
            'dirEmpresa' => 'string|required',//|unique:empresa_clientes,direccion
            'ciudadEmpresa' => 'string|required',
            'ciiuEmpresa' => 'string|required',
            'activEmpresa' => 'string|required',
            'sectorEmpresa' => 'string|required',
            'directos' => 'integer|required|min:0',
            'temporales' => 'integer|required|min:0',
            'servicios' => 'integer|required|min:0',
            'jornada' => 'required',
            'inicio' => 'required',
            'salida' => 'required',
            'descActividad' => 'string|required',
            
        ],[
            'telEmpresa.required' => 'Debe ingresar un Teléfono',
            'dirEmpresa.required' => 'Debe ingresar una Dirección',
            'ciudadEmpresa.required' => 'Debe ingresar una ciudad',
            'ciiuEmpresa.required' => 'Debe ingresar un CIIU (Cámara de Comercio)',
            'activEmpresa.required' => 'Debe indicar la Actividad Económica de la empresa',
            'sectorEmpresa.required' => 'Debe indicar el que sector económico de la empresa',
            'directos.required' => 'Debe indicar cuantos empleados directos tiene la empresa',
            'temporales.required' => 'Debe indicar cuantos empleados temporales tiene la empresa',
            'servicios.required' => 'Debe indicar cuantos empleados por prestación de servicios tiene la empresa',
            'jornada.required' => 'Seleccione una jornada de trabajo',
            'inicio.required' => 'Ingrese una hora de llegada',
            'salida.required' => 'Ingrese una hora de salida',
            'descActividad.required' => 'Debe describir la actividad económica',
            
            'dirEmpresa.unique' => 'Esta Dirección ya existe',
            
        ]);
        $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
        $empresa->telefono                      =  $data["telEmpresa"];
        $empresa->direccion                     =  $data["dirEmpresa"];
        $empresa->ciudad                        =  $data["ciudadEmpresa"];
        $empresa->ciiu                          =  $data["ciiuEmpresa"];
        $empresa->activEconomica                =  $data["activEmpresa"];
        $empresa->sector                        =  $data["sectorEmpresa"];
        $empresa->descActivEconomica            =  $data["descActividad"];
        $empresa->totalEmpleados                =  (int)$data["directos"] + (int)$data["temporales"] + (int)$data["servicios"] ;
        $empresa->totalEmpleadosDirectos        =  $data["directos"];
        $empresa->totalEmpleadosTemporales      =  $data["temporales"];
        $empresa->totalEmpleadosPrestServicios  =  $data["servicios"];
        $empresa->jornadaTrabajo                =  $data["jornada"];
        $empresa->horaLlegada                   = $data["inicio"];
        $empresa->horaSalida                    =  $data["salida"];
        
        $empresa->save();
        return $this->cargarPaginaEmpresa(session('idEmpresaCliente'));
    }
    
    public function finalizarCrearEmpresa(){
        $resultados = new ResultadosAnualesController();
        $resultados->crearResultadosCrearEmpresa();
        
        $sistemas = SistemaController::getArraySistemas();
        foreach($sistemas as $sistema){
            $this->crearActividades_Obligatorias_Sugeridas($sistema->id);
            $this->crearCapacitaciones_Obligatorias_Sugeridas($sistema->id);
            $this->crearInspecciones_Obligatorias_Sugeridas($sistema->id);
        }
        
        EmpresaClienteController::unsetSessionVariables();
        return redirect()->route('inicio');
    }
    
    public static function finalizarCrearNuevoCentroTrabajo($sistema_id){
        //El llamado a esta función se hace desde crearSistemaNuevoCentro($centro), en CentrosTrabajoController
        $objeto = new static;
        $objeto->crearActividades_Obligatorias_Sugeridas($sistema_id);
        $objeto->crearCapacitaciones_Obligatorias_Sugeridas($sistema_id);
        $objeto->crearInspecciones_Obligatorias_Sugeridas($sistema_id);
        return;
    }
    private function crearActividades_Obligatorias_Sugeridas($sistema_id){
        $actividades = simplexml_load_file(base_path("archivosXML/PlanAnual/xml_PAT_activObligatoriasYsugeridas.xml"));
        $obligatorias = $actividades->xpath("//listadoActividades/medida[@cumplimiento='obligatorio']/actividades/item");
        foreach ($obligatorias as $value) {
            ActividadesObligatoriasSugerida::create([
                'sistema_id'        => $sistema_id,
                'medida'            => 'obligatoria',
                'nombre'            => $value->nombre
            ]);
        }
        
        $sugeridas = $actividades->xpath("//listadoActividades/medida[@cumplimiento='sugerencia']/actividades/item");
        foreach ($sugeridas as $value) {
            ActividadesObligatoriasSugerida::create([
                'sistema_id'        => $sistema_id,
                'medida'            => 'sugerida',
                'nombre'            => $value->nombre
            ]);
        }
    }
    
    private function crearCapacitaciones_Obligatorias_Sugeridas($sistema_id){
        $capacitaciones = simplexml_load_file(base_path("archivosXML/PlanAnual/xml_PAC_capacObligatoriasYsugeridas.xml"));
        $obligatorias = $capacitaciones->xpath("//listadoCapacitaciones/medida[@cumplimiento='obligatorio']/capacitaciones/item");
        foreach ($obligatorias as $value) {
            CapacitacionesObligatoriasSugerida::create([
                'sistema_id'        => $sistema_id,
                'medida'            => 'obligatoria',
                'nombre'            => $value->nombre
            ]);
        }
        
        $sugeridas = $capacitaciones->xpath("//listadoCapacitaciones/medida[@cumplimiento='sugerencia']/capacitaciones/item");
        foreach ($sugeridas as $value) {
            CapacitacionesObligatoriasSugerida::create([
                'sistema_id'        => $sistema_id,
                'medida'            => 'sugerida',
                'nombre'            => $value->nombre
            ]);
        }
    }
    
    private function crearInspecciones_Obligatorias_Sugeridas($sistema_id){
        $inspecciones = simplexml_load_file(base_path("archivosXML/PlanAnual/xml_PAI_inspecObligatoriasYsugeridas.xml"));
        $obligatorias = $inspecciones->xpath("//listadoInspecciones/medida[@cumplimiento='obligatorio']/inspecciones/item");
        foreach ($obligatorias as $value) {
            InspeccionesObligatoriasSugerida::create([
                'sistema_id'        => $sistema_id,
                'medida'            => 'obligatoria',
                'nombre'            => $value->nombre
            ]);
        }
        
        $sugeridas = $inspecciones->xpath("//listadoInspecciones/medida[@cumplimiento='sugerencia']/inspecciones/item");
        foreach ($sugeridas as $value) {
            InspeccionesObligatoriasSugerida::create([
                'sistema_id'        => $sistema_id,
                'medida'            => 'sugerida',
                'nombre'            => $value->nombre
            ]);
        }
    }
}
