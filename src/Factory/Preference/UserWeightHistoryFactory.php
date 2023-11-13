<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Factory\Preference;

use App\Entity\User;
use App\Entity\UserWeightHistory;
use Doctrine\ORM\EntityManagerInterface;

class UserWeightHistoryFactory
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function new(User $user, float $weight): UserWeightHistory
    {
        $userWeightHistory = new UserWeightHistory();
        $userWeightHistory->setUsers($user);
        $userWeightHistory->setUserWeight($weight);
        $userWeightHistory->setDateTime(new \DateTime());

        $this->em->persist($userWeightHistory);
        $this->em->flush();

        return $userWeightHistory;
    }

}