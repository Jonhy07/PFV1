<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EscuelaSeeder::class);
        $this->call(AldeaSeeder::class);
        $this->call(SectorSeeder::class);
        $this->call(PersonaSinAccesoSeeder::class);
        $this->call(NinoSeeder::class);
        $this->call(EncargadoSeeder::class);
        $this->call(RelacionSeeder::class);
        $this->call(PadrinoSeeder::class);
        $this->call(ApadrinamientoSeeder::class);
        $this->call(PpiSeeder::class);
        $this->call(HistorialPpiSeeder::class);
        $this->call(CorrespondenciaSeeder::class);
        $this->call(FotografiaSeeder::class);
        $this->call(HistorialFotografiaSeeder::class);
        $this->call(GrupoPrestamoSeeder::class);
        $this->call(DestinoInversionSeeder::class);
        $this->call(Microprestamo::class);
        $this->call(DetalleIntegrante::class);
        $this->call(AbonoPrestamo::class);
    }
}
