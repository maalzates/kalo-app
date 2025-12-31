<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('macros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('kcal');
            $table->decimal('prot', 6, 2);
            $table->decimal('carb', 6, 2);
            $table->decimal('fat', 6, 2);
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('macros');
    }
};

