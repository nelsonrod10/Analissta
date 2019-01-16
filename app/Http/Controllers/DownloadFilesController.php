<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadFilesController extends Controller
{
    
    public function index(){
        $files =  Storage::allFiles("public");
        return view('files.index',compact('files'));
    }
    
    public function download(Request $request){
        $data = $request->validate([
           'path' => 'string|required', 
           'file' => 'string|required',
        ],[
           '.required' => 'Diligencie los campos obligatorios',  
           '.string'   => 'Datos invalidos',  
        ]);
        
        $realFile = $data['path'].'/'.$data['file'];
        if(Storage::exists($realFile)){
            return Storage::response($realFile);
        }
        return back();
        
    }
}
