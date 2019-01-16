<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcesosCentrosTrabajoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procesos_centros_trabajo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('centrosTrabajos_id')->unsigned()->default(0);//aplica cuando es matriz por centro
            $table->integer('proceso_id')->unsigned();
            $table->foreign('centrosTrabajos_id')->references('id')->on('centros_trabajos')->onDelete('cascade');
            $table->foreign('proceso_id')->references('id')->on('procesos')->onDelete('cascade');
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
        Schema::dropIfExists('procesos_centros_trabajo');
    }
}
