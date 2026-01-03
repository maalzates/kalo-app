<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Ingredient;
use App\Models\MealLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasFactory;

    protected $table = 'recipes';

    protected $with = ['ingredients'];

    protected $fillable = [
        'name',
        'servings',
        'total_kcal',
        'total_prot',
        'total_carb',
        'total_fat',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'total_prot' => 'decimal:2',
            'total_carb' => 'decimal:2',
            'total_fat' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)
            ->withPivot('amount', 'unit')
            ->withTimestamps();
    }

    public function mealLogs(): HasMany
    {
        return $this->hasMany(MealLog::class);
    }
}

