<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentoRespuestaDocumentosAsignadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentos_asignados', function (Blueprint $table) {
            $table->bigInteger('id_documento_respuesta')->unsigned()->default(null)->nullable()->after('departamento_id');
            $table->boolean('aprobado')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentos_asignados', function (Blueprint $table) {
            $table->dropColumn('id_documento_respuesta');
        });
    }
}
