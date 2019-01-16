<?php

use Faker\Generator as Faker;

$factory->define(App\EmpresaAsesore::class, function (Faker $faker) {
    return [
        'nombre'    => $faker->company,
        'nit'       => $faker->numberBetween(),
        'telefono'  => $faker->numberBetween(),
        'web'       => $faker->domainWord,
        'direccion' => $faker->address,
        
    ];
});
