<?php

namespace App\Http\Controllers\Ausentismos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers;
use App\Http\Controllers\Ausentismos\AusentismosCalculosController;
use App\EmpresaCliente;
use App\Ausentismos\Ausentismo;
use DateTime;
use DateInterval;

class AusentismosController extends Controller
{
    public function principalAusentismos(){
        return view('analissta.Ausentismos.vistaGeneral');
    }
    
    public function mostrarAusentismo($id){
        
        
        $ausentismo = Ausentismo::find($id);
        return view('analissta.Ausentismos.verAusentismo')->with(['ausentismo'=>$ausentismo]);
    }


    public function datosGenerales($id=null){
        
        
        
        return view('analissta.Ausentismos.crearAusentismo.datosGenerales')->with(['idAusentismo'=>$id]);
    }
    
    public function datosAusentismo($id){
        
        
        
        return view('analissta.Ausentismos.crearAusentismo.datosAusentado')->with(['idAusentismo'=>$id]);
    }
    
    public function diagnosticoAusentismo($id){
        
        
        
        return view('analissta.Ausentismos.crearAusentismo.diagnostico')->with(['idAusentismo'=>$id]);
    }
    
    
    public function calcularFechaFinal($d,$h,$fi,$hi){
        return helpers::calcularFechaFinal($d, $h, $fi, $hi);
    }
    
    public function buscarDiagnostico($diagnostico){
        return helpers::buscarCodigoDiagnostico($diagnostico);
    }
    
    public function datosDiagnostico($diagnostico){
        return response()->json([
            "diagnostico"   => helpers::datosDiagnostico($diagnostico),
        ]);
    }
    
    
    public function crearDatosGenerales($id=null){
        $data = request()->validate([
            'centro'        => 'integer|required',
            'clasificacion' => 'string|required',
            'fecha_inicio'  => 'date|required',
            'hora_inicio'   => 'string|required',
            'dias'          => 'integer|required',
            'horas'         => 'integer|required',
            'observaciones' => 'string|required',
        ],[
            'centro.required'       => 'Debe seleccionar un centro de trabajo',
            'clasificacion.required'=> 'Seleccione una clasificacion',
            'fecha_inicio.required' => 'Debe ingresar una fecha valida',
            'hora_inicio.required'  => 'Debe ingresar una hora valida',
            'dias.required'         => 'Debe escribir los días que durará la usencia',
            'horas.required'        => 'Debe escribir las horas que durará la usencia',
            'observaciones.required'=> 'Debe realizar un descripción del ausentismo',
            
            'centro.integer'     => 'Revise el formato de los datos',
            'clasificacion.string' => 'Revise el formato de los datos',
            'fecha_inicio.date'      => 'Revise el formato de los datos',
            'hora_inicio.string'      => 'Revise el formato de los datos',
            'horas.integer'     => 'Revise el formato de los datos',
            'dias.integer'     => 'Revise el formato de los datos',
            'observaciones.string' => 'Revise el formato de los datos',
        ]);
        if($id === null){
            $idAusentismo = $this->createDatosGenerales($data);
        }else{
            $idAusentismo = $this->updateDatosGenerales($id,$data);
        }
        
        
        return redirect()->route("datos-ausentismo",["id"=>$idAusentismo]);
    }
    
    private function createDatosGenerales($data){
        $ausentismo = Ausentismo::create([
            'sistema_id'  => session('sistema')->id,
            'centrosTrabajo_id'  => $data["centro"],
            'clasificacion'      => $data["clasificacion"],
            'fecha_inicio'       => $data["fecha_inicio"],
            'hora_inicio'        => $data["hora_inicio"],
            'dias_totales'       => $data["dias"],
            'horas_totales'      => $data["horas"],
            'dias_ausentismo'    => $this->calcularDiasAusencia($data["dias"],$data["fecha_inicio"],$data["hora_inicio"]),
            'horas_ausentismo'   => $data["horas"],
            'fecha_regreso'      =>  $this->calcularFechaRegreso($data["dias"], $data["fecha_inicio"]),
            'hora_regreso'       =>  $this->calcularHoraRegreso($data["horas"], $data["hora_inicio"]),
            'prorroga'           =>  $this->verificarProrroga($data["clasificacion"]),
            'observaciones'      => $data["observaciones"],
            'codigo_diagnostico' => "",
            'eps'                => "",
        ]);
        
        return $ausentismo->id;
    }
    
    private function updateDatosGenerales($idAusentismo,$data){
        $ausentismo = Ausentismo::find($idAusentismo);
        $ausentismo->centrosTrabajo_id     =  $data["centro"];
        $ausentismo->clasificacion         =  $data["clasificacion"];
        $ausentismo->fecha_inicio          =  $data["fecha_inicio"];
        $ausentismo->hora_inicio           =  $data["hora_inicio"];
        $ausentismo->dias_totales          =  $data["dias"];
        $ausentismo->horas_totales         =  $data["horas"];
        $ausentismo->dias_ausentismo       =  $this->calcularDiasAusencia($data["dias"],$data["fecha_inicio"],$data["hora_inicio"]);
        $ausentismo->horas_ausentismo      =  $data["horas"];
        $ausentismo->fecha_regreso         =  $this->calcularFechaRegreso($data["dias"], $data["fecha_inicio"]);
        $ausentismo->hora_regreso          =  $this->calcularHoraRegreso($data["horas"], $data["hora_inicio"]);
        $ausentismo->prorroga              =  $this->verificarProrroga($data["clasificacion"]);
        $ausentismo->observaciones         =  $data["observaciones"];
        $ausentismo->save();
        
        return $idAusentismo;
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
    
    private function verificarProrroga($clasificacion){
        if($clasificacion === "Enfermedad General" || $clasificacion === "Accidente Comun" || $clasificacion === "Enfermedad Laboral" || $clasificacion === "Accidente Trabajo"){
            return "Si";
        }
        return "No";
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
    
    public function crearDatosAusentado($id){
        $data = request()->validate([
            'identificacion'  => 'integer|required',
        ],[
            'identificacion.required'       => 'Identificacion del Accidentado',
            'identificacion.integer'       => 'Identificacion del Accidentado',
        ]);
        
        $ausentismo = Ausentismo::find($id);
        $ausentismo->ausentado_id        =  $data["identificacion"];
        $ausentismo->save();
        
        $calculosAusentismo = new AusentismosCalculosController();
        $calculosAusentismo->calcularValorAusencia($id,$data["identificacion"]);
        
        return redirect()->route("diagnostico-ausentismo",["id"=>$id]);
    }
    
    public function crearDiagnostico($id){
        $data = request()->validate([
            'diagnostico'  => 'string|required',
            'eps'          => 'string|required',
        ],[
            'diagnostico.required'       => 'Código de diagnóstico',
            'eps.required'               => 'Ingrese EPS',
            
            'diagnostico.string'       => 'Código de diagnóstico',
            'eps.string'               => 'Ingrese EPS',
        ]);
        
        $ausentismo = Ausentismo::find($id);
        $ausentismo->codigo_diagnostico        =  $data["diagnostico"];
        $ausentismo->eps                       =  $data["eps"];
        $ausentismo->save();
        
        return redirect()->route("ausentismo",["id"=>$id]);
    }
    
    public function cancelarAusentismo($id=null){
        if($id !== null){
            Ausentismo::where('sistema_id',session('sistema')->id)
                ->where('id',$id)
                ->delete();
        }
        return redirect()->route("ausentismos");
    }
}
