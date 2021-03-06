<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupoPrestamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo_prestamos', function (Blueprint $table) {
			$table->id();
			$table->string('nombre');
			$table->string('descripcion');
			$table->float('interes_ultimo_prestamo',8,2);
			$table->float('cantidad_ultimo_prestamo',8,2)->default(0);
			$table->float('cantidad_prestamo_actual',8,2)->default(0);
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
        Schema::dropIfExists('grupo_prestamos');
    }
}
