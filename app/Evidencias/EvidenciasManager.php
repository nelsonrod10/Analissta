<?php

namespace App\Evidencias;
use App\Evidencias\EvidenciasInterface;
use App\EmpresaCliente;
class EvidenciasManager
{
    
    public static function getOrigenEvidencia($origen){
        $arrBusqueda = ["ObligatoriasSugerida","Valoracione"];
        
        return str_replace($arrBusqueda, "", $origen);
    }
    
    public static function subirEvidencia(EvidenciasInterface $Evidencias) {
        
        return $Evidencias->subir();
    }

//
}
