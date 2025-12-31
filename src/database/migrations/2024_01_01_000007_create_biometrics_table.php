<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biometrics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->decimal('weight', 5, 2);
            $table->decimal('fat_percentage', 4, 2)->nullable();
            $table->decimal('clean_mass', 5, 2)->nullable();
            $table->decimal('waist_circumference', 5, 2)->nullable();
            $table->timestamp('measured_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biometrics');
    }
};

