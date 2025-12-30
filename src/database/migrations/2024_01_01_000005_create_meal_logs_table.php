<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('ingredient_id')
                ->nullable()
                ->constrained('ingredients')
                ->onDelete('set null');
            $table->foreignId('recipe_id')
                ->nullable()
                ->constrained('recipes')
                ->onDelete('set null');
            $table->decimal('quantity', 8, 2);
            $table->enum('unit', ['g', 'ml', 'un', 'serving']);
            $table->timestamp('logged_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_logs');
    }
};

