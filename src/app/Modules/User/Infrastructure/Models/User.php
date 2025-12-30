<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birth_date',
        'gender',
        'height',
        'google_id',
        'auth_provider',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
        ];
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(\App\Modules\Ingredient\Infrastructure\Models\Ingredient::class);
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(\App\Modules\Recipe\Infrastructure\Models\Recipe::class);
    }

    public function mealLogs(): HasMany
    {
        return $this->hasMany(\App\Modules\MealLog\Infrastructure\Models\MealLog::class);
    }

    public function biometrics(): HasMany
    {
        return $this->hasMany(\App\Modules\Biometric\Infrastructure\Models\Biometric::class);
    }
}

