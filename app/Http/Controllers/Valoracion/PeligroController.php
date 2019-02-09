<?php

namespace App\Http\Controllers\Valoracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Valoracion\CortoPlazoController;
use App\Http\Controllers\Valoracion\LargoPlazoController;
use App\Peligro;
use App\EmpresaCliente;
use App\Http\Controllers\MedidasIntervencion\MedidasIntervencionController;


class PeligroController extends Controller
{
    public function buscarDescripcionPeligro($idClasificacion,$idDescripcion,$idSubDesc){
        $xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
        $descripciones = $xml_GTC45->xpath("//peligros/clasificacion[id=$idClasificacion]/listDescripciones/descripcion");
        $result = "";
        foreach ($descripciones as $descripcion) {
            $attrChecked = ($descripcion->id == $idDescripcion)?"checked":"";
            $subdesc=[];
            $classAccion = "mostrar-fuentes-categoria";
            if($descripcion->subDescripcion){
                $subdesc = $descripcion->xpath("subDescripcion");
                $classAccion = "mostrar-subcategoria";
            }
            $result .= "<div class='columns small-12'>"
                    . "<input type='radio' name='descripcion' data-accion='$classAccion' data-clasificacion='$idClasificacion' id='desc-$descripcion->id' value='$descripcion->id' required='true' onchange='mostrarSubDesc_o_Fuentes(this.id)' $attrChecked>"
                    . "<label style='font-size:12px'  for='desc-$descripcion->id'>$descripcion->nombre</label>";
                    
                if(count($subdesc)>0){
                    $result .= "<div class='columns small-12 div-subCategoria hide' id='subCategoria-$descripcion->id' >"
                               ."<div style='text-decoration:underline'><b>Seleccione Subcategoria</b></div>";
                    foreach($subdesc as $subdescripcion){
                      $attrCheckedSub = ($subdescripcion->id == $idSubDesc && $descripcion->id == $idDescripcion)?"checked":"";  
                      $result .=  "<div>"
                              . "<input type='radio' name='subdescripcion' class='input-subCategoria input-subCategoria-descripcion-$descripcion->id' data-accion='mostrar-fuentes-subcategoria' data-clasificacion='$idClasificacion' data-descripcion='$descripcion->id' id='subdesc-$descripcion->id-$subdescripcion->id' value='$subdescripcion->id' onchange='mostrarSubDesc_o_Fuentes(this.id)' $attrCheckedSub />"
                              . "<label style='font-size:12px' for='subdesc-$descripcion->id-$subdescripcion->id'>$subdescripcion->nombre</label>"
                              . "</div>";
                    } 
                }  $result .= "</div>"; 
                    
            $result .= "</div>";
        }
        
        return response()->json([
            'descripciones' => $result
        ]);
    }
    
    public function buscarFuentesPeligro($idClasificacion,$idCategoria,$idSubCategoria,$strFuentes){
        $arrFuentesOld=explode(",",$strFuentes);
        $xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
        if((int)$idSubCategoria === 0){
            $fuentes = $xml_GTC45->xpath("//peligros/clasificacion[id=$idClasificacion]/listDescripciones/descripcion[id=$idCategoria]/fuentes/item");
        }else{
            $fuentes = $xml_GTC45->xpath("//peligros/clasificacion[id=$idClasificacion]/listDescripciones/descripcion[id=$idCategoria]/subDescripcion[id=$idSubCategoria]/fuentes/item");
            
        }
        $result = "";
        foreach ($fuentes as $fuente) {
            $attrChecked = (in_array($fuente->id,$arrFuentesOld)?"checked":"");
            $result .= "<div class='columns small-12'>"
                    . "<input type='checkbox' class='fuentes' id='$fuente->id' name='fuentes[]' value='$fuente->id' $attrChecked />"
                    . "<label for='$fuente->id' style='font-size:12px'>$fuente->nombre</label>"
                    . "</div>";
        }
        
        return response()->json([
            'fuentes' => $result
        ]);
    }
    
    public function guardarIdentificacionPeligro($idActividad){
        if (session('idPeligro')!==null) {
            $idPeligro = $this->updateIdentificacionPeligro();
        }else{
            $idPeligro = $this->createIdentificacionPeligro($idActividad);
            session(['idPeligro'=>$idPeligro]);
        }
        
        return redirect()->route('efectos-peligro',['idActividad'=>$idActividad]);
    }
    
    private function validacionDataIdenficacionPeligro(){
        $data = request()->validate([
            'clasificacion'     => 'integer|min:1|max:10|required',
            'descripcion'       => 'integer|required|min:1',
            'subdescripcion'    => 'integer',
            'fuentes'           => 'array',
            'especificacion'    => 'string|required',
            'factorH'           => 'string|nullable',
        ],[
            'clasificacion.required'   => 'Debe seleccionar una clasificación',
            'descripcion.required'    => 'Debe seleccionar una descripción',
            'especifiacion.required'       => 'Detalle las fuentes que generan el peligro',
            
            'clasificacion.min'            => 'Error al seleccionar la clasificación',
            'clasificacion.max'            => 'Error al seleccionar la clasificación',
            'descripcion.min'            => 'Error al seleccionar la descripción',
        ]);
        return $data;
    }
    
    private function createIdentificacionPeligro($idActividad){
        $data = $this->validacionDataIdenficacionPeligro();
        $peligro = Peligro::create([
            'sistema_id'            =>  session('sistema')->id,
            'actividad_id'          =>  $idActividad,
            'clasificacion'         =>  $data["clasificacion"],
            'categoria'             =>  $data["descripcion"],
            'subCategoria'          =>  isset($data["subdescripcion"])?$data["subdescripcion"]:0,
            'fuentes'               =>  implode(",", $data["fuentes"]),
            'especificacion'        =>  $data["especificacion"],
            'factorHumano'          =>  isset($data["factorH"])?$data["factorH"]:"N/A",
            'efectoPersona'         =>  "N/A",
            'efectoPropiedad'       =>  "N/A",
            'efectoProcesos'        =>  "N/A",
        ]);
        
        return $peligro->id;
    }
    
    private function updateIdentificacionPeligro(){
        $data = $this->validacionDataIdenficacionPeligro();
        $empresa = Peligro::find(session('idPeligro'));
        $empresa->clasificacion         =  $data["clasificacion"];
        $empresa->categoria             =  $data["descripcion"];
        $empresa->subCategoria          =  isset($data["subdescripcion"])?$data["subdescripcion"]:0;
        $empresa->fuentes               =  implode(",", $data["fuentes"]);
        $empresa->especificacion        =  $data["especificacion"];
        $empresa->factorHumano          =  isset($data["factorH"])?$data["factorH"]:"N/A";
        $empresa->save();
    }
    
    public function efectosPeligro($idActividad){
        $data = request()->validate([
            'personas'     => 'string|required',
            'propiedad'    => 'string|nullable',
            'proceso'      => 'string|nullable',
        ],[
            'personas.required'   => 'Debe seleccionar una opción para los efectos en las personas',
        ]);
        
        $peligro = Peligro::find(session('idPeligro'));
        $peligro->efectoPersona     =  $data["personas"];
        $peligro->efectoPropiedad   =  isset($data["propiedad"])?$data["propiedad"]:"N/A";
        $peligro->efectoProcesos    =  isset($data["proceso"])?$data["proceso"]:"N/A";
        $peligro->save();
        $this->eliminar_Corto_y_Largo_Plazo();
        return redirect()->route('controles-peligro',['idActividad'=>$idActividad]);
    }
    
    public function controlesPeligro($idActividad){
        $this->eliminar_Corto_y_Largo_Plazo();
        $peligro = Peligro::find(session('idPeligro'));
        if ($peligro->efectoPersona === 'Corto Plazo' || $peligro->efectoPersona === 'Largo Plazo') {
            $this->crearControlesCorto_o_largo_plazo($peligro);
        }else{
            $this->crearControlesCorto_y_largo_plazo();
        }
        
        return redirect()->route('valoracion-peligro',['idActividad'=>$idActividad]);
    }
    
    private function eliminar_Corto_y_Largo_Plazo(){
        $cortoPlazo = new CortoPlazoController();
        $cortoPlazo->eliminar_corto_plazo();
        $largoPlazo = new LargoPlazoController();
        $largoPlazo->eliminar_largo_plazo();
    }
    
    private function crearControlesCorto_o_largo_plazo($peligro){
        $data = request()->validate([
            'fuente'        => 'string|nullable',
            'medio'         => 'string|nullable',
            'individuo'     => 'string|nullable',
            'admon'         => 'string|nullable',
        ],[
            'fuente.string'     => 'Datos incorrectos para describir los controles en la fuente',
            'medio.string'      => 'Datos incorrectos para describir los controles en el medio',
            'individuo.string'  => 'Datos incorrectos para describir los controles sobre el individuo',
            'admon.string'      => 'Datos incorrectos para describir los controles administrativos',
        ]);
        
        if($peligro->efectoPersona === "Corto Plazo"){
            $cortoPlazo = new CortoPlazoController();
            $cortoPlazo->create_corto_plazo($data["fuente"], $data["medio"], $data["individuo"], $data["admon"],session('idPeligro'));
        }else{
            $largoPlazo = new LargoPlazoController();
            $largoPlazo->create_largo_plazo($data["fuente"], $data["medio"], $data["individuo"], $data["admon"],session('idPeligro'));
        }
    }
    
    private function crearControlesCorto_y_largo_plazo(){
        $data = request()->validate([
            'fuenteCP'      => 'string|nullable',
            'medioCP'       => 'string|nullable',
            'individuoCP'   => 'string|nullable',
            'admonCP'       => 'string|nullable',
            'fuenteLP'      => 'string|nullable',
            'medioLP'       => 'string|nullable',
            'individuoLP'   => 'string|nullable',
            'admonLP'       => 'string|nullable',
        ],[
            'fuenteCP.string'       => 'Datos incorrectos para describir los controles en la fuente',
            'medioCP.string'        => 'Datos incorrectos para describir los controles en el medio',
            'individuoCP.string'    => 'Datos incorrectos para describir los controles sobre el individuo',
            'admonCP.string'        => 'Datos incorrectos para describir los controles administrativos',
            'fuenteLP.string'       => 'Datos incorrectos para describir los controles en la fuente',
            'medioLP.string'        => 'Datos incorrectos para describir los controles en el medio',
            'individuoLP.string'    => 'Datos incorrectos para describir los controles sobre el individuo',
            'admonLP.string'        => 'Datos incorrectos para describir los controles administrativos',
        ]);
        $cortoPlazo = new CortoPlazoController();
        $largoPlazo = new LargoPlazoController();
        $cortoPlazo->create_corto_plazo($data["fuenteCP"], $data["medioCP"], $data["individuoCP"], $data["admonCP"],session('idPeligro'));
        $largoPlazo->create_largo_plazo($data["fuenteLP"], $data["medioLP"], $data["individuoLP"], $data["admonLP"],session('idPeligro'));
    }
    
    public function valoracionPeligro($idActividad){
        $peligro = Peligro::find(session('idPeligro'));
        if ($peligro->efectoPersona === 'Corto Plazo' || $peligro->efectoPersona === 'Largo Plazo') {
            $this->crearValoracionCorto_o_largo_plazo($peligro);
        }else{
            $this->crearValoracionCorto_y_largo_plazo($peligro);
        }
        
        return redirect()->route('criterios-peligro',['idActividad'=>$idActividad]);
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
            $cortoPlazo->getObjetoPorValoracion($peligro);
            $cortoPlazo->eliminarPGRP();
            $cortoPlazo->create_valoracion_corto_plazo($data["nd"], $data["ne"], $data["nc"],session('idPeligro'));
        }else{
            $largoPlazo = new LargoPlazoController();
            $largoPlazo->getObjetoPorValoracion($peligro);
            $largoPlazo->eliminarPVE();
            $largoPlazo->create_valoracion_largo_plazo($data["nd"], $data["ne"], $data["nc"],session('idPeligro'));
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
        
        $cortoPlazo->getObjetoPorValoracion($peligro);
        $largoPlazo->getObjetoPorValoracion($peligro);
        $cortoPlazo->eliminarPGRP();
        $largoPlazo->eliminarPVE();
        $cortoPlazo->create_valoracion_corto_plazo($data["NDcp"], $data["NEcp"], $data["NCcp"],session('idPeligro'));
        $largoPlazo->create_valoracion_largo_plazo($data["NDlp"], $data["NElp"], $data["NClp"],session('idPeligro'));
    }
    
    public function criteriosPeligro($idActividad){
        $peligro = Peligro::find(session('idPeligro'));
        
        if ($peligro->efectoPersona === 'Corto Plazo' || $peligro->efectoPersona === 'Largo Plazo') {
            $this->crearCriteriosCorto_o_largo_plazo($peligro);
        }else{
            $this->crearCriteriosCorto_y_largo_plazo($peligro);
        }
        
        return redirect()->route('medidas-intervencion-peligro',['idActividad'=>$idActividad]);
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
            $cortoPlazo->getObjetoPorValoracion($peligro);
            $cortoPlazo->create_criterios_corto_plazo($data["cliente"], $data["contratista"], $data["directos"], $data["visitante"], $data["consecuencia"], $data["legal"],$data["descLegal"],session('idPeligro'));
        }else{
            $largoPlazo = new LargoPlazoController();
            $largoPlazo->getObjetoPorValoracion($peligro);
            $largoPlazo->create_criterios_largo_plazo($data["cliente"], $data["contratista"], $data["directos"], $data["visitante"], $data["consecuencia"], $data["legal"],$data["descLegal"],session('idPeligro'));
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
        $cortoPlazo->getObjetoPorValoracion($peligro);
        $largoPlazo->getObjetoPorValoracion($peligro);
        $cortoPlazo->create_criterios_corto_plazo($data["clienteCP"], $data["contratistaCP"], $data["directosCP"], $data["visitanteCP"], $data["consecuenciaCP"], $data["legalCP"],$data["descLegalCP"],session('idPeligro'));
        $largoPlazo->create_criterios_largo_plazo($data["clienteLP"], $data["contratistaLP"], $data["directosLP"], $data["visitanteLP"], $data["consecuenciaLP"], $data["legalLP"],$data["descLegalLP"],session('idPeligro'));
    }
    
    public function medidasIntervencionPeligro($idActividad){
        $peligro = Peligro::find(session('idPeligro'));
        $data = request()->validate([
            'medidasIntervencion'        => 'array|required',
        ]);
        
        $peligro->eliminar = 'N/A';
        $peligro->sustituir = 'N/A';
        $peligro->ingenieria = 'N/A';
        $peligro->epp_herramientas = 'N/A';
        $peligro->administrativos = 'N/A';
        
        foreach ($data['medidasIntervencion'] as $medida) {
            $peligro->$medida = "Programar";
        }
        $peligro->save();
        session(['arrMedidas'=>$data['medidasIntervencion']]);
        return redirect()->route('configurar-medida-intervencion',['idActividad'=>$idActividad,'conteo'=>0]);
    }
/*
 * Cuando configurando actividades, capacitaciones o inspecciones dan click en el boton volver a Medidas
 */    
    public function volverMedidasIntervencionPeligro($idActividad){
        //se elimina todas las actividades, capacitaciones o inspecciones que se hayan creado
        $medidasCreadas = new MedidasIntervencionController();
        $medidasCreadas->eliminarTodasMedidasIntervencion();
        $peligro = Peligro::find(session('idPeligro'));
        $peligro->eliminar = 'N/A';
        $peligro->sustituir = 'N/A';
        $peligro->ingenieria = 'N/A';
        $peligro->epp_herramientas = 'N/A';
        $peligro->administrativos = 'N/A';
        $peligro->save();
        return redirect()->route('medidas-intervencion-peligro',['idActividad'=>$idActividad]);
    }
    
    
    
    public function crearMedidaIntervencion($idActividad){
        
        $data = request()->validate([
            'flag'      => 'string',
            'medida'    => 'string',
            'idDisponible'=>'string',
            'tipo'      => 'string',
            'nombre'    => 'string|required'
        ],[
            'nombre.required' => 'Ingrese el nombre de la medida',
            'nombre.string'   => 'Ingrese un nombre válido'  
        ]);
        
        $medida = $data["medida"];
        $peligro = Peligro::find(session('idPeligro'));
        $peligro->$medida = "Programado";
        $peligro->save();
        
        $newMedida = new MedidasIntervencionController();
        if($data['flag'] == "crear-en-disponibles"){
            $newMedida->crearMedidaIntervencionValoracion($data['nombre'], $data['tipo'], $medida,$peligro);
        }
        if($data['flag'] == "copiar-de-disponibles"){
            $newMedida->copiarMedidaDeDisponibles($data['idDisponible'], $data['tipo'], $peligro);
        }
        return redirect()->route('configurar-medida-intervencion',['idActividad'=>$idActividad,'conteo'=>1]);
    }
    
    public function eliminarMedidaIntervencion($idActividad){
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
        return redirect()->route('configurar-medida-intervencion',['idActividad'=>$idActividad,'conteo'=>1]);
    }
    
    
    public function modificarArrayMedidas($idActividad,$medida){
        //Se realizan los cambios en el array de medidas para avanzar a la siguiente medida
        $arrActual =session('arrMedidas');
        (int)$keyMedida = array_search($medida, $arrActual);
        unset($arrActual[$keyMedida]);
        $newArray=[];
        foreach($arrActual as $valor){
            array_push($newArray, $valor);
        }
        session(['arrMedidas'=>$newArray]);
        
        return redirect()->route('configurar-medida-intervencion',['idActividad'=>$idActividad,'conteo'=>0]);
    }
    
    public static function unsetSessionVariables(){
        if (session('idPeligro')!==null) {
            session()->forget('idPeligro');
        }
    }
    
}
