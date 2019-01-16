<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaAsesoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa_asesores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->unique();
            $table->bigInteger('nit')->unsigned()->unique();
            $table->bigInteger('telefono')->unsigned();
            $table->string('web')->unique()->nullable();
            $table->string('direccion')->unique();
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
        Schema::dropIfExists('empresa_asesores');
    }
}
