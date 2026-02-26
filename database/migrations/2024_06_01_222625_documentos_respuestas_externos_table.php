<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DocumentosRespuestasExternosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_respuestas_externos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_documento_externo')
            ->constrained('documentos_externos');
            $table->foreignId('id_documento')
            ->constrained('documentos');
            $table->boolean('aprobado')->default(false);
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
        Schema::dropIfExists('documentos_externos_respuestas');
    }
}
