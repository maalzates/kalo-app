<?php

declare(strict_types=1);

namespace App\Modules\Role\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\Role\Application\Services\RoleService;
use App\Models\Role;
use App\Modules\Role\Presentation\Http\Requests\CreateRoleRequest;
use App\Modules\Role\Presentation\Http\Requests\IndexRoleRequest;
use App\Modules\Role\Presentation\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RoleController extends ApiController
{
    public function __construct(
        private readonly RoleService $service
    ) {
    }

    public function index(IndexRoleRequest $request): JsonResponse
    {
        return $this->success($this->service->findAll($request->toDTO()));
    }

    public function show(Role $role): JsonResponse
    {
        return $this->success($this->service->findById((string) $role->id));
    }

    public function store(CreateRoleRequest $request): JsonResponse
    {
        return $this->success($this->service->create($request->toDTO()), 'Role created successfully', Response::HTTP_CREATED);
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        return $this->success($this->service->update($request->toDTO()), 'Role updated successfully');
    }

    public function destroy(Role $role): JsonResponse
    {
        return $this->success($this->service->delete((string) $role->id), 'Role deleted successfully', Response::HTTP_NO_CONTENT);
    }
}

