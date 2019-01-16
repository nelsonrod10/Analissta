<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa_clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asesor_id')->unsigned();
            $table->foreign('asesor_id')->references('id')->on('asesores')->onDelete('cascade');
            $table->string('nombre')->unique();
            $table->bigInteger('nit')->unique()->unsigned();
            $table->bigInteger('telefono')->unsigned();
            $table->string('direccion')->unique();
            $table->string('ciudad');
            $table->date('fechaFundacion');
            $table->string('ciiu');
            $table->string('activEconomica');
            $table->string('ARL');
            $table->date('fechaAfiliacionARL');
            $table->string('cajaCompensacion')->nullable();
            $table->date('fechaAfiliacionCajaComp')->nullable();
            $table->string('sector');
            $table->longText('descActivEconomica');
            $table->integer('totalEmpleados')->unsigned();
            $table->integer('totalEmpleadosDirectos')->unsigned()->default(0);
            $table->integer('totalEmpleadosTemporales')->unsigned()->default(0);
            $table->integer('totalEmpleadosPrestServicios')->unsigned()->default(0);
            $table->enum('jornadaTrabajo',['Lunes a Viernes','Lunes a Sabado'])->default('Lunes a Viernes');
            $table->time('horaLlegada')->default('07:00:00');
            $table->time('horaSalida')->default('17:00:00');
            $table->enum('horasAlmuerzo',['0.5','1','2','3'])->default('1');
            $table->enum('tipoValoracion',['Matriz por Centro','Matriz General'])->default('Matriz General');
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
        Schema::dropIfExists('empresa_clientes');
    }
}
