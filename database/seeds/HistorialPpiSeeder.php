<?php

use Illuminate\Database\Seeder;

class HistorialPpiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\HistorialPpi::class, 50)->create();
    }
}
