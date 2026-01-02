<?php

declare(strict_types=1);

namespace App\Modules\Role\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Models\Permission;
use App\Modules\Role\Application\DTOs\AttachPermissionDTO;
use App\Modules\Role\Application\Services\RoleService;
use App\Models\Role;
use App\Modules\Role\Presentation\Http\Requests\AttachPermissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RolePermissionController extends ApiController
{
    public function __construct(
        private readonly RoleService $service
    ) {
    }

    public function attach(AttachPermissionRequest $request, Role $role): JsonResponse
    {
        return $this->success($this->service->attachPermission($request->toDTO()), 'Permission attached successfully', Response::HTTP_CREATED);
    }

    public function detach(Role $role, Permission $permission): JsonResponse
    {
        return $this->success($this->service->detachPermission(new AttachPermissionDTO(
            roleId: (string) $role->id,
            permissionId: (string) $permission->id,
        )), 'Permission detached successfully', Response::HTTP_NO_CONTENT);
    }
}

