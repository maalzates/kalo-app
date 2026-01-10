<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\User\Application\Services\UserService;
use App\Modules\User\Presentation\Http\Requests\UpdateWeightRequest;
use Illuminate\Http\JsonResponse;

class UserWeightController extends ApiController
{
    public function __construct(
        private readonly UserService $service
    ) {
    }

    public function update(UpdateWeightRequest $request): JsonResponse
    {
        return $this->success($this->service->updateWeight($request->toDTO()), 'Weight updated successfully');
    }
}
