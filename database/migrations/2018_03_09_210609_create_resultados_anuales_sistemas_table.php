<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultadosAnualesSistemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultados_anuales_sistemas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empresaCliente_id')->unsigned();
            $table->foreign('empresaCliente_id')->references('id')->on('empresa_clientes')->onDelete('cascade');
            $table->integer('anio')->unsigned();
            $table->string('tasaMortalidad')->default('0');
            $table->string('indLesionesIncapacitantes')->default('0');
            $table->string('indFrecuencia')->default('0');
            $table->string('indSeveridad')->default('0');
            $table->string('tasaAccidentalidad')->default('0');
            $table->string('tasaEnfLaboral')->default('0');
            $table->string('indFrecEnfLaboral')->default('0');
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
        Schema::dropIfExists('resultados_anuales_sistemas');
    }
}
