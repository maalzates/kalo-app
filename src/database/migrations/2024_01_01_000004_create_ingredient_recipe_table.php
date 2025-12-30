<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredient_recipe', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('recipe_id')
                ->constrained('recipes')
                ->onDelete('cascade');
            $table->foreignId('ingredient_id')
                ->constrained('ingredients')
                ->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->enum('unit', ['g', 'ml', 'un']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_recipe');
    }
};

