<?php

namespace App\Service\Exporter;

use App\DTO\ExportResult;
use App\Entity\Property;
use App\Enum\ExportStatus;

class SeLogerExporter implements ExporterInterface
{
    public function export(Property $property): ExportResult
    {
        // Fake API Call
        try {
            $externalId = 'SELOGER_' . $property->getId() . '_' . time();

            $response = [
                'platform' => 'seloger',
                'property_id' => $property->getId(),
                'external_id' => $externalId,
                'title' => $property->getTitle(),
                'price' => $property->getPrice(),
                'city' => $property->getCity(),
                'exported_at' => date('c'),
            ];

            return new ExportResult(
                status: ExportStatus::COMPLETED,
                externalId: $externalId,
                response: $response,
            );
        } catch (\Exception $e) {
            return new ExportResult(
                status: ExportStatus::FAILED,
                errorMessage: $e->getMessage(),
            );
        }
    }

    public function getGatewayCode(): string
    {
        return 'seloger';
    }
}
