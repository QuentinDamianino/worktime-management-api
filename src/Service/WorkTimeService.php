<?php

namespace App\Service;

use App\Dto\WorkTime\RegisterWorkTimeDTO;
use App\Entity\WorkTime;
use App\Repository\EmployeeRepository;
use App\Repository\WorkTimeRepository;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

class WorkTimeService
{
    public function __construct(
        private readonly WorkTimeRepository $workTimeRepository,
        private readonly EmployeeRepository $employeeRepository,
        private readonly ObjectMapperInterface $objectMapper,
    ) {}

    public function registerWorkTime(RegisterWorkTimeDTO $dto): WorkTime
    {
        $workTime = new WorkTime();

        $employee = $this->employeeRepository->find($dto->employeeId);
        if (!$employee) {
            throw new \Exception('Employee not found');
        }

        $workTime->setEmployee($employee);
        $this->objectMapper->map($dto, $workTime);

        $this->workTimeRepository->save($workTime);

        return $workTime;
    }
}
