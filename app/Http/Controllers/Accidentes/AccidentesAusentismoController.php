<?php

namespace App\Http\Controllers\Accidentes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\EmpresaCliente;
use App\Accidentes\Accidente;
use App\Accidentes\AccidentesAusentismo;
use App\Ausentismos\Ausentismo;
use App\Http\Controllers\Ausentismos\AusentismosCalculosController;
use DateTime;
use DateInterval;

class AccidentesAusentismoController extends Controller
{
    
    public function crearAusentismo($idAccidente){
        $accidente = Accidente::where('sistema_id',  session('sistema')->id)
                ->where('id',$idAccidente)
                ->get();
        $ausentismo = Ausentismo::create([
            'sistema_id'  => session('sistema')->id,
            'centrosTrabajo_id'  => $accidente[0]->centrosTrabajo_id,
            'clasificacion'      => "Accidente Trabajo",
            'fecha_inicio'       => $accidente[0]->fechaAccidente,
            'hora_inicio'        => $accidente[0]->horaAccidente,
            'dias_totales'       => 0,
            'horas_totales'      => 0,
            'dias_ausentismo'    => 0,
            'horas_ausentismo'   => 0,
            'fecha_regreso'      =>  $accidente[0]->fechaAccidente,
            'hora_regreso'       =>  $accidente[0]->horaAccidente,
            'ausentado_id'       => $accidente[0]->accidentado_id,
            'prorroga'           =>  "Si", // por ser un accidente de Trabajo se pueden crear prorrogas
            'observaciones'      => $accidente[0]->descripcion,
            'codigo_diagnostico' => "",
            'eps'                => "",
        ]);
        
        $this->crearPivotAccidenteAusentismo($accidente[0]->id,$ausentismo->id);
        
        return redirect()->route('datos-generales-ausentismo-accidente',['idAccidente'=>$accidente[0]->id]);
    }
    
    public function datosGenerales($idAccidente){
        
        
        $accidente = Accidente::where('sistema_id',session('sistema')->id)
                ->where('id',$idAccidente)
                ->get();
        return view('analissta.Accidentes.crearAusencia.datosGenerales')->with(['accidente'=>$accidente[0]]);
    }
    
    public function diagnostico($idAccidente){
        
        
        $accidente = Accidente::where('sistema_id',session('sistema')->id)
                ->where('id',$idAccidente)
                ->get();
        return view('analissta.Accidentes.crearAusencia.diagnostico')->with(['accidente'=>$accidente[0]]);
    }
    
    private function crearPivotAccidenteAusentismo($idAccidente,$idAusentismo){
        AccidentesAusentismo::create([
            'sistema_id'  => session('sistema')->id,
            'accidente_id'       => $idAccidente,
            'ausentismo_id'        => $idAusentismo,
        ]);
        
        return;
    }
    
    public function crearDatosGenerales($idAccidente){
        $data = request()->validate([
            'idAusencia'    =>  'string|required',
            'dias'          => 'integer|required',
            'horas'         => 'integer|required',
        ],[
            'dias.required'         => 'Debe escribir los días que durará la usencia',
            'horas.required'        => 'Debe escribir las horas que durará la usencia',
            'horas.integer'     => 'Revise el formato de los datos',
            'dias.integer'     => 'Revise el formato de los datos',
        ]);
        
        $ausentismo = Ausentismo::find($data["idAusencia"]);
        $ausentismo->dias_totales          =  $data["dias"];
        $ausentismo->horas_totales         =  $data["horas"];
        $ausentismo->dias_ausentismo       =  $this->calcularDiasAusencia($data["dias"],$ausentismo->fecha_inicio,$ausentismo->hora_inicio);
        $ausentismo->horas_ausentismo      =  $data["horas"];
        $ausentismo->fecha_regreso         =  $this->calcularFechaRegreso($data["dias"], $ausentismo->fecha_inicio);
        $ausentismo->hora_regreso          =  $this->calcularHoraRegreso($data["horas"], $ausentismo->hora_inicio);
        $ausentismo->save();
        
        $calculosAusentismo = new AusentismosCalculosController();
        $calculosAusentismo->calcularValorAusencia($data["idAusencia"],$ausentismo->ausentado_id);
        
        return redirect()->route('diagnostico-ausentismo-accidente',['idAccidente'=>$idAccidente]);
    }
    
    
    
    private function calcularFechaRegreso($diasTotales,$fInicio){
        $fechaInicioAusencia = new DateTime("$fInicio");
        $fechaRegreso = $fechaInicioAusencia->add(new DateInterval("P".$diasTotales."D"));
        if($fechaRegreso->format("l") === "Saturday"){
           $fechaRegreso->add(new DateInterval("P2D"));
        }
        if($fechaRegreso->format("l") === "Sunday"){
           $fechaRegreso->add(new DateInterval("P1D"));
        }
        return  $fechaRegreso->format("Y-m-d");
    }
    
    private function calcularHoraRegreso($horasTotales,$hInicio){
        $horaInicioAusencia = new DateTime("$hInicio");
        $horaRegreso = $horaInicioAusencia->add(new DateInterval("PT".$horasTotales."H"));
        
        return  $horaRegreso->format("H:i:s");
    }
    
    private function calcularDiasAusencia($diasTotales,$fechaInicio, $horaInicio){
        $fechaDiasAusencia = new DateTime("$fechaInicio $horaInicio");
        $diasRealesAusencia = 0;
        //$fechaDiasAusencia = new DateTime($this->fechaInicialCalculada->format("l, Y-m-d H:i:s"));
        $fechaDiasAusencia->sub(new DateInterval("P1D"));
        for($i=1;$i<=(int)$diasTotales;$i++){
            $fechaDiasAusencia->add(new DateInterval("P1D"));
            //echo "<p>".$fechaDiasAusencia->format("l")."</p>";
            if((string)$fechaDiasAusencia->format("l") !== "Saturday" && (string)$fechaDiasAusencia->format("l") !== "Sunday"){
                $diasRealesAusencia++;
             }
        }
        
        return $diasRealesAusencia;
    }
    
    public function crearDiagnostico($idAccidente) {
        $data = request()->validate([
            'idAusencia'   => 'string|required',
            'diagnostico'  => 'string|required',
            'eps'          => 'string|required',
        ],[
            'diagnostico.required'       => 'Código de diagnóstico',
            'eps.required'               => 'Ingrese EPS',
            
            'diagnostico.string'       => 'Código de diagnóstico',
            'eps.string'               => 'Ingrese EPS',
        ]);
        
        $ausentismo = Ausentismo::find($data["idAusencia"]);
        $ausentismo->codigo_diagnostico        =  $data["diagnostico"];
        $ausentismo->eps                       =  $data["eps"];
        $ausentismo->save();
        
        return redirect()->route("ausentismo",["id"=>$data["idAusencia"]]);
    }
    
    
    
    public function cancelarAusentismo($idAccidente, $idAusentismo){
        Ausentismo::where('sistema_id',session('sistema')->id)
            ->where('id',$idAusentismo)
            ->delete();
        
        AccidentesAusentismo::where('sistema_id',session('sistema')->id)
                ->where('accidente_id',$idAccidente)
                ->where('ausentismo_id',$idAusentismo)
                ->delete();
        
        return redirect()->route("accidente",['id'=>$idAccidente]);
    }
}
