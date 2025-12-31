<?php

declare(strict_types=1);

namespace App\Modules\Macro\Infrastructure\Models;

use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Macro extends Model
{
    use HasFactory;

    protected $table = 'macros';

    protected $fillable = [
        'kcal',
        'prot',
        'carb',
        'fat',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'prot' => 'decimal:2',
            'carb' => 'decimal:2',
            'fat' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

