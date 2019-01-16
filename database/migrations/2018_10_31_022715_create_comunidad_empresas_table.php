<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComunidadEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comunidad_empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->bigInteger('identificacion')->unsigned()->unique();
            $table->string('ciudad');
            $table->string('web')->unique()->nullable();
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
        Schema::dropIfExists('comunidad_empresas');
    }
}
