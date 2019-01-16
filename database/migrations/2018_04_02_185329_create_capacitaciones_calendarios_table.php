<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapacitacionesCalendariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capacitaciones_calendarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('centroTrabajo_id')->unsigned();
            $table->integer('capacitacion_id')->unsigned();
            $table->string('responsable');
            $table->enum('tipo',['','obligatoria','sugerida','valoracion','hallazgo'])->default('');
            $table->enum('ejecutada',['','Si','No'])->default('No');
            $table->integer('anio')->unsigned();
            $table->enum('mes',['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']);
            $table->enum('semana',['1','2','3','4']);
            $table->integer('poblacion_objetivo')->unsigned()->default(0);
            $table->integer('invitados')->unsigned()->default(0);
            $table->integer('asistentes')->unsigned()->defatul(0);
            $table->decimal('duracion',8,2)->defatul(0);
            
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
            $table->foreign('centroTrabajo_id')->references('id')->on('centros_trabajos')->onDelete('cascade');
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
        Schema::dropIfExists('capacitaciones_calendarios');
    }
}
