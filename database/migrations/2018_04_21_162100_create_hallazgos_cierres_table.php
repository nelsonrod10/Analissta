<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHallazgosCierresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hallazgos_cierres', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('hallazgo_id')->unsigned();
            $table->date('fechaCierrePropuesta');
            $table->date('fechaReapertura');
            $table->enum('eficaz',['N/A','Si','No'])->defaul('N/A');
            $table->longText('observaciones');
            $table->longText('evidencias');
            $table->enum('optimizar',['N/A','Actividades','Capacitaciones','Actividades y Capacitaciones'])->defaul('N/A');
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
            $table->foreign('hallazgo_id')->references('id')->on('hallazgos')->onDelete('cascade');
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
        Schema::dropIfExists('hallazgos_cierres');
    }
}
