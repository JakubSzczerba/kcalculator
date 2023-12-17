<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kcalculator\Domain\WeightHistory\Entity\UserWeightHistory;

class UserWeightHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserWeightHistory::class);
    }

    public function showHistory(int $id)
    {      
        $qb = $this->createQueryBuilder('w');

        $qb->select('w.userWeight')
            ->where('w.user = :user')     
            ->setParameter('user', $id);
    
        return $qb->getQuery()->getResult();
    }

    public function monthHistory(int $id)
    {       
        $qb = $this->createQueryBuilder('w');

        $qb->select('w.datetime')
            ->where('w.user = :user')     
            ->setParameter('user', $id);
 
        return $qb->getQuery()->getResult();
    }
}