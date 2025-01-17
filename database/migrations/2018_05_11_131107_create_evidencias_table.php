<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvidenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evidencias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('origen_id')->unsigned();
            $table->enum('origen_table',[
                'Accidente','ActividadesHallazgo','ActividadesObligatoriasSugerida','ActividadesValoracione',
                'CapacitacionesHallazgo','CapacitacionesObligatoriasSugerida','CapacitacionesValoracione',
                'InspeccionesHallazgo','InspeccionesObligatoriasSugerida','InspeccionesValoracione',
                'Ausentismo','Hallazgo'
                ])->defaul('N/A');
            $table->longText('evidencia');
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
        Schema::dropIfExists('evidencias');
    }
}
