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
        Schema::create('routes', function (Blueprint $table) {
            $table->id()->comment('Id da rota');
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('perfil_route', function (Blueprint $table) {
            $table->integer('route_id');
            $table->integer('perfil_id');
            $table->primary(['route_id', 'perfil_id']);
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->foreign('perfil_id')->references('id')->on('perfis')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission');
        Schema::dropIfExists('routes');
    }
};
