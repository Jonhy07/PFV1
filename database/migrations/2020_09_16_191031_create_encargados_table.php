<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEncargadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encargados', function (Blueprint $table) {
			$table->id();
			$table->string('ruta_imagen')->nullable();
			$table->boolean('estado')->default(1);
			$table->string('escolaridad')->nullable();
			$table->string('ocupacion')->nullable();
			$table->float('ingresos',8,2)->nullable();
			$table->foreignId('persona_sin_acceso_id')->constrained();
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
        Schema::dropIfExists('encargados');
    }
}
