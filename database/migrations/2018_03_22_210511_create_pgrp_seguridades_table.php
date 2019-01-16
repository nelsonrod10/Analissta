<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePgrpSeguridadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pgrp_seguridades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('anio')->unsigned();
            $table->integer('sistema_id')->unsigned();
            $table->enum('estado',['','Programado'])->nullable();
            $table->integer('categoria')->unsigned();
            $table->string('nombre')->default("");
            $table->string('cargo')->default("");
            $table->longText('objetivo')->nullable();
            $table->longText('alcance')->nullable();
            $table->integer('cobertura')->default(100);
            $table->enum('cumplimiento',[0,50,60,70,80,90,100])->defaul(0);
            $table->string('frecuencia_medicion')->default('Mensual');
            $table->enum('frecuencia_analisis',['N/A','Trimestral','Semestral','Anual'])->defaul('N/A');
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
        Schema::dropIfExists('pgrp_seguridades');
    }
}
