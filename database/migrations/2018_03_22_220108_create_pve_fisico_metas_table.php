<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePveFisicoMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pve_fisico_metas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pve_fisicos_id')->unsigned();
            $table->enum('nombreMeta',['N/A','Eficacia Incidencia','Eficacia Prevalencia','Eficacia Casos Nuevos'])->defaul('N/A');
            $table->enum('objetivo',['N/A','Aumentar','Reducir','Mantener'])->defaul('N/A');
            $table->enum('unidad',['N/A','unidad','porcentaje'])->defaul('N/A');
            $table->string('valorMeta')->default('0');
            $table->foreign('pve_fisicos_id')->references('id')->on('pve_fisicos')->onDelete('cascade');
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
        Schema::dropIfExists('pve_fisico_metas');
    }
}
