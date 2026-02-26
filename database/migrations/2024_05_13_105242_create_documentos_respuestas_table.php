<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosRespuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_documento')
                ->constrained('documentos');
            $table->foreignId('documento_respuesta')
                ->constrained('documentos');
            $table->boolean('aprobado')->default(false);
            $table->date('fecha_respuesta')->nullable()->default(null);
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
        Schema::dropIfExists('documentos_respuestas');
    }
}
