<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultadosMensualesSistema extends Model
{
     protected $guarded = [];
    public function empresaCliente(){
        return $this->belongsTo('App\EmpresaCliente','empresaCliente_id');
    }
}
