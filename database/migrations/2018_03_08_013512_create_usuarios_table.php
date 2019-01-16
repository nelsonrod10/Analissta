<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empresaCliente_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('empleados_id')->unsigned();
            $table->foreign('empresaCliente_id')->references('id')->on('empresa_clientes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('empleados_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->enum('role_usuario',['Administrador','Digitador']);
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
        Schema::dropIfExists('usuarios');
    }
}
