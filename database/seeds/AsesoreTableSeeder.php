<?php

use Illuminate\Database\Seeder;

class AsesoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\Asesore::class,2)->create();
        factory(App\Asesore::class)->create([
            'empresaAsesor_id' => 3,
            'user_id' => 1,
            'nombre' => "Nelson Dario",
            'apellidos' => "Rodriguez",
            'identificacion' => 80057776,
            'email' => "nelsonrod10@gmail.com",
            'telefono' => 3167585671
        ]);
        
        factory(App\Asesore::class)->create([
            'empresaAsesor_id' => 1,
            'user_id' => 2,
            'nombre' => "Diego Arturo",
            'apellidos' => "Gaspar",
            'identificacion' => 80740685,
            'email' => "gaspar.diego@gmail.com",
            'telefono' => 3153284000
        ]);
    }
}
