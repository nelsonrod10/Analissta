<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresupuestoEjecucionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presupuesto_ejecuciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('presupuesto_id')->unsigned();
            $table->integer('calendario_id')->unsigned();
            $table->string('tabla_calendario');
            $table->longText('observaciones');
            $table->string('valor');
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
            $table->foreign('presupuesto_id')->references('id')->on('presupuestos')->onDelete('cascade');
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
        Schema::dropIfExists('presupuesto_ejecuciones');
    }
}
