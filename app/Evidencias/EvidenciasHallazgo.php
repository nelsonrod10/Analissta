<?php

namespace App\Evidencias;

use App\Evidencias\EvidenciasInterface;
use App\EmpresaCliente;

class EvidenciasHallazgo implements EvidenciasInterface
{
    public $file;
    
    public function __construct($file) {
        $this->file = $file;
    }
    public function subir() {
        $empresa = EmpresaCliente::find(session('sistema')->empresaCliente_id)->first();
        return $this->file->storeAs($empresa->nit.'/Hallazgos', $this->file->getClientOriginalName());
        
    }

//
}
