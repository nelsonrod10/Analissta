<?php

namespace App\Http\Controllers\Ausentismos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\Http\Controllers\Ausentismos\AusentismosCalculosController;
use App\EmpresaCliente;
use App\Ausentismos\Ausentismo;
use App\Ausentismos\AusentismosProrroga;

use DateTime;
use DateInterval;

class AusentismosProrrogasController extends Controller
{
    public function showFrmProrroga(){
        $data = request()->validate([
            'idAusentismo'  => 'string|required',
        ]);
        
        
        $ausentismo = Ausentismo::find($data["idAusentismo"]);
        return view('analissta.Ausentismos.crearProrroga.crearProrroga')->with(['ausentismo'=>$ausentismo]);
    }
    
    public function crearProrroga(){
        $data = request()->validate([
            'idAusentismo'  => 'string|required',
            'identificacion'=> 'string|required',
            'fecha_inicio'  => 'date|required',
            'hora_inicio'   => 'string|required',
            'dias'          => 'integer|required',
            'horas'         => 'integer|required',
            'observaciones' => 'string|required',
        ],[
            'fecha_inicio.required' => 'Debe ingresar una fecha valida',
            'hora_inicio.required'  => 'Debe ingresar una hora valida',
            'dias.required'         => 'Debe escribir los días que durará la usencia',
            'horas.required'        => 'Debe escribir las horas que durará la usencia',
            'observaciones.required'=> 'Debe realizar un descripción del ausentismo',
            
            'fecha_inicio.date'      => 'Revise el formato de los datos',
            'hora_inicio.string'      => 'Revise el formato de los datos',
            'horas.integer'     => 'Revise el formato de los datos',
            'dias.integer'     => 'Revise el formato de los datos',
            'observaciones.string' => 'Revise el formato de los datos',
        ]);
        
        AusentismosProrroga::create([
            'sistema_id'  => session('sistema')->id,
            'ausentismo_id'      => $data["idAusentismo"],
            'dias_prorroga'      => $data["dias"],
            'horas_prorroga'     => $data["horas"],
            'fecha_regreso'      => $this->calcularFechaRegreso($data["dias"], $data["fecha_inicio"]),
            'hora_regreso'       => $this->calcularHoraRegreso($data["horas"], $data["hora_inicio"]),
            'observaciones'      => $data["observaciones"],
        ]);
        $this->calcularDiasAusencia($data["idAusentismo"],$data["dias"],$data["fecha_inicio"], $data["hora_inicio"]);
        $calculosAusentismo = new AusentismosCalculosController();
        $calculosAusentismo->calcularValorAusencia($data["idAusentismo"],$data["identificacion"]);
        
        return redirect()->route("ausentismo",["id"=>$data["idAusentismo"]]);
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
    
    private function calcularDiasAusencia($idAusentismo,$diasProrroga,$fechaInicioProrroga, $horaInicioProrroga){
        $fechaDiasAusencia = new DateTime("$fechaInicioProrroga $horaInicioProrroga");
        $diasRealesAusencia = 0;
        //$fechaDiasAusencia = new DateTime($this->fechaInicialCalculada->format("l, Y-m-d H:i:s"));
        $fechaDiasAusencia->sub(new DateInterval("P1D"));
        for($i=1;$i<=(int)$diasProrroga;$i++){
            $fechaDiasAusencia->add(new DateInterval("P1D"));
            //echo "<p>".$fechaDiasAusencia->format("l")."</p>";
            if((string)$fechaDiasAusencia->format("l") !== "Saturday" && (string)$fechaDiasAusencia->format("l") !== "Sunday"){
                $diasRealesAusencia++;
             }
        }
        
        $ausentismo = Ausentismo::find($idAusentismo);
        $ausentismo->dias_totales = $ausentismo->dias_totales+=$diasProrroga;
        $ausentismo->dias_ausentismo = $ausentismo->dias_ausentismo+=$diasRealesAusencia;
        $ausentismo->save();
    }
    
}
