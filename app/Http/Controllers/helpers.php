<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Asesore;
use App\Usuario;
use DateTime;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class helpers extends Controller
{
    /*public static function getAsesor(){
        $user = \App\User::find(Auth::user()->id);
        $asesorUser = $user->asesor;
            foreach ($asesorUser as $value) {
                $idAsesor = $value->id;
            }
        $asesor = Asesore::find($idAsesor);
        $empresaAsesor = $asesor->empresaAsesor;
        return $empresaAsesor;
    }*/
    
    public function calcularPrecios($empleados){
        
        $archivo = simplexml_load_file(base_path("archivosXML/ListadoPreciosAnalissta/listadoPreciosAnalissta.xml"));
        $data = $archivo->xpath("//listaPrecios/item[empleados=$empleados]");
        $calculoDescuento = (((float)$data[0]->precioMes*12)*$data[0]->dctoAnual)/100;
        $precioAnual = ((float)$data[0]->precioMes*12) - $calculoDescuento;
        
        $calculo = [
          "mensualidad"   => (float)$data[0]->precioMes,
          "anualidad"     => round($precioAnual,2),
          "descuento"     => (int)$data[0]->dctoAnual  
        ];
        
        return response()->json([
            'calculo' => $calculo,
        ]);
    }
    
    public function especialidadesCategoria($categoria){
        
        $xml_especialidades = simplexml_load_file(base_path("archivosXML/Comunidad/especialidades.xml"));
        $especialidades = $xml_especialidades->xpath("//comunidad/categoria[@id='$categoria']/especialidades/item");
        $result = "";
        foreach ($especialidades as $especialidad) {
            $result .= '<div class="form-check col-md-6">'
                    . '<input class="form-check-input" type="checkbox" name="especialidades[]" id="especialidad-'.$especialidad->attributes()["id"].'" value="'.$especialidad->attributes()["id"].'" >'
                    .'<label  class="form-check-label" style="font-weight: normal" for="especialidad-'.$especialidad->attributes()["id"].'">'.ucfirst(strtolower($especialidad)).'</label>';    
            $result .= "</div>";
        }
        
        
        return response()->json([
            'especialidades' => $result,
        ]);
    }
    
    public static function getAsesor(){
        $user = \App\User::find(Auth::user()->id);
        if (Gate::forUser(Auth::user())->allows('verify-Asesor-role')) {
            $asesorUser = $user->asesor;
            foreach ($asesorUser as $value) {
                $idAsesor = $value->id;
            }
            $asesor = Asesore::find($idAsesor);
            $empresaUsuarioConectado = $asesor->empresaAsesor;
        }else{
            $usuarioUser = $user->usuario; 
            foreach ($usuarioUser as $value) {
                $idUsuario = $value->id;
            }
            $empresaUsuarioConectado = Usuario::find($idUsuario)->empresaCliente;
        }
        
        return $empresaUsuarioConectado;
    }
    
    public static function getDateNow(){
        date_default_timezone_set('America/Bogota');
        $objFechaActual = new DateTime("NOW");
        return $objFechaActual;
    }
    
    public static function getCurrentYear(){
        date_default_timezone_set('America/Bogota');
        $objFechaActual = new DateTime("NOW");;
        (string)$anioActual = $objFechaActual->format("Y");
        
        return $anioActual;
    }
    
    public static function getLastYear(){
        date_default_timezone_set('America/Bogota');
        $objFechaActual = new DateTime("NOW");;
        (string)$fechaActual = $objFechaActual->format("Y-m-d");
        (string)$anioAnterior = $objFechaActual->sub(new DateInterval("P1Y"))->format("Y");
        
        return $anioAnterior;
    }
    
    public static function calcularEdad($fecha){
        date_default_timezone_set('America/Bogota');
        $fechaActual = new DateTime("NOW");
        $fechaNacimiento = new DateTime($fecha);
        $edad = $fechaActual->diff($fechaNacimiento);
        return $edad->format("%Y");
    }
    
    public static function calcularDiferenciaEnAnios($fecha){
        date_default_timezone_set('America/Bogota');
        $fechaActual = new DateTime("NOW");
        $fechaComparar = new DateTime($fecha);
        $diferencia = $fechaActual->diff($fechaComparar);
        return $diferencia->format("%Y");
    }
    
    public static function calcularRangoEdad($edad){
        switch ((int)$edad) {
            case ($edad == 0 && $edad < 18):
                $rangoEdad = "18 a 30";break;
            case ($edad >= 18 && $edad <= 30):
                $rangoEdad = "18 a 30";break;
            case ($edad >= 31 && $edad <= 40):
                $rangoEdad = "31 a 40";break;
            case ($edad >= 41 && $edad <= 50):
                $rangoEdad = "41 a 50";break;
            case ($edad >= 51 && $edad <= 60):
                $rangoEdad = "51 a 60";break;
            case ($edad >= 61):
                $rangoEdad = "61 o mas";break;
            default:
                $rangoEdad = "No puede contratar menores de edad";break;
        }
        return $rangoEdad;
    }
    
    public static function calcularRangoDias($dias){
        switch ((int)$dias) {
            case ($dias == 0):
                $rangoDias = "0";break;
            case ($dias >= 1 && $dias <= 2):
                $rangoDias = "1 a 2";break;
            case ($dias >= 3 && $dias <= 10):
                $rangoDias = "3 a 10";break;
            case ($dias > 10):
                $rangoDias = "Mayor a 10";break;
            default:
                $rangoDias = "Error en el calculo, el valor no puede ser $dias";break;
        }
        return $rangoDias;
    }
    
    public static function getSalarioMinimoVigente(){
        return 781242; 
    }
    
    public static function calcularRangoSalario($salario){
        $salarioMinimoVigente = helpers::getSalarioMinimoVigente();
        $minimoX3 = $salarioMinimoVigente*3;
        $minimoX5 = $salarioMinimoVigente*5;
        $minimoX7 = $salarioMinimoVigente*7;
        
        switch ((float)$salario) {
        case ($salario === $salarioMinimoVigente):
            $rango = "Mínimo Legal (S.M.L.)";
            break;
        case ($salario > $salarioMinimoVigente && $salario <= $minimoX3):
            $rango = "Entre 1 a 3 S.M.L.";
            break;
        case ($salario > $minimoX3 && $salario <= $minimoX5):
            $rango = "Entre 3 a 5 S.M.L.";
            break;
        case ($salario > $minimoX5 && $salario <= $minimoX7):
            $rango = "Entre 5 a 7 S.M.L.";
            break;
        case ($salario > $minimoX7):
            $rango = "Mas de 7 S.M.L.";
            break;
        default:
            $rango = "No puede ser menor al minimo vigente";
            break;
        }
        return $rango;
    }
    
    public static function buscarCodigoDiagnostico($q){
        if($q === ""){return;}
        $archivoCodigos = simplexml_load_file(base_path("archivosXML/Ausentismo/diagnosticosAusencia.xml"));
        $codigos=$archivoCodigos->xpath("//Ausentismo/Diagnostico");
        $count=0;
        foreach ($codigos as $codigo) {
            if (stristr(substr($codigo->lineaCodigo, 0, strlen($q)),$q) || stristr(substr($codigo->lineaDescripcion, 0, strlen($q)),$q)) {
                //$data = $archivoCodigos->xpath("//Ausentismo/Diagnostico[lineaCodigo = '$codigoArchivo']");
                if($count <=9){
                    echo "<option class='option-datalist' value='{$codigo->lineaCodigo}'>{$codigo->lineaDescripcion}<option/>";
                }
                $count++;
            }
        }
    }
    
    public static function datosDiagnostico($diagnostico){
        $archivoCodigos = simplexml_load_file(base_path("archivosXML/Ausentismo/diagnosticosAusencia.xml"));
        $datos=$archivoCodigos->xpath("//Ausentismo/Diagnostico[lineaCodigo='$diagnostico']");
        return $datos[0];
    }
    
    public static function calcularFechaFinal($d,$h,$fi,$hi){
        $fecha1 = new DateTime("$fi $hi");
        $textoFecha1 = helpers::traducirDias($fecha1->format("l")).", ".helpers::traducirMeses($fecha1->format("F"))." ".$fecha1->format("d")." de ".$fecha1->format("Y");
        echo "<div><b>Fecha Inicial: </b>".$textoFecha1."<b>, Hora Inicial: </b>".$fecha1->format("H:i:s")." <i> (Hora Militar)</i></div><br/>";
        
        $newFecha = $fecha1->add(new DateInterval("P".$d."DT".$h."H"));
        if($newFecha->format("l") === "Saturday"){
           $newFecha->add(new DateInterval("P2D"));
        }
        if($newFecha->format("l") === "Sunday"){
           $newFecha->add(new DateInterval("P1D"));
        }
        $textoFechaF = helpers::traducirDias($newFecha->format("l")).", ".helpers::traducirMeses($newFecha->format("F"))." ".$newFecha->format("d")." de ".$newFecha->format("Y");
        echo "<div><b>Fecha de Regreso: </b>".$textoFechaF."<b>, Hora Regreso: </b>".$newFecha->format("H:i:s")." <i> (Hora Militar)</i></div>";
    }
    
    public static function meses_de_numero_a_texto($valor){
        switch ($valor) {
            case "0":$mes = "Enero";break;
            case "1":$mes = "Febrero";break;
            case "2":$mes = "Marzo";break;
            case "3":$mes = "Abril";break;
            case "4":$mes = "Mayo";break;
            case "5":$mes = "Junio";break;
            case "6":$mes = "Julio";break;
            case "7":$mes = "Agosto";break;
            case "8":$mes = "Septiembre";break;
            case "9":$mes = "Octubre";break;
            case "10":$mes = "Noviembre";break;
            case "11":$mes = "Diciembre";break;
            default:break;
        }
        return $mes;
            
    }
    
    public static function traducirMeses($valor){
        switch ($valor) {
            case "January":$mes = "Enero";break;
            case "February":$mes = "Febrero";break;
            case "March":$mes = "Marzo";break;
            case "April":$mes = "Abril";break;
            case "May":$mes = "Mayo";break;
            case "June":$mes = "Junio";break;
            case "July":$mes = "Julio";break;
            case "August":$mes = "Agosto";break;
            case "September":$mes = "Septiembre";break;
            case "October":$mes = "Octubre";break;
            case "November":$mes = "Noviembre";break;
            case "December":$mes = "Diciembre";break;
            default:break;
        }
        return $mes;
            
    }
    
    
    
    public static function traducirMesesAlIngles($valor){
        $mes="";
        switch ($valor) {
            case "Enero":$mes = "January";break;
            case "Febrero":$mes = "February";break;
            case "Marzo":$mes = "March";break;
            case "Abril":$mes = "April";break;
            case "Mayo":$mes = "May";break;
            case "Junio":$mes = "June";break;
            case "Julio":$mes = "July";break;
            case "Agosto":$mes = "August";break;
            case "Septiembre":$mes = "September";break;
            case "Octubre":$mes = "October";break;
            case "Noviembre":$mes = "November";break;
            case "Diciembre":$mes = "December";break;
            default:break;
        }
        return $mes;
            
    }
    
    public static function traducirDias($valor){
        switch ($valor) {
            case "Monday":$dia="Lunes";break;
            case "Tuesday":$dia="Martes";break;
            case "Wednesday":$dia="Miercoles";break;
            case "Thursday":$dia="Jueves";break;
            case "Friday":$dia = "Viernes";break;
            case "Saturday":$dia = "Sabado";break;
            case "Sunday":$dia = "Domingo";break;
        }
        return $dia;
    }
    
    public static function obtenerNumeroMes($textMes){
        switch ($textMes) {
            case "Enero":$numeroMes=0;break;
            case "Febrero":$numeroMes=1;break;
            case "Marzo":$numeroMes=2;break;
            case "Abril":$numeroMes=3;break;
            case "Mayo":$numeroMes = 4;break;
            case "Junio":$numeroMes = 5;break;
            case "Julio":$numeroMes = 6;break;
            case "Agosto":$numeroMes = 7;break;
            case "Septiembre":$numeroMes = 8;break;
            case "Octubre":$numeroMes = 9;break;
            case "Noviembre":$numeroMes = 10;break;
            case "Diciembre":$numeroMes = 11;break;
        }
        return $numeroMes;
    }
    
    public static function calcularJornadasFrecuencia($frecuencia,$mesInicial,$semanaInicio){
        $arrMeses=$arrSemanas1=$arrSemanas2=array();
        $mesInicio = $mesInicial+1;
        //Esto se hace porque se encontro en la clase DateTime::createFromFormat un error para el mes de febrero
        //29 sept 2017
        if($mesInicial !== "1"){
            $mes = DateTime::createFromFormat("n", (int)$mesInicio);
            $textMesInicio = helpers::traducirMeses($mes->format("F"));
        }else{
            $textMesInicio = "Febrero";
        }
        
        switch ($frecuencia) {
            case "semanal": $incrementoMensual=1;$incrementoSemanal=1;$inicioI=1;$comparacionI=4;break;
            case "quincenal":$incrementoMensual=1;$incrementoSemanal=2;$inicioI=($semanaInicio%2===0)?2:1;$comparacionI=4;break;
            case "mensual":$incrementoMensual=1;$incrementoSemanal=4;$inicioI=$semanaInicio;$comparacionI=$semanaInicio;break;
            case "bimestral":$incrementoMensual=2;$incrementoSemanal=4;$inicioI=$semanaInicio;$comparacionI=$semanaInicio;break;
            case "trimestral":$incrementoMensual=3;$incrementoSemanal=4;$inicioI=$semanaInicio;$comparacionI=$semanaInicio;break;
            case "cuatrimestral":$incrementoMensual=4;$incrementoSemanal=4;$inicioI=$semanaInicio;$comparacionI=$semanaInicio;break;
            case "semestral":$incrementoMensual=6;$incrementoSemanal=4;$inicioI=$semanaInicio;$comparacionI=$semanaInicio;break;
            case "anual":$incrementoMensual=12;$incrementoSemanal=4;$inicioI=$semanaInicio;$comparacionI=$semanaInicio;break;
            default:break;
        }

        for($i=(int)$semanaInicio;$i<=4;$i+=$incrementoSemanal){array_push($arrSemanas1, $i);}
        array_push($arrMeses,$textMesInicio);
        for($i=$incrementoMensual;$i<=(12-(int)$mesInicio);$i+=$incrementoMensual){
            $mesCreado = DateTime::createFromFormat("n", (int)$mesInicio+$i);
            array_push($arrMeses,  helpers::traducirMeses($mesCreado->format("F")));
        }
        $arrJornadas =  array_fill_keys($arrMeses,"");
//este para llenar las semanas de acuerdo a la semana de inicio                        
        $arrJornadas["$textMesInicio"]=implode(",",$arrSemanas1);
//este para llenar los otros meses fuera del mes de inicio            
        for($i=$inicioI;$i<=$comparacionI;$i+=$incrementoSemanal){array_push($arrSemanas2, $i);}
        foreach ($arrJornadas as $key => $value) {
            if($key !== $textMesInicio){$arrJornadas[$key]=implode(",",$arrSemanas2);}
        }
        return $arrJornadas;
    }
    
    public static function interpretacionValoracion($variable,$flag){
        $textReturn = "";
        if($flag === "NP"){
            if((int)$variable >=2 && (int)$variable <= 4 ){$textReturn= "Bajo";}
            if((int)$variable >=6 && (int)$variable <= 8 ){$textReturn = "Medio";}
            if((int)$variable >=10 && (int)$variable <= 20 ){$textReturn = "Alto";}
            if((int)$variable >=24 && (int)$variable <= 40 ){$textReturn = "Muy Alto";}
        }
        
        if($flag === "NRI"){
           if((int)$variable <= 20 ){$textReturn = "IV - Mantener Controles Actuales";}
           if((int)$variable >=40 &&  (int)$variable <= 120 ){$textReturn = "III - Mejorar si es posible";}
           if((int)$variable >=150 && (int)$variable <= 500 ){$textReturn = "II - Corregir y adoptar medidas operacionales";}
           if((int)$variable >=600 && (int)$variable <= 4000 ){$textReturn = "I - Situación Crítica, Corrección urgente";}   
        }
        return $textReturn;
    }
    
    
}
