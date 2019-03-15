<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluacionesMedicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluaciones_medicas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empresaCliente_id')->unsigned();
            $table->integer('empleado_id')->unsigned();
            $table->enum('estado',['N/A','Programada','Realizada'])->default('N/A');
            $table->string('anio_sugerido');
            $table->string('mes_sugerido');
            $table->string('dia_sugerido');
            $table->string('anio_realizado')->nullable();
            $table->string('mes_realizado')->nullable();
            $table->string('dia_realizado')->nullable();
            $table->longText('observaciones')->nullable();
            $table->foreign('empresaCliente_id')->references('id')->on('empresa_clientes')->onDelete('cascade');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
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
        Schema::dropIfExists('evaluaciones_medicas');
    }
}
