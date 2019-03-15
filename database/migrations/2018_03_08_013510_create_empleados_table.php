<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empresaCliente_id')->unsigned();
            $table->integer('centrosTrabajos_id')->unsigned();
            $table->foreign('empresaCliente_id')->references('id')->on('empresa_clientes')->onDelete('cascade');
            $table->foreign('centrosTrabajos_id')->references('id')->on('centros_trabajos')->onDelete('cascade');
            $table->string('nombre');
            $table->string('apellidos');
            $table->bigInteger('identificacion')->unsigned()->unique();
            $table->date('fechaNacimiento');
            $table->enum('genero',['Hombre','Mujer']);
            $table->string('cargo');
            $table->integer('salarioMes')->unsigned();
            $table->string('email')->unique();
            $table->bigInteger('telefono')->unsigned();
            $table->date("fecha_ingreso");
            $table->enum('estadoCivil',['na', 'Soltero', 'Casado','Union Libre','Separado']);
            $table->enum('personasAcargo',['Ninguna', '1-3 Personas', '4-6 Personas','Mas de 7 Personas']);
            $table->enum('escolaridad',['Primaria', 'Secundaria', 'Tecnico/Tecnologo','Universitario','Especialista/Maestro']);
            $table->enum('tipoVivienda',['Propia', 'Arrendada', 'Familiar','Compartida con otro']);
            $table->enum('tiempoLibre',['Otro Trabajo', 'Labores Domesticas', 'Recreacion y Deporte','Estudio','Ninguno']);
            $table->enum('antiguedadEmpresa',['na', 'Menos de 1', 'De 1 a 5','De 5 a 10','De 10 a 15','Mas de 15']);
            $table->enum('antiguedadCargo',['na', 'Menos de 1', 'De 1 a 5','De 5 a 10','De 10 a 15','Mas de 15']);
            $table->enum('tipoContrato',['na', 'Directo Definido', 'Directo Indefinido', 'En Mision','Prestacion de Servicios','Servicios Profesionales']);
            $table->longText('actividades')->nullable();
            $table->enum('diagnosticoEnfermedad',['na', 'Si', 'No']);
            $table->enum('fumador',['na', 'Si', 'No']);
            $table->enum('consumoAlcohol',['na', 'No Consume', 'Semanal','Quincenal','Mensual','Ocasional']);
            $table->enum('deportista',['na', 'No Practica','Diario','Semanal','Quincenal','Mensual','Ocasional']);
            $table->enum('firmaConsentimiento',['na', 'Si','No']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleados');
    }
}
