<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitosLegalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisitos_legales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tipo_id')->unsigned();//viene de la tabla corto_plazo o largo_plazo
            $table->enum('tipo_texto',['Corto Plazo','Largo Plazo']);
            $table->longText('requisitos')->nullable();
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
        Schema::dropIfExists('requisitos_legales');
    }
}
