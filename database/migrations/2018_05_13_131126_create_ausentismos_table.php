<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAusentismosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ausentismos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('centrosTrabajo_id')->unsigned();
            $table->enum('clasificacion',['Accidente Comun','Accidente Trabajo','Enfermedad General','Enfermedad Laboral','Licencia Paternidad','Licencia Maternidad','Permiso no Remunerado', 'Cita Medica','Permiso Particular','Ausencia Injustificada', 'Licencia Luto','Calamidad Familiar','Calamidad Domestica']);
            $table->date('fecha_inicio');
            $table->time('hora_inicio');
            $table->integer('dias_totales')->unsigned();
            $table->integer('horas_totales')->unsigned();
            $table->integer('dias_ausentismo')->unsigned();
            $table->integer('horas_ausentismo')->unsigned();
            $table->date('fecha_regreso');
            $table->time('hora_regreso');
            $table->integer('ausentado_id')->unsigned()->nullable();
            $table->string('codigo_diagnostico');
            $table->string('eps');
            $table->enum('prorroga',['Si','No']);
            $table->longText('observaciones');
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
            $table->foreign('centrosTrabajo_id')->references('id')->on('centros_trabajos')->onDelete('cascade');
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
        Schema::dropIfExists('ausentismos');
    }
}
