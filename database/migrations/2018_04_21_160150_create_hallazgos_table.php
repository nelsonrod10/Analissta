<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHallazgosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hallazgos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('centrosTrabajo_id')->unsigned();
            $table->integer('proceso_id')->unsigned();
            $table->integer('origen_id')->unsigned();
            $table->date('fechaHallazgo');
            $table->string('cargoResponsable');
            $table->longText('descripcion');
            $table->enum('actoCondicion',['N/A','Acto y Condicion Insegura','Acto Inseguro','Condicion Insegura','Acto Seguro'])->defaul('N/A');
            $table->enum('tipoAccion',['N/A','Correctiva','Preventiva','Oportunidad de Mejora'])->defaul('N/A');
            $table->enum('planAccion',['N/A','Actividades','Capacitaciones','Actividades y Capacitaciones'])->defaul('N/A');
            $table->date('fechaCierre');
            $table->enum('cerrado',['No','Si'])->default('No');
            $table->integer('origen_externo_id')->default(0);
            $table->enum('origen_externo_tipo',['N/A','obligatoria','sugerida','valoracion','accidente'])->default('N/A');
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
            $table->foreign('centrosTrabajo_id')->references('id')->on('centros_trabajos')->onDelete('cascade');
            $table->foreign('proceso_id')->references('id')->on('procesos')->onDelete('cascade');
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
        Schema::dropIfExists('hallazgos');
    }
}
