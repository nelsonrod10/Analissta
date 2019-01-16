<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccidentesCostosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accidentes_costos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('accidente_id')->unsigned();
            $table->decimal('costos',20,2);
            $table->decimal('persona',20,2);
            $table->decimal('operacion',20,2);
            $table->decimal('productividad',20,2);
            $table->decimal('seguimiento',20,2);
            $table->decimal('imagen_corporativa',20,2);
            $table->decimal('legales',20,2);
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
            $table->foreign('accidente_id')->references('id')->on('accidentes')->onDelete('cascade');
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
        Schema::dropIfExists('accidentes_costos');
    }
}
