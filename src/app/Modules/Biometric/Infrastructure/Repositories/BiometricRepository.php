<?php

declare(strict_types=1);

namespace App\Modules\Biometric\Infrastructure\Repositories;

use App\Modules\Biometric\Domain\Contracts\BiometricRepositoryInterface;
use App\Modules\Biometric\Domain\Exceptions\BiometricCreationFailedException;
use App\Modules\Biometric\Domain\Exceptions\BiometricUpdateFailedException;
use App\Modules\Biometric\Infrastructure\Models\Biometric;
use Throwable;

class BiometricRepository implements BiometricRepositoryInterface
{
    public function findAll(array $filters): array
    {
        try {
            $query = Biometric::query();

            if (isset($filters['userId']) && $filters['userId'] !== null) {
                $query->where('user_id', $filters['userId']);
            }

            if (isset($filters['dateFrom']) && $filters['dateFrom'] !== null) {
                $query->whereDate('measured_at', '>=', $filters['dateFrom']);
            }

            if (isset($filters['dateTo']) && $filters['dateTo'] !== null) {
                $query->whereDate('measured_at', '<=', $filters['dateTo']);
            }

            $page = $filters['page'] ?? 1;
            $perPage = $filters['perPage'] ?? 15;

            $paginated = $query->orderBy('measured_at', 'desc')->paginate($perPage, ['*'], 'page', $page);

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
            $biometric = Biometric::find($id);
            return $biometric ? $biometric->toArray() : null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function create(array $data): array
    {
        try {
            $biometric = Biometric::create($data);
            return $biometric->toArray();
        } catch (Throwable $e) {
            throw BiometricCreationFailedException::fromException($e);
        }
    }

    public function update(string $id, array $data): bool
    {
        try {
            $biometric = Biometric::findOrFail($id);
            return $biometric->update($data);
        } catch (Throwable $e) {
            throw BiometricUpdateFailedException::fromException($id, $e);
        }
    }

    public function delete(string $id): bool
    {
        try {
            $biometric = Biometric::findOrFail($id);
            return $biometric->delete();
        } catch (Throwable $e) {
            return false;
        }
    }
}

