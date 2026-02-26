<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoImportanteDocumentosExternosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentos_externos', function (Blueprint $table) {
            $table->string('tipo')->nullable();
            $table->string('importante')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentos_externos', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->dropColumn('importante');
        });
    }
}
