<?php

namespace App\Entity;

use App\Repository\WorkTimeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WorkTimeRepository::class)]
class WorkTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Employee::class, cascade: ['persist', 'remove'], inversedBy: 'workTimes')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotBlank(message: 'Employee is required')]
    private ?Employee $employee = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Assert\NotBlank(message: 'Start datetime is required')]
    private ?\DateTimeImmutable $startDateTime = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Assert\NotBlank(message: 'End datetime is required')]
    private ?\DateTimeImmutable $endDateTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getStartDateTime(): ?\DateTimeImmutable
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(\DateTimeImmutable $startDateTime): static
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getEndDateTime(): ?\DateTimeImmutable
    {
        return $this->endDateTime;
    }

    public function setEndDateTime(\DateTimeImmutable $endDateTime): static
    {
        $this->endDateTime = $endDateTime;

        return $this;
    }
}
