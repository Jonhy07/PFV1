<?php

use Illuminate\Database\Seeder;

class HistorialClinicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\HistorialClinico::class, 500)->create();
    }
}
