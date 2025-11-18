<?php

namespace App\Command;

use App\Service\ExportService;
use App\Repository\PropertyRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:export:property',
    description: 'Export a property to one or all active gateways',
)]
class ExportPropertyCommand extends Command
{
    public function __construct(
        private ExportService $exportService,
        private PropertyRepository $propertyRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('property_id', InputArgument::REQUIRED, 'The property ID to export')
            ->addOption('gateway', 'g', InputOption::VALUE_OPTIONAL, 'Gateway code (optional, exports to all active if not provided)')
            ->addOption('all', null, InputOption::VALUE_NONE, 'Export to all active gateways');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $propertyId = $input->getArgument('property_id');
        $gatewayCode = $input->getOption('gateway');
        $exportAll = $input->getOption('all');

        $property = $this->propertyRepository->find($propertyId);

        if (!$property) {
            $io->error("Property not found: $propertyId");

            return Command::FAILURE;
        }

        try {
            if ($gatewayCode) {
                $export = $this->exportService->exportPropertyToGatewayByCode($property, $gatewayCode);
                $io->success("Property exported to {$export->getGateway()->getName()}");
                $io->writeln("Status: {$export->getStatus()->value}");
                $io->writeln("External ID: {$export->getExternalId()}");
            } elseif ($exportAll) {
                $exports = $this->exportService->exportPropertyToAllActiveGateways($property);
                $io->success('Property exported to ' . count($exports) . ' gateway(s)');

                foreach ($exports as $export) {
                    $io->writeln("  - {$export->getGateway()->getName()}: {$export->getStatus()->value}");
                }
            } else {
                $io->error('No gateway provided');

                return Command::FAILURE;
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Export failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
