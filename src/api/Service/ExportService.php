<?php

namespace App\Service;

use App\DTO\PaginateAPIResult;
use App\Entity\Export;
use App\Entity\Gateway;
use App\Entity\Property;
use App\Repository\ExportRepository;
use App\Service\Exporter\ExporterRegistry;
use App\Repository\GatewayRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ExportService
{
    public function __construct(
        private ExporterRegistry $exporterRegistry,
        private EntityManagerInterface $entityManager,
        private GatewayRepository $gatewayRepository,
        private ExportRepository $exportRepository,
    ) {
    }

    public function exportPropertyToAllActiveGateways(Property $property): array
    {
        $exports = [];
        $gateways = $this->gatewayRepository->findBy(['active' => true]);

        foreach ($gateways as $gateway) {
            if ($this->exporterRegistry->has($gateway->getCode())) {
                $exports[] = $this->exportPropertyToGateway($property, $gateway);
            }
        }

        return $exports;
    }

    public function exportPropertyToGatewayByCode(Property $property, string $gatewayCode): Export
    {
        $gateway = $this->gatewayRepository->findOneBy(['code' => $gatewayCode]);

        if (!$gateway) {
            throw new \InvalidArgumentException("Gateway not found: $gatewayCode");
        }

        return $this->exportPropertyToGateway($property, $gateway);
    }

    public function getExports(Request $request): PaginateAPIResult
    {
        $filters = [
            'gateway_code' => $request->query->get('gateway_code'),
            'status' => $request->query->get('status'),
            'property_id' => $request->query->getInt('property_id'),
        ];

        $page = max(1, $request->query->getInt('page', 1));
        $limit = min(100, $request->query->getInt('limit', 20));

        [$exports, $total] = $this->exportRepository->findWithFilters($filters, $page, $limit);

        return new PaginateAPIResult($exports, $page, $limit, $total);
    }

    private function exportPropertyToGateway(Property $property, Gateway $gateway): Export
    {
        $exporter = $this->exporterRegistry->get($gateway->getCode());
        $result = $exporter->export($property);

        $export = new Export();
        $export
        ->setProperty($property)
        ->setGateway($gateway)
        ->setStatus($result->status)
        ->setExternalId($result->externalId)
        ->setResponse($result->response);

        $this->entityManager->persist($export);
        $this->entityManager->flush();

        return $export;
    }
}
