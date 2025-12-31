<?php

declare(strict_types=1);

namespace App\Modules\Role\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\Role\Application\Services\RoleService;
use App\Modules\Role\Domain\Exceptions\RoleInUseException;
use App\Modules\Role\Domain\Exceptions\RoleNotFoundException;
use App\Models\Role;
use App\Modules\Role\Presentation\Http\Requests\CreateRoleRequest;
use App\Modules\Role\Presentation\Http\Requests\IndexRoleRequest;
use App\Modules\Role\Presentation\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class RoleController extends ApiController
{
    public function __construct(
        private readonly RoleService $service
    ) {
    }

    public function index(IndexRoleRequest $request): JsonResponse
    {
        $results = $this->service->findAll($request->toDTO());

        return $this->success($results);
    }

    public function show(Role $role): JsonResponse
    {
        try {
            $roleData = $this->service->findById((string) $role->id);
            return $this->success($roleData);
        } catch (RoleNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        }
    }

    public function store(CreateRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->service->create($request->toDTO());
            return $this->success($role, 'Role created successfully', Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return $this->error('Failed to create role', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        try {
            $updatedRole = $this->service->update($request->toDTO());
            return $this->success($updatedRole, 'Role updated successfully');
        } catch (RoleNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to update role', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Role $role): JsonResponse
    {
        try {
            $this->service->delete((string) $role->id);
            return $this->success(null, 'Role deleted successfully', Response::HTTP_NO_CONTENT);
        } catch (RoleNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (RoleInUseException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to delete role', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

