<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Paciente;
use App\Beneficio;
use Faker\Generator as Faker;
$factory->define(Beneficio::class, function (Faker $faker) {
    return [
		'descripcion' => $faker->text($maxNbChars = 100), 
		'fecha_entrega' => $faker->dateTimeBetween($startDate = '-9 months', $endDate = 'now', $timezone = null),
        'paciente_id' => Paciente::pluck('id')[$faker->numberBetween(1,Paciente::count()-1)],
        'estado' => $faker->numberBetween($min = 0, $max = 1)
    ];
});
