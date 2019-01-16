<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePgrpGeneralMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pgrp_general_metas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pgrp_generales_id')->unsigned();
            $table->enum('nombreMeta',['N/A','Eficacia Frecuencia','Eficacia Severidad','Eficacia ili','Eficacia Tasa Mortalidad'])->defaul('N/A');
            $table->enum('objetivo',['N/A','Aumentar','Reducir','Mantener'])->defaul('N/A');
            $table->enum('unidad',['N/A','unidad','porcentaje'])->defaul('N/A');
            $table->string('valorMeta')->default('0');
            $table->foreign('pgrp_generales_id')->references('id')->on('pgrp_generales')->onDelete('cascade');
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
        Schema::dropIfExists('pgrp_general_metas');
    }
}