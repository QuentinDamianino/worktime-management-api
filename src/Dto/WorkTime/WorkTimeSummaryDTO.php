<?php

namespace App\Dto\WorkTime;

use Symfony\Component\Validator\Constraints as Assert;

class WorkTimeSummaryDTO
{
    #[Assert\NotBlank(message: 'Uuis is required')]
    #[Assert\Uuid(message: 'Wrong Uuid format')]
    public string $employeeId;

    #[Assert\NotBlank(message: 'Date is required')]
    #[Assert\Regex(
        pattern: '/^\d{4}-\d{2}(-\d{2})?$/',
        message: 'Date must be in format YYYY-MM or YYYY-MM-DD'
    )]
    public string $date;
}
