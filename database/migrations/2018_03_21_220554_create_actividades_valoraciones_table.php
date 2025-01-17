<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadesValoracionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividades_valoraciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('peligro_id')->unsigned();
            $table->enum('medida',['N/A','eliminar','sustituir','ingenieria','epp_herramientas','administrativos'])->defaul('N/A');
            $table->string('nombre')->defaul('N/A');
            $table->string('cargo')->nullable();
            $table->longText('evidencias')->nullable();
            $table->longText('observaciones')->nullable();
            $table->enum('frecuencia',['N/A','semanal','quincenal','mensual','bimestral','trimestral','cuatrimestral','semestral','anual'])->defaul('N/A');
            $table->enum('estado',['','Programada','En ejecucion','Ejecutado'])->defaul('');
            $table->decimal('ejecucionTotal',8,2)->default(0);
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
            $table->foreign('peligro_id')->references('id')->on('peligros')->onDelete('cascade');
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
        Schema::dropIfExists('actividades_valoraciones');
    }
}
