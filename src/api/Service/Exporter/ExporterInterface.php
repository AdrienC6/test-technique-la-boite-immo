<?php

namespace App\Service\Exporter;

use App\DTO\ExportResult;
use App\Entity\Property;

interface ExporterInterface
{
    /**
     * Export a property to the external platform
     */
    public function export(Property $property): ExportResult;

    /**
     * Get the gateway code identifier
     */
    public function getGatewayCode(): string;
}
