<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evidencia;
use App\Evidencias\EvidenciasManager;
use Illuminate\Support\Facades\Storage;

class EvidenciasController extends Controller
{
    
    public function store(Request $request){
        //Storage::makeDirectory('');
        $data = $request->validate([
            'origen_id' => 'required|string',
            'origen_table' => 'required|string',
            'file' => 'required|file'
        ]);
        
        
        $origenEvidencia = '\App\Evidencias\Evidencias'.EvidenciasManager::getOrigenEvidencia($data["origen_table"]);   
        
        $ruta = EvidenciasManager::subirEvidencia(new $origenEvidencia($data['file']));
        
        Evidencia::create([
            'sistema_id'        => session('sistema')->id,
            'origen_id'         => $data["origen_id"],
            'origen_table'      => $data["origen_table"],
            'evidencia'         => $ruta,
        ]);
        
        return redirect()->back();
        //
    }
    
    public function show($id)
    {   
        $evidencia = Evidencia::find($id);
        return Storage::response($evidencia->evidencia);
    }
    
    
    
    

}