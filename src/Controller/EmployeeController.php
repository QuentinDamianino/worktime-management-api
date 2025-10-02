<?php

namespace App\Controller;

use App\Dto\Employee\CreateEmployeeDTO;
use App\Service\EmployeeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
final class EmployeeController extends AbstractController
{
    public function __construct(
        private readonly EmployeeService $employeeService,
    ) {}

    #[Route('/employees', name: 'create_employee', methods: ['POST'])]
    public function createEmployee(
        #[MapRequestPayload] CreateEmployeeDTO $dto
    ): JsonResponse
    {
        $employee = $this->employeeService->createEmployee($dto);

        return $this->json([
            'response' => [
                'id' => $employee->getId()->toString(),
            ],
            Response::HTTP_CREATED
        ]);
    }
}
