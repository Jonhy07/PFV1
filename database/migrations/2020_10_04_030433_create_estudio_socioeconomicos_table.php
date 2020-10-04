<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstudioSocioeconomicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudio_socioeconomicos', function (Blueprint $table) {
			$table->id();
			$table->date('fecha_boleta');
			$table->date('fecha_ingreso');
			$table->float('total_ingresos',8,2);
			$table->text('alimentacion');
			$table->int('situacion_vivienda');
			$table->float('costo_vivienda',6,2);
			$table->boolean('luz');
			$table->boolean('agua');
			$table->boolean('drenaje');
			$table->int('cantidad_cuartos');
			$table->string('bano');
			$table->string('paredes');
			$table->string('techo');
			$table->string('piso');
			$table->text('evaluacion_diagnostico');
			$table->boolean('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estudio_socioeconomicos');
    }
}