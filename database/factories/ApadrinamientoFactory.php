<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Apadrinamiento;
use App\Nino;
use App\Padrino;
use Faker\Generator as Faker;

$factory->define(Apadrinamiento::class, function (Faker $faker) {
    return [
        // 'nino_id' => Nino::pluck('id')[$faker->numberBetween(1,Nino::count()-1)],
        'nino_id' => factory(App\Nino::class),
        // 'padrino_id' => Padrino::pluck('id')[$faker->numberBetween(1,Padrino::count()-1)],
        'padrino_id' => factory(App\Padrino::class),
        'estado' => $faker->numberBetween($min = 0, $max = 1),
        'created_at' => $faker->dateTime($max = 'now', $timezone = null),
        'updated_at' => $faker->dateTime($max = 'now', $timezone = null),
    ];
});
