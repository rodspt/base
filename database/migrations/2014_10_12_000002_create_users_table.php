<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('cpf',11)->primary()->unique()->comment('CPF do usuário');
            $table->string('nome')->comment('Nome do usuário');
            $table->string('email')->unique()->comment('Email do usuário');
            $table->string('password');

            $table->integer('perfil_id');
            $table->foreign('perfil_id')->references('id')->on('perfis')->onDelete('cascade');

            $table->timestamp('email_verified_at')->nullable();
            $table->string('cpf_aprovacao',11)->nullable()->comment('CPF do autorizador');
            $table->string('cpf_bloqueio',11)->nullable()->comment('CPF do bloqueio');
            $table->timestamp('aprovacao_at')->nullable()->comment('Data de aprovação');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
