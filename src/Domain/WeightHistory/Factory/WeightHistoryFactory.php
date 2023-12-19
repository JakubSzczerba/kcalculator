<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\WeightHistory\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Kcalculator\Domain\User\Entity\User;
use Kcalculator\Domain\WeightHistory\Entity\WeightHistory;

class WeightHistoryFactory implements WeightHistoryFactoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function new(User $user, float $weight): WeightHistory
    {
        $weightHistory = new WeightHistory();
        $weightHistory->setUsers($user);
        $weightHistory->setUserWeight($weight);
        $weightHistory->setDateTime(new \DateTime());

        $this->em->persist($weightHistory);
        $this->em->flush();

        return $weightHistory;
    }

}