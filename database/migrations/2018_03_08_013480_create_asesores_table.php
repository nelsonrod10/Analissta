<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsesoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asesores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empresaAsesor_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('empresaAsesor_id')->references('id')->on('empresa_asesores')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('nombre');
            $table->string('apellidos');
            $table->bigInteger('identificacion')->unsigned()->unique();
            $table->string('email')->unique();
            $table->bigInteger('telefono')->unsigned();
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
        Schema::dropIfExists('asesores');
    }
}
