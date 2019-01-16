<?php

use Illuminate\Database\Seeder;

class EmpresaClienteTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\EmpresaCliente::class,10)->create();
    }
}
