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
        Schema::create('perfis', function (Blueprint $table) {
            $table->id()->comment('Id do perfil');
            $table->string('name')->unique()->comment('Nome do perfil');
            $table->string('description')->comment('Descrição do perfil');
            $table->boolean('hide')->default(false)->comment('Ocultar na listagem');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfis');
    }
};
