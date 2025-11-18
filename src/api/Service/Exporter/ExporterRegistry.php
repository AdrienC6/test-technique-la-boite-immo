<?php

namespace App\Service\Exporter;

class ExporterRegistry
{
    private array $exporters = [];

    public function register(ExporterInterface $exporter): void
    {
        $this->exporters[$exporter->getGatewayCode()] = $exporter;
    }

    public function get(string $gatewayCode): ExporterInterface
    {
        if (!isset($this->exporters[$gatewayCode])) {
            throw new \InvalidArgumentException("Exporter not found for gateway: $gatewayCode");
        }
        return $this->exporters[$gatewayCode];
    }

    public function all(): array
    {
        return $this->exporters;
    }

    public function has(string $gatewayCode): bool
    {
        return isset($this->exporters[$gatewayCode]);
    }
}
