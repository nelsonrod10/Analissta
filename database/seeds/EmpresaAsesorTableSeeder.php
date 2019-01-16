<?php

use Illuminate\Database\Seeder;

class EmpresaAsesorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\EmpresaAsesore::class)->create([
            'nombre' => "Cromac S.A.S",
            'nit' => "800900600",
            'telefono' => "3153284000",
            'web'  => 'www.cromacsas.com',
            'direccion' => 'Cra 4 26D 88',
        ]);
        
        factory(App\EmpresaAsesore::class,3)->create();
    }
}
