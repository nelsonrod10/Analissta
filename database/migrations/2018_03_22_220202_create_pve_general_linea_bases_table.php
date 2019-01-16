<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePveGeneralLineaBasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pve_general_linea_bases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pve_generales_id')->unsigned();
            $table->enum('nombreMeta',['N/A','Eficacia Incidencia','Eficacia Prevalencia','Eficacia Casos Nuevos'])->defaul('N/A');
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
            $table->foreign('pve_generales_id')->references('id')->on('pve_generales')->onDelete('cascade');
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
        Schema::dropIfExists('pve_general_linea_bases');
    }
}
