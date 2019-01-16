<?php

namespace App\Http\Controllers;
use App\Http\Controllers\helpers;
use App\Empleado;
use App\EmpresaCliente;
use App\User;
use App\Usuario;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function getfrmAgregarUsuario() {
        
        
        return view('analissta.Usuarios.frmAgregarUsuario');
        
    }
    
    public function crearNuevoUsuario(){
        $data = request()->validate([
            'nombres'       => 'string|required',
            'apellidos'     => 'string|required',
            'identificacion'=> 'string|required', // va a ser el password Inicial
            'email'         => 'email|required|unique:users',
            'telefono'      => 'string|required',
            'roleUsuario'   => 'string|required', //|enum:[Administrador, Digitador]
        ],[
            'nombres.required'          => 'Debe ingresar los nombres del empleado',
            'apellidos.required'        => 'Debe ingresar los apellidos del empleado',
            'identificacion.required'   => 'Debe ingresar el número de identificación del empleado',
            'email.required'            => 'Debe ingresar un email',
            'telefono.required'         => 'Debe ingresar un número de teléfono',
            
            'email.unique'          => 'Ya existe un usuario con este email',
        ]);
        $empleado = Empleado::where('identificacion', $data["identificacion"])->first();
        
        
        $newUser = User::create([
            'name'      => $data['nombres'],
            'lastname'  => $data['apellidos'],
            'email'     => $data['email'],
            'role'      => 'Usuario',
            'password'  => bcrypt($data['identificacion']),
        ]);
        
        Usuario::create([
            'empresaCliente_id'     => session('idEmpresaCliente'),
            'user_id'               => $newUser->id,
            'empleados_id'          => $empleado->id,
            'role_usuario'          => $data['roleUsuario'],
        ]);
        return redirect()->route('ver-empresa-cliente',['id'=>session('idEmpresaCliente')]);
    }
    
    public function eliminarUsuario($id){
        $usuario = Usuario::find($id);
        $user = User::find($usuario->user_id);
        $user->delete();
        $usuario->delete();
        return redirect()->route('ver-empresa-cliente',['id'=>session('idEmpresaCliente')]);
    }
}
