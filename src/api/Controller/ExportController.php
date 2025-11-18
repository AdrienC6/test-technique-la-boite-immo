<?php

namespace App\Controller;

use App\Entity\Property;
use App\Service\ExportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/exports', name: 'api_exports_')]
class ExportController extends AbstractController
{
    public function __construct(
        private readonly ExportService $exportService
    ){}

    #[Route('/property/{id}', name: 'create', methods: ['POST'])]
    public function export(Property $property, Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true) ?? [];
        $gatewayCode = $data['gateway_code'] ?? null;

        try {
            $result = $gatewayCode
                ? $this->exportService->exportPropertyToGatewayByCode($property, $gatewayCode)
                : $this->exportService->exportPropertyToAllActiveGateways($property);

            $message = $gatewayCode
                ? 'Property exported successfully'
                : 'Property exported to all active gateways';

            return $this->json([
                'message' => $message,
                'data' => $result,
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route(name: 'list', methods: ['GET'])]
    public function list(Request $request): JsonResponse {
        try {
            $exports = $this->exportService->getExports($request);
            return $this->json($exports);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
