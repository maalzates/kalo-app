<?php

declare(strict_types=1);

namespace App\Modules\Logs\Infrastructure\Persistence\Eloquent;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageLogModel extends Model
{
    use HasFactory;

    protected $table = 'usage_logs';

    protected $fillable = [
        'user_id',
        'ai_provider',
        'action_type',
        'food_type',
        'image_size_bytes',
        'input_tokens',
        'output_tokens',
        'total_tokens',
        'cost_usd',
        'request_duration_ms',
        'status',
        'error_message',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'image_size_bytes' => 'integer',
            'input_tokens' => 'integer',
            'output_tokens' => 'integer',
            'total_tokens' => 'integer',
            'cost_usd' => 'decimal:6',
            'request_duration_ms' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\UsageLogFactory::new();
    }
}
