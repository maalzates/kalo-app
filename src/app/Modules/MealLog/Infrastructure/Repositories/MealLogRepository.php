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
        } catch (Throwable $e) {
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

    public function findById(string $id): ?array
    {
        try {
            $mealLog = MealLog::find($id);
            return $mealLog ? $mealLog->toArray() : null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function create(array $data): array
    {
        try {
            $mealLog = MealLog::create($data);
            return $mealLog->toArray();
        } catch (Throwable $e) {
            throw MealLogCreationFailedException::fromException($e);
        }
    }

    public function update(string $id, array $data): bool
    {
        try {
            $mealLog = MealLog::findOrFail($id);
            return $mealLog->update($data);
        } catch (Throwable $e) {
            throw MealLogUpdateFailedException::fromException($id, $e);
        }
    }

    public function delete(string $id): bool
    {
        try {
            $mealLog = MealLog::findOrFail($id);
            return $mealLog->delete();
        } catch (Throwable $e) {
            return false;
        }
    }
}

