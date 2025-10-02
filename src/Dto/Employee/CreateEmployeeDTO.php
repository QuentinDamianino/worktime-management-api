<?php

declare(strict_types=1);

namespace App\Dto\Employee;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\ObjectMapper\Attribute\Map;

class CreateEmployeeDTO
{
    #[Map(target: 'firstName')]
    #[Assert\NotBlank(message: 'First name cannot be empty')]
    #[Assert\Length(max: 100, maxMessage: 'Last name cannot be longer than 100 characters')]
    public string $firstName;

    #[Map(target: 'lastName')]
    #[Assert\NotBlank(message: 'Last name cannot be empty')]
    #[Assert\Length(max: 100, maxMessage: 'Last name cannot be longer than 100 characters')]
    public string $lastName;
}
