<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Usuario;
use App\Paciente;
use App\SalidaMedicamento;
use Faker\Generator as Faker;

$factory->define(SalidaMedicamento::class, function (Faker $faker) {
    return [
        'fecha_salida' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'descripcion' => $faker->text($maxNbChars = 100),
        'usuario_id' => Usuario::pluck('id')[$faker->numberBetween(1,Usuario::count()-1)],
        'paciente_id' => Paciente::pluck('id')[$faker->numberBetween(1,Paciente::count()-1)],
        'estado' => $faker->numberBetween($min = 0, $max = 1),
        
    ];
});
