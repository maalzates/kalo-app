<?php

declare(strict_types=1);

namespace App\Modules\Permission\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\Permission\Application\Services\PermissionService;
use App\Modules\Permission\Domain\Exceptions\PermissionInUseException;
use App\Modules\Permission\Domain\Exceptions\PermissionNotFoundException;
use App\Models\Permission;
use App\Modules\Permission\Presentation\Http\Requests\CreatePermissionRequest;
use App\Modules\Permission\Presentation\Http\Requests\IndexPermissionRequest;
use App\Modules\Permission\Presentation\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class PermissionController extends ApiController
{
    public function __construct(
        private readonly PermissionService $service
    ) {
    }

    public function index(IndexPermissionRequest $request): JsonResponse
    {
        $results = $this->service->findAll($request->toDTO());

        return $this->success($results);
    }

    public function show(Permission $permission): JsonResponse
    {
        try {
            $permissionData = $this->service->findById((string) $permission->id);
            return $this->success($permissionData);
        } catch (PermissionNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        }
    }

    public function store(CreatePermissionRequest $request): JsonResponse
    {
        try {
            $permission = $this->service->create($request->toDTO());
            return $this->success($permission, 'Permission created successfully', Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return $this->error('Failed to create permission', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        try {
            $updatedPermission = $this->service->update($request->toDTO());
            return $this->success($updatedPermission, 'Permission updated successfully');
        } catch (PermissionNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to update permission', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Permission $permission): JsonResponse
    {
        try {
            $this->service->delete((string) $permission->id);
            return $this->success(null, 'Permission deleted successfully', Response::HTTP_NO_CONTENT);
        } catch (PermissionNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (PermissionInUseException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to delete permission', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

