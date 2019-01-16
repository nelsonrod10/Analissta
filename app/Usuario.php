<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $guarded = [];
    /**
     * Obtener los datos de la empresa del Usuario
     * @return type
     */
    public function empresaCliente(){
        return $this->belongsTo('App\EmpresaCliente','empresaCliente_id');
    }
    
    public function empleado(){
        return $this->belongsTo('App\Empleado');
    }
    
    
}
