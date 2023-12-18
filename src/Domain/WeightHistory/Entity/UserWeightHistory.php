<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\WeightHistory\Entity;

use Kcalculator\Domain\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_weightHistory')]
class UserWeightHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id;

    #[ORM\ManyToOne(targetEntity: "Kcalculator\Domain\User\Entity\User", inversedBy: "userWeightHistory")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Column]
    private \DateTime $datetime;

    #[ORM\Column(type: "float", nullable: false)]
    private float $userWeight;

    public function __construct()
    {
        $this->datetime = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDateTime(): \DateTime
    {
        return $this->datetime;
    }

    public function setDateTime(\DateTime $datetime): void
    {
        $this->datetime = $datetime;
    }

    public function getUserWeight(): ?float
    {
        return $this->userWeight;
    }

    public function setUserWeight($userWeight): void
    {
        $this->userWeight = $userWeight;
    }

    public function getUsers(): ?User
    {
        return $this->user;
    }

    public function setUsers(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}