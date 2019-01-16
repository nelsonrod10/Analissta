<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeligrosHallazgosAccidentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peligros_hallazgos_accidentes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('origen_id')->unsigned();
            $table->enum('origen_table',['N/A','Accidentes','Hallazgos'])->defaul('N/A');
            $table->integer('clasificacion')->unsigned()->default(0);
            $table->integer('categoria')->unsigned()->default(0);
            $table->integer('subCategoria')->unsigned()->default(0);
            $table->string('fuentes')->default("");
            $table->string('especificacion')->default("");
            $table->string('factorHumano')->defaul("N/A");
            
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
        Schema::dropIfExists('peligros_hallazgos_accidentes');
    }
}
