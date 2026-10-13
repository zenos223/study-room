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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('building');
            $table->string('location')->nullable();
            $table->string('floor')->nullable();
            $table->unsignedSmallInteger('capacity');
            $table->string('category');
            $table->text('description')->nullable();
            $table->json('equipment')->nullable();
            $table->text('image_url')->nullable();
            $table->boolean('is_available')->default(true)->index();
            $table->timestamps();

            $table->index(['building', 'capacity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
