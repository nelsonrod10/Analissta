<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapacitacionesDisponiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capacitaciones_disponibles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('clasificacion_peligro_id')->unsigned();
            $table->string("capacitaciones_valoracione_id")->defatul("0");
            $table->enum('medida',['N/A','eliminar','sustituir','ingenieria','epp_herramientas','administrativos'])->defaul('N/A');
            $table->string('nombre')->defaul('N/A');
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
        Schema::dropIfExists('capacitaciones_disponibles');
    }
}
