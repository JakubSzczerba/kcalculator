<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_weightHistory")
 */
class UserWeightHistory
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Kcalculator\Entity\User", inversedBy="userWeightHistory")
     * @ORM\JoinColumn(name="user_id", nullable=false, referencedColumnName="id")
     */
    private User $user;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTime $datetime;

    /**
     * @ORM\Column(type="float")
     */
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