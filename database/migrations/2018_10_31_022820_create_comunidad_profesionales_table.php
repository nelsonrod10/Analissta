<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComunidadProfesionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comunidad_profesionales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('ciudad');
            $table->string('profesion');
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->string('licencia')->nullable();
            $table->longText('perfil')->nullable();
            $table->enum('aceptacion_tratamiento_datos',['Si','No'])->default('No');
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
        Schema::dropIfExists('comunidad_profesionales');
    }
}
