<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccidentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accidentes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sistema_id')->unsigned();
            $table->integer('centrosTrabajo_id')->unsigned();
            $table->integer('proceso_id')->unsigned();
            $table->date('fechaAccidente');
            $table->time('horaAccidente');
            $table->string('cargoResponsable');
            $table->enum('clasificacion',['Casi Accidente','Accidente','Enfermedad Laboral']);
            $table->longText('lugar');
            $table->longText('descripcion');
            $table->integer('accidentado_id')->unsigned()->defaul(0);
            $table->enum('tipo_evento',['','Casi Accidente','Muerte','Dias Perdidos','Trabajo Restringido','Tratamiento Medico','Primeros Auxilios','Enfermedad Laboral'])->default('');
            $table->enum('accidente_grave',['Si','No'])->default('No');
            $table->enum('incapacidad',['Si','No'])->default('No');
            $table->enum('afectacion',['','Personas','Medio Ambiente','Equipos'])->default('');
            $table->string('nombre_empresa')->default('');
            $table->enum('empleado_tipo',['','Directo','Contratista'])->default('');
            $table->enum('jornada',['','Diurna','Nocturna'])->default('');
            $table->enum('labor_habitual',['','Si','No'])->default('');
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
            $table->foreign('centrosTrabajo_id')->references('id')->on('centros_trabajos')->onDelete('cascade');
            $table->foreign('proceso_id')->references('id')->on('procesos')->onDelete('cascade');
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
        Schema::dropIfExists('accidentes');
    }
}
