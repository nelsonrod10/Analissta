<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'name' => "Nelson Dario",
            'lastname' => "Rodriguez",
            'email' => "nelsonrod10@gmail.com",
            'role'  => 'Asesor',
            'password' => bcrypt('admin'),
        ]);
        
        factory(App\User::class)->create([
            'name' => "Diego Arturo",
            'lastname' => "Gaspar",
            'email' => "gaspar.diego@gmail.com",
            'role'  => 'Asesor',
            'password' => bcrypt('admin'),
        ]);
        
        /*factory(App\User::class)->create([
            'name' => "Diego Gaspar",
            'email' => "diego.gaspar@gmail.com",
            'role'  => 'Usuario',
            'password' => bcrypt('user'),
        ])*/
        
        //factory(App\User::class,7)->create();
    }
}
