<?php

namespace App\Dto\WorkTime;

use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;
class RegisterWorkTimeDTO
{
    #[Assert\NotBlank(message: 'Uuid is required')]
    #[Assert\Uuid(message: 'Wrong Uuid format')]
    public string $employeeId;

    #[Map(target: 'startDateTime')]
    #[Assert\NotBlank(message: 'Start datetime is required')]
    public \DateTimeImmutable $startDateTime;

    #[Map(target: 'endDateTime')]
    #[Assert\NotBlank(message: 'End datetime is required')]
    public \DateTimeImmutable $endDateTime;
}
