<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCausasBasicasInmediatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('causas_basicas_inmediatas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('origen_id')->unsigned();
            $table->enum('origen_table',['N/A','Accidentes','Hallazgos'])->defaul('N/A');
            $table->enum('tipo',['N/A','Basica','Inmediata'])->defaul('N/A');
            $table->integer('factor')->unsigned();
            $table->integer('categoria')->unsigned();
            $table->string('descripcion');
            $table->longText('observaciones')->nullable();
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
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
        Schema::dropIfExists('causas_basicas_inmediatas');
    }
}
