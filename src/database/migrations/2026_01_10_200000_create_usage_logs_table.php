<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ai_provider', 50); // 'gemini', 'openai', 'openrouter'
            $table->string('action_type', 100); // 'food_analysis', extensible
            $table->string('food_type', 50)->nullable(); // 'ingredient', 'recipe'
            $table->unsignedBigInteger('image_size_bytes');
            $table->unsignedInteger('input_tokens')->nullable();
            $table->unsignedInteger('output_tokens')->nullable();
            $table->unsignedInteger('total_tokens')->nullable();
            $table->decimal('cost_usd', 10, 6)->nullable();
            $table->unsignedInteger('request_duration_ms')->nullable();
            $table->enum('status', ['success', 'error'])->default('success');
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Índices para queries comunes
            $table->index(['user_id', 'created_at']);
            $table->index(['ai_provider', 'created_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_logs');
    }
};
