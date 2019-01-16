<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAusentismosProrrogasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ausentismos_prorrogas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('ausentismo_id')->unsigned();
            $table->integer('dias_prorroga')->unsigned();
            $table->integer('horas_prorroga')->unsigned();
            $table->date('fecha_regreso');
            $table->time('hora_regreso');
            $table->longText('observaciones');
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
            $table->foreign('ausentismo_id')->references('id')->on('ausentismos')->onDelete('cascade');
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
        Schema::dropIfExists('ausentismos_prorrogas');
    }
}
