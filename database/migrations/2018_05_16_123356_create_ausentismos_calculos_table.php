<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAusentismosCalculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ausentismos_calculos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('ausentismo_id')->unsigned();
            $table->enum('pagador',['ARL','Empresa','EPS']);
            $table->integer('dias_cobrados')->unsigned();
            $table->integer('horas_cobradas')->unsigned();
            $table->decimal('porcentaje',10,2)->unsigned();
            $table->decimal('valor',20,2)->unsigned();
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
            $table->foreign('ausentismo_id')->references('id')->on('ausentismos')->onDelete('cascade');
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
        Schema::dropIfExists('ausentismos_calculos');
    }
}
