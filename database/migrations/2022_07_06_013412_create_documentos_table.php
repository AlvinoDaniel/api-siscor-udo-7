<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->mediumText('asunto');
            $table->string('nro_documento', 50)->nullable();
            $table->longText('contenido');
            $table->string('tipo_documento');
            $table->string('estatus');
            $table->dateTime('fecha_enviado')->nullable();
            $table->foreignId('departamento_id')
                ->constrained('departamentos');
            $table->foreignId('user_id')
                ->constrained('users');
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
        Schema::dropIfExists('documentos');
    }
}
