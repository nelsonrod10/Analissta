<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComunidadEspecialidadesEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comunidad_especialidades_empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comEmp_id')->unsigned();
            $table->string('categoria');
            $table->string('especialidades');
            $table->foreign('comEmp_id')->references('id')->on('comunidad_empresas')->onDelete('cascade');
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
        Schema::dropIfExists('comunidad_especialidades_empresas');
    }
}
