<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiPasswordResetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_password_reset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained('users');
            $table->string('token_signature', 30);
            $table->integer('token_type')->default(10);
            $table->integer('used_type')->nullable();
            $table->timestamp('expires_at');
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
        Schema::dropIfExists('api_password_reset');
    }
}
