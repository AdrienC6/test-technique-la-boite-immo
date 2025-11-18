<?php

namespace App\Service;

use App\Entity\Export;
use App\Entity\Gateway;
use App\Entity\Property;
use App\Service\Exporter\ExporterRegistry;
use App\Repository\GatewayRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExportService
{
    public function __construct(
        private ExporterRegistry $exporterRegistry,
        private EntityManagerInterface $entityManager,
        private GatewayRepository $gatewayRepository,
    ) {}

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
