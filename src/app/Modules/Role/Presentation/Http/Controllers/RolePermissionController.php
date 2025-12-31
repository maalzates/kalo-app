<?php

declare(strict_types=1);

namespace App\Modules\Role\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\Permission\Infrastructure\Models\Permission;
use App\Modules\Role\Application\DTOs\AttachPermissionDTO;
use App\Modules\Role\Application\Services\RoleService;
use App\Modules\Role\Domain\Exceptions\RoleNotFoundException;
use App\Modules\Role\Infrastructure\Models\Role;
use App\Modules\Role\Presentation\Http\Requests\AttachPermissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class RolePermissionController extends ApiController
{
    public function __construct(
        private readonly RoleService $service
    ) {
    }

    public function attach(AttachPermissionRequest $request, Role $role): JsonResponse
    {
        try {
            $dto = $request->toDTO();
            $this->service->attachPermission($dto);
            return $this->success(null, 'Permission attached successfully', Response::HTTP_CREATED);
        } catch (RoleNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to attach permission', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detach(Role $role, Permission $permission): JsonResponse
    {
        try {
            $dto = new AttachPermissionDTO(
                roleId: (string) $role->id,
                permissionId: (string) $permission->id,
            );

            $this->service->detachPermission($dto);
            return $this->success(null, 'Permission detached successfully', Response::HTTP_NO_CONTENT);
        } catch (RoleNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to detach permission', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

