<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSistemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sistemas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empresaCliente_id')->unsigned();
            $table->foreign('empresaCliente_id')->references('id')->on('empresa_clientes')->onDelete('cascade');
            $table->timestamps();
        });
        
        /*Pivot table entre sistemas y los centros de trabajo*/
        
        Schema::create('sistema_centros_trabajo', function (Blueprint $table) {
            $table->integer('sistema_id')->unsigned();
            $table->integer('centros_trabajo_id')->unsigned();
            $table->primary(['sistema_id','centros_trabajo_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sistemas');
        Schema::dropIfExists('sistema_centros_trabajo');
    }
}
