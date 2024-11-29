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
        Schema::create('recipe_recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->foreignId('child_recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->unique(['parent_recipe_id', 'child_recipe_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_recipes');
    }
};
