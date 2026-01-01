<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealLog extends Model
{
    use HasFactory;

    protected $table = 'meal_logs';

    protected $with = ['ingredient', 'recipe'];

    protected $fillable = [
        'user_id',
        'ingredient_id',
        'recipe_id',
        'quantity',
        'unit',
        'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'logged_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}

