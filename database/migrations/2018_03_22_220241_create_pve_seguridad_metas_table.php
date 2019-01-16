<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePveSeguridadMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pve_seguridad_metas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pve_seguridades_id')->unsigned();
            $table->enum('nombreMeta',['N/A','Eficacia Incidencia','Eficacia Prevalencia','Eficacia Casos Nuevos'])->defaul('N/A');
            $table->string('frecuencia_medicion')->default('Mensual');
            $table->enum('frecuencia_analisis',['N/A','Trimestral','Semestral','Anual'])->defaul('N/A');
            $table->enum('objetivo',['N/A','Aumentar','Reducir','Mantener'])->defaul('N/A');
            $table->enum('unidad',['N/A','unidad','porcentaje'])->defaul('N/A');
            $table->string('valorMeta')->default('0');
            $table->foreign('pve_seguridades_id')->references('id')->on('pve_seguridades')->onDelete('cascade');
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
        Schema::dropIfExists('pve_seguridad_metas');
    }
}
