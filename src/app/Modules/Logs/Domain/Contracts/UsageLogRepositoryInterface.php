<?php

declare(strict_types=1);

namespace App\Modules\Logs\Domain\Contracts;

use App\Modules\Logs\Application\DTO\CreateUsageLogDTO;
use App\Modules\Logs\Domain\Entities\UsageLog;

interface UsageLogRepositoryInterface
{
    public function create(CreateUsageLogDTO $dto): UsageLog;
}
