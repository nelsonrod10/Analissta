<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeligrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peligros', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('actividad_id')->unsigned();
            $table->integer('clasificacion')->unsigned();
            $table->integer('categoria')->unsigned();
            $table->integer('subCategoria')->unsigned()->default(0);
            $table->string('fuentes');
            $table->string('especificacion');
            $table->string('factorHumano')->defaul("N/A");
            $table->enum('efectoPersona',['N/A','Corto Plazo','Largo Plazo','Corto y Largo Plazo'])->defaul('N/A');
            $table->longText('efectoPropiedad')->defaul('N/A');
            $table->longText('efectoProcesos')->defaul('N/A');
            $table->enum('eliminar',['N/A','Programar','Programado'])->defaul('N/A');
            $table->enum('sustituir',['N/A','Programar','Programado'])->defaul('N/A');
            $table->enum('ingenieria',['N/A','Programar','Programado'])->defaul('N/A');
            $table->enum('epp_herramientas',['N/A','Programar','Programado'])->defaul('N/A');
            $table->enum('administrativos',['N/A','Programar','Programado'])->defaul('N/A');
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
            $table->foreign('actividad_id')->references('id')->on('actividades')->onDelete('cascade');
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
        Schema::dropIfExists('peligros');
    }
}
