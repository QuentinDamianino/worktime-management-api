<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Employee\CreateEmployeeDTO;
use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

class EmployeeService
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly ObjectMapperInterface $objectMapper,
    ) {}

    public function createEmployee(CreateEmployeeDTO $dto): Employee
    {
        $employee = new Employee();
        $this->objectMapper->map($dto, $employee);

        $this->employeeRepository->save($employee);

        return $employee;
    }
}
