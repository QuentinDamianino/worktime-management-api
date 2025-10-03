<?php

namespace App\Service;

use App\Dto\WorkTime\RegisterWorkTimeDTO;
use App\Dto\WorkTime\WorkTimeSummaryDTO;
use App\Entity\Employee;
use App\Entity\WorkTime;
use App\Repository\EmployeeRepository;
use App\Repository\WorkTimeRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Uid\Uuid;

class WorkTimeService
{
    private const int MONTHLY_HOURS_LIMIT = 40;
    private const int HOURLY_RATE = 20;
    private const int OVERTIME_RATE_MULTIPLIER = 2;

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
            throw new NotFoundHttpException('Employee not found');
        }

        $workTime->setEmployee($employee);
        $workTime->setStartDate($dto->startDateTime);
        $this->objectMapper->map($dto, $workTime);

        $this->workTimeRepository->save($workTime);

        return $workTime;
    }

    public function getWorkTimeSummary(WorkTimeSummaryDTO $dto): array
    {
        $employee = $this->employeeRepository->find(Uuid::fromString($dto->employeeId));

        if (!$employee) {
            throw new NotFoundHttpException('Employee not found');
        }

        $isMonthQuery = preg_match('/^\d{4}-\d{2}$/', $dto->date);
        $isDayQuery = preg_match('/^\d{4}-\d{2}-\d{2}$/', $dto->date);

        if ($isMonthQuery) {
            return $this->getMonthSummary($employee, $dto->date);
        } elseif ($isDayQuery) {
            return $this->getDaySummary($employee, $dto->date);
        }

        throw new BadRequestHttpException('Invalid date format');
    }

    private function getDaySummary(Employee $employee, string $date): array
    {
        $startDate = \DateTimeImmutable::createFromFormat('Y-m-d', $date);

        $workTimes = $this->workTimeRepository->createQueryBuilder('wt')
            ->where('wt.employee = :employee')
            ->andWhere('wt.startDate = :startDate')
            ->setParameter('employee', $employee->getId()->toBinary())
            ->setParameter('startDate', $startDate->format('Y-m-d'))
            ->getQuery()
            ->getResult();

        $totalHours = 0;

        foreach ($workTimes as $workTime) {
            $totalHours += $this->calculateHours(
                $workTime->getStartDateTime(),
                $workTime->getEndDateTime()
            );
        }

        $totalAmount = $totalHours * self::HOURLY_RATE;

        return [
            'totalAmount' => number_format($totalAmount, 0, '.', '') . ' PLN',
            'totalHours' => $totalHours,
            'rate' => self::HOURLY_RATE . ' PLN',
        ];
    }

    private function getMonthSummary(Employee $employee, string $date): array
    {
        [$year, $month] = explode('-', $date);

        $startOfMonth = \DateTimeImmutable::createFromFormat('Y-m-d', "$year-$month-01");
        $endOfMonth = $startOfMonth->modify('last day of this month');

        $workTimes = $this->workTimeRepository->createQueryBuilder('wt')
            ->where('wt.employee = :employee')
            ->andWhere('wt.startDate >= :startDate')
            ->andWhere('wt.startDate <= :endDate')
            ->setParameter('employee', $employee->getId()->toBinary())
            ->setParameter('startDate', $startOfMonth->format('Y-m-d'))
            ->setParameter('endDate', $endOfMonth->format('Y-m-d'))
            ->getQuery()
            ->getResult();

        $totalHours = 0;

        foreach ($workTimes as $workTime) {
            $totalHours += $this->calculateHours(
                $workTime->getStartDateTime(),
                $workTime->getEndDateTime()
            );
        }

        $normalHours = min($totalHours, self::MONTHLY_HOURS_LIMIT);
        $overtimeHours = max(0, $totalHours - self::MONTHLY_HOURS_LIMIT);

        $normalAmount = $normalHours * self::HOURLY_RATE;
        $overtimeAmount = $overtimeHours * self::HOURLY_RATE * self::OVERTIME_RATE_MULTIPLIER;
        $totalAmount = $normalAmount + $overtimeAmount;

        return [
            'normalHours' => $normalHours,
            'rate' => self::HOURLY_RATE . ' PLN',
            'overtimeHours' => $overtimeHours,
            'overtimeRateMultiplier' => (self::HOURLY_RATE * self::OVERTIME_RATE_MULTIPLIER) . ' PLN',
            'totalAmount' => number_format($totalAmount, 0, '.', '') . ' PLN',
        ];
    }

    private function calculateHours(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): float
    {
        $diff = $endDate->getTimestamp() - $startDate->getTimestamp();
        $hours = $diff / 3600;

        return round($hours * 2) / 2;
    }
}
