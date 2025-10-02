<?php

namespace App\Controller;

use App\Dto\WorkTime\RegisterWorkTimeDTO;
use App\Service\WorkTimeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
final class WorkTimeController extends AbstractController
{
    public function __construct(
        private WorkTimeService $workTimeService,
    ) {}

    #[Route('/work-time', name: 'register_work_time', methods: ['POST'])]
    public function registerWorkTime(
        #[MapRequestPayload] RegisterWorkTimeDTO $registerWorkTimeDTO
    ): JsonResponse
    {
        $workTime = $this->workTimeService->registerWorkTime($registerWorkTimeDTO);

        return $this->json([
            'response' => [
                'message' => 'Czas pracy dodany'
            ]
        ]);
    }
}
