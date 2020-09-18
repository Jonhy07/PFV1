<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Apadrinamiento;
use App\Nino;
use App\Padrino;
use Faker\Generator as Faker;

$factory->define(Apadrinamiento::class, function (Faker $faker) {
    return [
        'nino_id' => $faker->numberBetween($min = 1, $max = 50),
        //'padrino_id' => $faker->numberBetween($min = 1, $max = 50),
        'padrino_id' => Padrino::pluck('id')[$faker->numberBetween(1,Padrino::count()-1)],
        'estado' => $faker->numberBetween($min = 0, $max = 1),
        'created_at' => $faker->dateTime($max = 'now', $timezone = null),
        'updated_at' => $faker->dateTime($max = 'now', $timezone = null),
    ];
});
