<?php

declare(strict_types=1);

namespace App\Modules\Biometric\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Biometric extends Model
{
    use HasFactory;

    protected $table = 'biometrics';

    protected $fillable = [
        'user_id',
        'weight',
        'fat_percentage',
        'clean_mass',
        'waist_circumference',
        'measured_at',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'fat_percentage' => 'decimal:2',
            'clean_mass' => 'decimal:2',
            'waist_circumference' => 'decimal:2',
            'measured_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\User\Infrastructure\Models\User::class);
    }
}

