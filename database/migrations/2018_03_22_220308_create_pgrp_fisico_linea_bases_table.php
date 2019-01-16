<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePgrpFisicoLineaBasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pgrp_fisico_linea_bases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pgrp_fisicos_id')->unsigned();
            $table->enum('nombreMeta',['N/A','Eficacia Frecuencia','Eficacia Severidad','Eficacia ili','Eficacia Tasa Mortalidad'])->defaul('N/A');
            $table->string('Enero')->default('0');
            $table->string('Febrero')->default('0');
            $table->string('Marzo')->default('0');
            $table->string('Abril')->default('0');
            $table->string('Mayo')->default('0');
            $table->string('Junio')->default('0');
            $table->string('Julio')->default('0');
            $table->string('Agosto')->default('0');
            $table->string('Septiembre')->default('0');
            $table->string('Octubre')->default('0');
            $table->string('Noviembre')->default('0');
            $table->string('Diciembre')->default('0');
            $table->foreign('pgrp_fisicos_id')->references('id')->on('pgrp_fisicos')->onDelete('cascade');
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
        Schema::dropIfExists('pgrp_fisico_linea_bases');
    }
}
