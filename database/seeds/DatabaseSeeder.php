<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(EmpresaAsesorTableSeeder::class);
        $this->call(AsesoreTableSeeder::class);
        //$this->call(EmpresaClienteTableSeed::class);
        //$this->call(UsuarioTableSeed::class);
    }
}
