<?php

namespace App\Http\Controllers\Ausentismos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ausentismos\Ausentismo;
use App\Ausentismos\AusentismosCalculo;
use App\Empleado;

class AusentismosCalculosController extends Controller
{
    private $idAusencia;
    
    public function calcularValorAusencia($idAusencia,$identificacionAusentado){
        $this->idAusencia = $idAusencia;
        //se borran todos los calculos previos
        AusentismosCalculo::where('sistema_id',session('sistema')->id)
                ->where('ausentismo_id',$idAusencia)
                ->delete();
        
        $ausentismo = Ausentismo::where('sistema_id',session('sistema')->id)
                ->where('id',$idAusencia)
                ->get();
        $empleado = Empleado::where('empresaCliente_id',session('idEmpresaCliente'))
                ->where('identificacion',$identificacionAusentado)
                ->get();
        $origen = (string)$ausentismo[0]->clasificacion; (int)$diasTotales=$ausentismo[0]->dias_totales;
        $horasTotales = (int)$ausentismo[0]->horas_totales; (float)$salarioDia = round($empleado[0]->salarioMes/30,2);
        
        switch ($origen) {
            case ((string)$origen === "Permiso no Remunerado"):
                //Si la ausencia es permiso no remunerado, toda la ausencia da 0 pesos
                $this->crearCalculoAusentismo("Empresa",$diasTotales, 0, 100,0);
            break;
            
            case ((string)$origen === "Enfermedad General" || (string)$origen === "Accidente Comun"):
                $this->calcularAusenciaSegunRango($diasTotales,$horasTotales,$salarioDia);
            break;
        
            case ((string)$origen === "Enfermedad Laboral" || (string)$origen === "Accidente Trabajo"):
                $this->crearCalculoAusentismo("ARL",$diasTotales,$horasTotales,100,$this->calculoValorAusenciaConHoras($diasTotales, $horasTotales,$salarioDia));
            break;
            case ((string)$origen === "Licencia Maternidad"):
                //Se pone este rango de -1 para que sepa que es una licencia de maternidad
                $this->crearCalculoAusentismo("EPS",$diasTotales,0,66.67,$this->calculoValorAusencia($diasTotales, $salarioDia,66.67));
            break;
            default:
                $this->crearCalculoAusentismo("Empresa", $diasTotales,$horasTotales,100,$this->calculoValorAusenciaConHoras($diasTotales, $horasTotales,$salarioDia));
                break;
        }
        return;
    }
    
    private function calcularAusenciaSegunRango($diasTotales,$horasTotales,$salarioDia,$maternidad=false){
        $rango = $this->rangoDias($diasTotales);
        switch ($rango) {
            case 1:
                $this->crearCalculoAusentismo("Empresa", $diasTotales,$horasTotales,100, $this->calculoValorAusenciaConHoras($diasTotales,$horasTotales, $salarioDia));
                break;
            case 2:
                $this->crearCalculoAusentismo("Empresa", 2,$horasTotales,100,$this->calculoValorAusenciaConHoras(2,$horasTotales, $salarioDia));
                //$this->crearCalculoAusentismo($this->calculoValorAusencia(($diasAusencia-3), $salarioDia, 33.33), $rango, "Empresa", 33.33, $diasAusencia-3);
                $this->crearCalculoAusentismo("EPS", $diasTotales-2,0, 66.67,$this->calculoValorAusencia(($diasTotales-2), $salarioDia,66.67));
                break;
            case 3:
                $this->crearCalculoAusentismo("Empresa", 2, $horasTotales, 100,$this->calculoValorAusenciaConHoras(2,$horasTotales, $salarioDia));
                //$this->crearCalculoAusentismo($this->calculoValorAusencia(87, $salarioDia, 33.33), $rango, "Empresa", 33.33, 87);
                $this->crearCalculoAusentismo("EPS", 88,0, 66.67,$this->calculoValorAusencia(88, $salarioDia,66.67));
                //$this->crearCalculoAusentismo($this->calculoValorAusencia(($diasAusencia-90), $salarioDia, 50), $rango, "Empresa", 50, $diasAusencia-90);
                $this->crearCalculoAusentismo("EPS", $diasTotales-90,0,50,$this->calculoValorAusencia(($diasTotales-90), $salarioDia,50));
                break;
            case 4:
                $this->crearCalculoAusentismo("Empresa", 2,$horasTotales,100,$this->calculoValorAusenciaConHoras(2,$horasTotales, $salarioDia));
                //$this->crearCalculoAusentismo($this->calculoValorAusencia(87, $salarioDia, 33.33), $rango, "Empresa", 33.33, 87);
                $this->crearCalculoAusentismo("EPS", 88,0,66.67,$this->calculoValorAusencia(88, $salarioDia,66.67));
                //$this->crearCalculoAusentismo($this->calculoValorAusencia(90, $salarioDia, 50), $rango, "Empresa", 50, 90);
                $this->crearCalculoAusentismo("EPS", 90,0,50,$this->calculoValorAusencia(90, $salarioDia,50));
                //$this->crearCalculoAusentismo($this->calculoValorAusencia(($diasTotales-180), $salarioDia, 50), $rango, "Empresa", 50, $diasTotales-180);
                $this->crearCalculoAusentismo("ARL",$diasTotales-180,0,50, $this->calculoValorAusencia(($diasTotales-180), $salarioDia,50));
                break;
            default:
                break;
        }
        return;
    }
    
    private function rangoDias($diasTotales){
        $rango = 0;
        switch ((int)$diasTotales) {
            case ($diasTotales <= 2):
                //paga 100% el empleador
                $rango = 1;
                break;
            case ($diasTotales >=3 && $diasTotales <=90):
                $rango = 2;
                break;
            case ($diasTotales >=91 && $diasTotales <=180):
                $rango = 3;
                break;
            case ($diasTotales >=181 && $diasTotales <=540):
                $rango = 4;
                break;
            default:
                break;
        }
        
        return $rango;
    }
    
    private function calculoValorAusenciaConHoras($diasTotales,$horasTotales,$salarioDia){
        return ((float)$diasTotales*(float)$salarioDia) + ((float)$horasTotales*((float)$salarioDia/8)) ;
    }
    
    private function calculoValorAusencia($diasTotales,$salarioDia,$porcentaje){
        $calculo = (((int)$diasTotales*(float)$salarioDia)*(float)$porcentaje)/100;
        return $calculo;
    }
    
    private function crearCalculoAusentismo($pagador,$diasTotales,$horasTotales,$porcentaje,$valor){
        AusentismosCalculo::create([
            'sistema_id'=> session('sistema')->id,
            'ausentismo_id'=>$this->idAusencia,
            'pagador'=>$pagador,
            'dias_cobrados' =>$diasTotales,
            'horas_cobradas' =>$horasTotales,
            'porcentaje'=>$porcentaje,
            'valor' => $valor
        ]);
    }
}
