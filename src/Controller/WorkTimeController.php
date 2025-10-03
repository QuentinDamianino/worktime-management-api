<?php

namespace App\Controller;

use App\Dto\WorkTime\RegisterWorkTimeDTO;
use App\Dto\WorkTime\WorkTimeSummaryDTO;
use App\Service\WorkTimeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
final class WorkTimeController extends AbstractController
{
    public function __construct(
        private readonly WorkTimeService $workTimeService,
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

    #[Route('/work-time/summary', name: 'work_time_summary', methods: ['GET'])]
    public function getWorkTimeSummary(
        #[MapRequestPayload] WorkTimeSummaryDTO $workTimeSummaryDTO
    ): JsonResponse
    {
        $summary = $this->workTimeService->getWorkTimeSummary($workTimeSummaryDTO);

        return $this->json([
            'response' => $summary
        ]);
    }
}
