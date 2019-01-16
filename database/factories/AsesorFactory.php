<?php

use Faker\Generator as Faker;

$factory->define(App\Asesore::class, function (Faker $faker) {
    return [
        'empresaAsesor_id'  => App\EmpresaAsesore::all()->random()->id,
        'user_id'           => App\User::where('role','Asesor')->get()->random()->id,
        'nombre'            => $faker->name,
        'apellidos'         => $faker->lastName,
        'identificacion'    => $faker->numberBetween(),
        'email'             => $faker->email,
        'telefono'          => $faker->numberBetween(),
    ];
});
