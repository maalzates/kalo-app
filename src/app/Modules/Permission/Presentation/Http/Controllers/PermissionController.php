<?php

declare(strict_types=1);

namespace App\Modules\Permission\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\Permission\Application\Services\PermissionService;
use App\Models\Permission;
use App\Modules\Permission\Presentation\Http\Requests\CreatePermissionRequest;
use App\Modules\Permission\Presentation\Http\Requests\IndexPermissionRequest;
use App\Modules\Permission\Presentation\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PermissionController extends ApiController
{
    public function __construct(
        private readonly PermissionService $service
    ) {
    }

    public function index(IndexPermissionRequest $request): JsonResponse
    {
        return $this->success($this->service->findAll($request->toDTO()));
    }

    public function show(Permission $permission): JsonResponse
    {
        return $this->success($this->service->findById((string) $permission->id));
    }

    public function store(CreatePermissionRequest $request): JsonResponse
    {
        return $this->success($this->service->create($request->toDTO()), 'Permission created successfully', Response::HTTP_CREATED);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        return $this->success($this->service->update($request->toDTO()), 'Permission updated successfully');
    }

    public function destroy(Permission $permission): JsonResponse
    {
        return $this->success($this->service->delete((string) $permission->id), 'Permission deleted successfully', Response::HTTP_NO_CONTENT);
    }
}

