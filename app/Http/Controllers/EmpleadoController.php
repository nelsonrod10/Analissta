<?php

namespace App\Http\Controllers;
use App\Empleado;
use App\Usuario;
use App\User;
use App\EmpresaCliente;
use Illuminate\Http\Request;
use App\Http\Controllers\helpers;
use App\Http\Controllers\Empleados\EvaluacionesMedicasController;

class EmpleadoController extends Controller
{
    public function cargarBaseDatosEmpleados(){
        
        return view('analissta.Empleados.empleados');
    }
    
    public function buscarEmpleado($q){
        $empleadosEmpresa = Empleado::where('empresaCliente_id',session('idEmpresaCliente'))->get();
        $count=0;
        foreach ($empleadosEmpresa as $empleado){
            if (stristr(substr($empleado->identificacion, 0, strlen($q)),$q) || stristr(substr($empleado->nombre, 0, strlen($q)),$q) || stristr(substr($empleado->apellidos, 0, strlen($q)),$q)) {
                if($count <=9){
                    $stringRta = "<option class='seleccionar-empleado' value='{$empleado->identificacion}'>{$empleado->nombre} {$empleado->apellidos}<option/>";
                }
                $count++;
            }
        }
        
        
        return $stringRta;
    }
    
    public function cargarDatosEmpleado($id){
        $empleado = Empleado::where(['identificacion'=>$id,'empresaCliente_id'=>session('idEmpresaCliente')])->get();
        return response()->json([
            'empleado' => $empleado[0],
        ]);
        //return view()->with(['datosEmpleado'=>$datosEmpleado]);
    }
    
    public function getfrmAgregarEmpleado($origen,$idOrigen=null) {
        
        return view('analissta.Empleados.frmAgregarEmpleado')->with(['origen'=>$origen,'idOrigen'=>$idOrigen]);
    }
    
    public function getfrmEditarDatosEmpleado(Request $request,$origen) {
        $data = $request->validate([
            'idEmpleado' => 'integer'
        ]);
        $empleado = Empleado::find($data['idEmpleado']);
        
        return view('analissta.Empleados.frmAgregarEmpleado')->with(['origen'=>$origen,'empleado'=>$empleado,'idOrigen'=>null]);
    }
    
    public function agregarNuevoEmpleado($id,$idOrigen=null){
        if($id === "0"){
            $routePaginaOrigen = $this->crearNuevoEmpleado();
        }else{
            $routePaginaOrigen = $this->updateDatosEmpleado($id);
        }
        return redirect()->route($routePaginaOrigen,['id'=>$idOrigen]);
    }
    
    public function validateDataEmpleado($tipoValoracion){
        $strUniqueId = $strUniqueEmail = '';
        if($tipoValoracion === "Create"){
            $strUniqueId = '|unique:empleados,identificacion';
            $strUniqueEmail = '|unique:empleados,email';
        }
        
        $data = request()->validate([
            'pagina'        => 'string|required',
            'nombres'       => 'string|required',
            'apellidos'     => 'string|required',
            'id'            => 'string|required'.$strUniqueId,
            'nacimiento'    => 'date|required',
            'genero'        => 'string|required',
            'ingreso'       => 'date|required',
            'centroTrabajo' => 'string|required',
            'cargo'         => 'string|required',
            'salario'       => 'string|required',
            'email'         => 'email|required'.$strUniqueEmail,
            'telefono'      => 'string|required',
        ],[
            'nombres.required'      => 'Debe ingresar los nombres del empleado',
            'apellidos.required'    => 'Debe ingresar los apellidos del empleado',
            'id.required'           => 'Debe ingresar el número de identificación del empleado',
            'nacimiento.required'   => 'Debe ingresar la fecha de nacimiento',
            'genero.required'       => 'Debe seleccionar un genero',
            'centroTrabajo.required'=> 'Debe seleccionar un centro de trabajo',
            'cargo.required'        => 'Debe ingresar el nombre del cargo del empleado',
            'salario.required'      => 'Debe indicar el salario mensual del empleado',
            'email.required'        => 'Debe ingresar un email',
            'telefono.required'     => 'Debe ingresar un número de teléfono',
            'ingreso.required'      => 'Debe indicar la fecha de ingreso',
            'id.unique'             => 'Ya existe un empleado con este numero de identificación',
            'email.unique'          => 'Ya existe un empleado con este email',
        ]);
        
        return $data;
    }
    
    public function crearNuevoEmpleado(){
        $data = $this->validateDataEmpleado("Create");
        $empleado = Empleado::create([
            'empresaCliente_id'     => session('idEmpresaCliente'),
            'centrosTrabajos_id'    => $data['centroTrabajo'],
            'nombre'                => $data['nombres'],
            'apellidos'             => $data['apellidos'],
            'identificacion'        => $data['id'],
            'fechaNacimiento'       => $data['nacimiento'],
            'genero'                => $data['genero'],
            'cargo'                 => $data['cargo'],
            'salarioMes'            => $data['salario'],
            'email'                 => $data['email'],
            'telefono'              => $data['telefono'],
            'fecha_ingreso'         => $data['ingreso'],
            'antiguedadEmpresa'     => $this->antiguedadEmpresa($data['ingreso']),
            'antiguedadCargo'       => $this->antiguedadEmpresa($data['ingreso']),
        ]);
        $this->crearEvaluacionMedica($empleado,$data['ingreso']);
        
        return $data['pagina'];
    }
    
    public function updateDatosEmpleado($idEmpleado){
        
        $data = $this->validateDataEmpleado("Update");

        $empleado = Empleado::find($idEmpleado);
        $empleado->centrosTrabajos_id    =  (int)$data["centroTrabajo"];
        $empleado->cargo                 =  $data["cargo"];
        $empleado->salarioMes            =  $data["salario"];
        $empleado->telefono              =  $data["telefono"];
        $empleado->fecha_ingreso         =  $data["ingreso"];
        $empleado->antiguedadEmpresa     =  $this->antiguedadEmpresa($data['ingreso']);
        $empleado->antiguedadCargo       =  $this->antiguedadEmpresa($data['ingreso']);
        $empleado->save();
        
        $this->crearEvaluacionMedica($empleado,$data['ingreso']);
        
        return $data['pagina'];
    }
    
    
    private function antiguedadEmpresa($fecha){
        $antiguedad = helpers::calcularDiferenciaEnAnios($fecha);
        switch ($antiguedad) {
            case ((int)$antiguedad >= 1 && (int)$antiguedad < 5):
                $valor = "De 1 a 5";
                break;
            case ((int)$antiguedad >= 5 && (int)$antiguedad < 10):
                $valor = "De 5 a 10";
                break;
            case ((int)$antiguedad >= 10 && (int)$antiguedad < 15):
                $valor = "De 10 a 15";
                break;
            case ((int)$antiguedad >=15):
                $valor = "Mas de 15";
                break;
            default:
                $valor = "Menos de 1";
                break;
        }
        
        return $valor;
    }
    
    private function crearEvaluacionMedica(Empleado $empleado, $fecha){
        $anio = helpers::getCurrentYear();
        if((string)$empleado->antiguedadEmpresa === "Menos de 1"){
            $anio = (int)helpers::getAnioFecha($fecha)+1;
        }
        $controladorEvaluaciones = new EvaluacionesMedicasController();

        $controladorEvaluaciones->store(new Request([
            'empresa'   => (string)$empleado->empresa->id,
            'empleado'  => (string)$empleado->id,
            'anio'      => (string)$anio,
            'mes'       => helpers::getMesFecha($fecha),
            'dia'       => helpers::getDiaFecha($fecha),
        ]));
        
        
        
        return;
    }
    
    public function cargarFrmSocioDemografico($id){
        $empleado = Empleado::find($id);
        return view('analissta.Empleados.frmDemograficoEmpleado')->with(['empleado'=>$empleado]);
    }
    
    public function guardarPerfilSocioDemografico($id){
        $data = request()->validate([
            'estadoCivil'              => 'string|required',
            'personasAcargo'           => 'string|required',
            'escolaridad'              => 'string|required',
            'tipoVivienda'             => 'string|required',
            'tiempoLibre'              => 'string|required',
            //'antiguedadEmpresa'        => 'string|required',
            'antiguedadCargo'          => 'string|required',
            'tipoContrato'             => 'string|required',
            'diagnosticoEnfermedad'    => 'string|required',
            'fumador'                  => 'string|required',
            'consumoAlcohol'           => 'string|required',
            'deportista'               => 'string|required',
            'firmaConsentimiento'      => 'string|required',
        ],[
            'estadoCivil.required'      => 'Indique el estado civil del empleado',
            'personasAcargo.required'    => 'Indique número de personas a cargo',
            'escolaridad.required'           => 'Indique nivel de escolaridad',
            'tipoVivienda.required'   => 'Indique el tipo de vivienda',
            'tiempoLibre.required'       => 'Indique a que dedica el tiempo libre',
            //'antiguedadEmpresa.required'=> 'Indique antiguedad en la empresa',
            'antiguedadCargo.required'        => 'Indique antiguedad en el cargo',
            'tipoContrato.required'      => 'Indique que tipo de contrato tiene el empleado',
            'diagnosticoEnfermedad.required'        => 'Indique si el empleado ha tenido alguna enfermedad',
            'fumador.required'     => 'Indique si el empleado es fumador',
            'consumoAlcohol.required'             => 'Indique frecuencia de consumo del alcohol',
            'deportista.required'          => 'Indique frecuenda de ejercicio del empleado',
            'firmaConsentimiento.required'          => 'Indique firma de consentimiento del empleado',
        ]);
        
        $empleado = Empleado::find($id);
        $empleado->estadoCivil              =  $data["estadoCivil"];
        $empleado->personasAcargo           =  $data["personasAcargo"];
        $empleado->escolaridad              =  $data["escolaridad"];
        $empleado->tipoVivienda             =  $data["tipoVivienda"];
        $empleado->tiempoLibre              =  $data["tiempoLibre"];
        //$empleado->antiguedadEmpresa        =  $data["antiguedadEmpresa"];
        $empleado->antiguedadCargo          =  $data["antiguedadCargo"];
        $empleado->tipoContrato             =  $data["tipoContrato"];
        $empleado->diagnosticoEnfermedad    =  $data["diagnosticoEnfermedad"];
        $empleado->fumador                  =  $data["fumador"];
        $empleado->consumoAlcohol           =  $data["consumoAlcohol"];
        $empleado->deportista               =  $data["deportista"];
        $empleado->firmaConsentimiento      =  $data["firmaConsentimiento"];
        $empleado->save();
        
        return redirect()->route('mostrar-empleados');
    }
    
    public function eliminarEmpleado($id){
        $empleado = Empleado::find($id);
        if($empleado->usuarios->count() > 0){
            $user = User::find($empleado->usuarios()->first()->user_id);
            $empleado->usuarios()->first()->delete();
            $user->delete();
        }
        $empleado->delete();
        return redirect()->route('mostrar-empleados');
    }
    
    public function cambiarCentroTrabajo(Empleado $empleado){
        $data = request()->validate([
            'centro'    => 'string|required',
        ],[
            'centro.required'    => 'Indique el centro de trabajo del empleado',
            'centro.string'      => 'Verifique los datos enviados',
        ]);
        
        $empleado->update([
            'centrosTrabajos_id'  => $data['centro']
            ]
        );
                
        return redirect()->back();        
    }
    
    public function actualizarFechaIngreso(Empleado $empleado){
        $data = request()->validate([
            'ingreso' => 'date|required',
        ],[
            'ingreso.required'  => 'Indique la fecha de ingreso',
            'ingreso.date'      => 'Verifique los datos enviados',
        ]);
        
        $empleado->update([
            'fecha_ingreso'         => $data['ingreso'],
            'antiguedadEmpresa'     => $this->antiguedadEmpresa($data['ingreso']),
            'antiguedadCargo'       => $this->antiguedadEmpresa($data['ingreso']),
            ]
        );
        
        $this->crearEvaluacionMedica($empleado,$data['ingreso']);
        
        return redirect()->back();        
    }
    
}
