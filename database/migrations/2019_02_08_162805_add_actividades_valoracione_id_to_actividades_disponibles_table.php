<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActividadesValoracioneIdToActividadesDisponiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actividades_disponibles', function (Blueprint $table) {
            $table->string("actividades_valoracione_id")->after("clasificacion_peligro_id")->defatul("0");
        });
        Schema::table('capacitaciones_disponibles', function (Blueprint $table) {
            $table->string("capacitaciones_valoracione_id")->after("clasificacion_peligro_id")->defatul("0");
        });
        Schema::table('inspecciones_disponibles', function (Blueprint $table) {
            $table->string("inspecciones_valoracione_id")->after("clasificacion_peligro_id")->defatul("0");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('actividades_disponibles', function (Blueprint $table) {
            $table->dropColumn("actividades_valoracione_id");
        });
        Schema::table('capacitaciones_disponibles', function (Blueprint $table) {
            $table->dropColumn("capacitaciones_valoracione_id");
        });
        Schema::table('inspecciones_disponibles', function (Blueprint $table) {
            $table->dropColumn("inspecciones_valoracione_id");
        });
    }
}
