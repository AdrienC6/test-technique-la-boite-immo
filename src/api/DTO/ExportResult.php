<?php

namespace App\DTO;

use App\Enum\ExportStatus;

class ExportResult
{
    public function __construct(
        public readonly ExportStatus $status,
        public readonly ?string $externalId = null,
        public readonly ?array $response = null,
        public readonly ?string $errorMessage = null,
    ) {}
}
