<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspeccionesvaloracionePeligroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspecciones_valoracione_peligro', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inspeccion_id')->unsigned();
            $table->integer('peligro_id')->unsigned();
            $table->foreign('peligro_id')->references('id')->on('peligros')->onDelete('cascade');
            $table->foreign('inspeccion_id')->references('id')->on('inspecciones_valoraciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actividadesvaloracione_peligro');
    }
}
