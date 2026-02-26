<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosAsignadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_asignados', function (Blueprint $table) {
            $table->id();
            $table->morphs('documento');
            $table->foreignId('departamento_id')
                ->constrained('departamentos');
            $table->boolean('leido')->default(false);
            $table->date('fecha_leido')->nullable()->default(null);
            $table->date('fecha_asignado')->default(null);;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentos_asignados');
    }
}
