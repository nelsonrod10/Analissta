<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCentrosTrabajoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centros_trabajos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empresaCliente_id')->unsigned();
            $table->string('nombre');
            $table->integer('totalEmpleados')->unsigned()->default(0);
            $table->enum('nivelRiesgo',['1','2','3','4','5']);
            $table->string('ciudad');
            $table->string('direccion');
            $table->bigInteger('telefono')->unsigned();
            $table->foreign('empresaCliente_id')->references('id')->on('empresa_clientes')->onDelete('cascade');
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
        Schema::dropIfExists('centros_trabajo');
    }
}
