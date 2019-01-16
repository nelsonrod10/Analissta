<?php

namespace App\Http\Controllers;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\Request;
use App\EmpresaCliente;
use App\Empleado;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmpleadosCargueMasivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('analissta.Empleados.CargueMasivo.index');
    }
    
    public function subirArchivo(Request $request){
        $data = $request->validate([
            'file' => 'required|file'
        ]);
        
        $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
        
        $data['file']->storeAs($empresa->nit.'/Empleados', "BaseDatosEmpleado.xlsx");
        
        return redirect()->back();
    }
    
    public function eliminarArchivo(Request $request){
        $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
        $archivo = storage_path("app/$empresa->nit/Empleados/BaseDatosEmpleado.xlsx");
        unlink($archivo);
        
        return redirect()->back();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
        
        if(Storage::exists($empresa->nit.'/Empleados/BaseDatosEmpleado.xlsx')){
            $archivo = storage_path("app/$empresa->nit/Empleados/BaseDatosEmpleado.xlsx");
        }
        if(Storage::exists($empresa->nit.'/Empleados/BaseDatosEmpleado.xls')){
            $archivo = storage_path("app/$empresa->nit/Empleados/BaseDatosEmpleado.xls");
        }
        
        $data= (new FastExcel)->import($archivo)->toArray();
        foreach ($data as $value) {
            Validator::make($value, [
                'Identificacion' => 'required|unique:empleados,identificacion',
                'Email' => 'required|email|unique:empleados,email',
            ],[
                'required' => 'Verificar los campos obligatorios',
                'unique' => 'Verificar números de identificación y correo, algunos de ellos ya existen',
                'email' => 'Verficar formatos de email, deben ser de tipo empleado@empresa.com'
            ])->validate();
            
            Empleado::updateOrCreate([
                'empresaCliente_id'     => session('idEmpresaCliente'),
                'centrosTrabajos_id'    => $empresa->centrosTrabajo->first()->id,
                'nombre'                => $value['Nombres'],
                'apellidos'             => $value['Apellidos'],
                'identificacion'        => $value['Identificacion'],
                'fechaNacimiento'       => $value['FechaNacimiento'],
                'genero'                => $value['Genero'],
                'cargo'                 => $value['Cargo'],
                'salarioMes'            => $value['SalarioMensual'],
                'email'                 => $value['Email'],
                'telefono'              => $value['Telefono'],
                
                'estadoCivil'           => $value['EstadoCivil'],
                'personasAcargo'        => $value['PersonasAcargo'],
                'escolaridad'           => $value['Escolaridad'],
                'tipoVivienda'          => $value['TipoVivienda'],
                'tiempoLibre'           => $value['TiempoLibre'],
                'antiguedadEmpresa'     => $value['AntiguedadEmpresa'],
                'antiguedadCargo'       => $value['AntiguedadCargo'],
                'tipoContrato'          => $value['TipoContrato'],
                'diagnosticoEnfermedad' => $value['Enfermedades'],
                'fumador'               => $value['Fumador'],
                'consumoAlcohol'        => $value['ConsumoAlcohol'],
                'deportista'            => $value['Deportista'],
                'firmaConsentimiento'   => $value['Consentimiento'],
            ]);
        }
        
        unlink($archivo);
        
        /*(new FastExcel)->import($archivo, function ($line) {
            $empresa = EmpresaCliente::find(session('idEmpresaCliente'));
            Empleado::updateOrCreate([
                'empresaCliente_id'     => session('idEmpresaCliente'),
                'centrosTrabajos_id'    => $empresa->centrosTrabajo->first()->id,
                'nombre'                => $line['Nombres'],
                'apellidos'             => $line['Apellidos'],
                'identificacion'        => $line['Identificacion'],
                'fechaNacimiento'       => $line['FechaNacimiento'],
                'genero'                => $line['Genero'],
                'cargo'                 => $line['Cargo'],
                'salarioMes'            => $line['SalarioMensual'],
                'email'                 => $line['Email'],
                'telefono'              => $line['Telefono'],
            ]);
        });*/
        
        return redirect()->route('mostrar-empleados');
        
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
