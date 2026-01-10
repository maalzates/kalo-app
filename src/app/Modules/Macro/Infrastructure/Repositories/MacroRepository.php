<?php

declare(strict_types=1);

namespace App\Modules\Macro\Infrastructure\Repositories;

use App\Modules\Macro\Domain\Contracts\MacroRepositoryInterface;
use App\Modules\Macro\Domain\Exceptions\MacroCreationFailedException;
use App\Modules\Macro\Domain\Exceptions\MacroUpdateFailedException;
use App\Models\Macro;
use Throwable;

class MacroRepository implements MacroRepositoryInterface
{
    public function findAll(array $filters): array
    {
        try {
            $query = Macro::query();

            // Always filter by user_id from authenticated user
            if (isset($filters['user_id']) && $filters['user_id'] !== null) {
                $query->where('user_id', $filters['user_id']);
            }

            $page = $filters['page'] ?? 1;
            $perPage = $filters['perPage'] ?? 15;

            // Ordenar por created_at descendente y luego por id descendente para garantizar orden consistente
            // incluso cuando hay múltiples registros con el mismo timestamp
            $query->orderBy('created_at', 'desc')
                  ->orderBy('id', 'desc');

            $paginated = $query->with('user')->paginate($perPage, ['*'], 'page', $page);

            return [
                'data' => $paginated->items(),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'per_page' => $paginated->perPage(),
                    'total' => $paginated->total(),
                ],
            ];
        } catch (Throwable $exception) {
            return [
                'data' => [],
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 15,
                    'total' => 0,
                ],
            ];
        }
    }

    public function findById(string $id, int $userId): ?array
    {
        try {
            return ($macro = Macro::with('user')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->first()) ? $macro->toArray() : null;
        } catch (Throwable $exception) {
            return null;
        }
    }

    public function findByUserId(string $userId): ?array
    {
        try {
            return ($macro = Macro::where('user_id', $userId)->with('user')->first()) ? $macro->toArray() : null;
        } catch (Throwable $exception) {
            return null;
        }
    }

    public function findByUserIdAndDate(int $userId, string $date): ?Macro
    {
        try {
            return Macro::where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->first();
        } catch (Throwable $exception) {
            return null;
        }
    }

    public function create(array $data): array
    {
        try {
            return Macro::create($data)->load('user')->toArray();
        } catch (Throwable $exception) {
            throw MacroCreationFailedException::fromException($exception);
        }
    }

    public function update(string $id, array $data, int $userId): bool
    {
        try {
            return Macro::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail()
                ->update($data);
        } catch (Throwable $exception) {
            throw MacroUpdateFailedException::fromException($id, $exception);
        }
    }

    public function delete(string $id, int $userId): bool
    {
        try {
            return Macro::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail()
                ->delete();
        } catch (Throwable $exception) {
            return false;
        }
    }
}

