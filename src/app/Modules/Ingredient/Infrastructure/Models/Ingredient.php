<?php

declare(strict_types=1);

namespace App\Modules\Ingredient\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $table = 'ingredients';

    protected $fillable = [
        'name',
        'amount',
        'unit',
        'kcal',
        'prot',
        'carb',
        'fat',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'prot' => 'decimal:2',
            'carb' => 'decimal:2',
            'fat' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\User\Infrastructure\Models\User::class);
    }

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(\App\Modules\Recipe\Infrastructure\Models\Recipe::class)
            ->withPivot('amount', 'unit')
            ->withTimestamps();
    }

    public function mealLogs(): HasMany
    {
        return $this->hasMany(\App\Modules\MealLog\Infrastructure\Models\MealLog::class);
    }
}

