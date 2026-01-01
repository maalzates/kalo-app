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

            $paginated = $query->with('user')->paginate($perPage, ['*'], 'page', $page);

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

    public function findById(string $id, int $userId): ?array
    {
        try {
            $macro = Macro::with('user')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->first();
            return $macro ? $macro->toArray() : null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function findByUserId(string $userId): ?array
    {
        try {
            $macro = Macro::where('user_id', $userId)->with('user')->first();
            return $macro ? $macro->toArray() : null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function create(array $data): array
    {
        try {
            $macro = Macro::create($data);
            return $macro->load('user')->toArray();
        } catch (Throwable $e) {
            throw MacroCreationFailedException::fromException($e);
        }
    }

    public function update(string $id, array $data, int $userId): bool
    {
        try {
            $macro = Macro::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();
            return $macro->update($data);
        } catch (Throwable $e) {
            throw MacroUpdateFailedException::fromException($id, $e);
        }
    }

    public function delete(string $id, int $userId): bool
    {
        try {
            $macro = Macro::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();
            return $macro->delete();
        } catch (Throwable $e) {
            return false;
        }
    }
}

