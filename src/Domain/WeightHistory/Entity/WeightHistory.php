<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\WeightHistory\Entity;

use Kcalculator\Domain\User\Entity\User;

class WeightHistory
{
    private $id;

    private User $user;

    private \DateTime $datetime;

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