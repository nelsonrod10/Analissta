<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCortoPlazosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corto_plazos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('peligro_id')->unsigned();
            $table->enum('tipo',['valoracion','revaloracion'])->default('valoracion');
            $table->longText('fuente')->nullable();
            $table->longText('medio')->nullable();
            $table->longText('individuo')->nullable();
            $table->longText('administrativo')->nullable();
            $table->enum('nd',[0,2,6,10]);
            $table->enum('ne',[1,2,3,4]);
            $table->enum('nc',[10,25,60,100]);
            $table->integer('np')->default(0);
            $table->integer('nri')->default(0);
            $table->enum('pgrp',['Si','No'])->default('No');
            $table->integer('pgrp_id')->default(0);
            $table->enum('pgrp_table',['N/A','pgrp_fisicos','pgrp_seguridades','pgrp_generales'])->default("N/A");
            $table->integer('cliente')->unsigned()->default(0);
            $table->integer('contratista')->unsigned()->default(0);
            $table->integer('directos')->unsigned()->default(0);
            $table->integer('visitantes')->unsigned()->default(0);
            $table->longText('peorConsecuencia')->nullable();
            $table->enum('reqLegal',['Si','No']);
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
            $table->foreign('peligro_id')->references('id')->on('peligros')->onDelete('cascade');
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
        Schema::dropIfExists('corto_plazos');
    }
}
