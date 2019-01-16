<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspeccionesCalendariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspecciones_calendarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('centroTrabajo_id')->unsigned();
            $table->integer('inspeccion_id')->unsigned();
            $table->enum('tipo',['','obligatoria','sugerida','valoracion'])->default('');
            $table->enum('ejecutada',['','Si','No'])->default('No');
            $table->integer('anio')->unsigned();
            $table->enum('mes_inicio',['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']);
            $table->enum('semana_inicio',['1','2','3','4']);
            $table->enum('mes',['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']);
            $table->enum('semana',['1','2','3','4']);
            $table->string('responsable');
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
        Schema::dropIfExists('inspecciones_calendarios');
    }
}
