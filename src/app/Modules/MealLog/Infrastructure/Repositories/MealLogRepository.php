<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Infrastructure\Repositories;

use App\Modules\MealLog\Domain\Contracts\MealLogRepositoryInterface;
use App\Modules\MealLog\Domain\Exceptions\MealLogCreationFailedException;
use App\Modules\MealLog\Domain\Exceptions\MealLogUpdateFailedException;
use App\Models\MealLog;
use Throwable;

class MealLogRepository implements MealLogRepositoryInterface
{
    public function findAll(array $filters): array
    {
        try {
            $query = MealLog::query();

            // Always filter by user_id from authenticated user
            if (isset($filters['userId']) && $filters['userId'] !== null) {
                $query->where('user_id', $filters['userId']);
            }

            if (isset($filters['dateFrom']) && $filters['dateFrom'] !== null) {
                $query->whereDate('logged_at', '>=', $filters['dateFrom']);
            }

            if (isset($filters['dateTo']) && $filters['dateTo'] !== null) {
                $query->whereDate('logged_at', '<=', $filters['dateTo']);
            }

            if (isset($filters['type']) && $filters['type'] !== null) {
                if ($filters['type'] === 'ingredient') {
                    $query->whereNotNull('ingredient_id')->whereNull('recipe_id');
                } elseif ($filters['type'] === 'recipe') {
                    $query->whereNotNull('recipe_id')->whereNull('ingredient_id');
                }
            }

            $page = $filters['page'] ?? 1;
            $perPage = $filters['perPage'] ?? 15;

            $paginated = $query->paginate($perPage, ['*'], 'page', $page);

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
            return ($mealLog = MealLog::where('id', $id)
                ->where('user_id', $userId)
                ->first()) ? $mealLog->toArray() : null;
        } catch (Throwable $exception) {
            return null;
        }
    }

    public function create(array $data): array
    {
        try {
            return MealLog::create($data)->toArray();
        } catch (Throwable $exception) {
            throw MealLogCreationFailedException::fromException($exception);
        }
    }

    public function update(string $id, array $data, int $userId): bool
    {
        try {
            return MealLog::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail()
                ->update($data);
        } catch (Throwable $exception) {
            throw MealLogUpdateFailedException::fromException($id, $exception);
        }
    }

    public function delete(string $id, int $userId): bool
    {
        try {
            return MealLog::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail()
                ->delete();
        } catch (Throwable $exception) {
            return false;
        }
    }
}

