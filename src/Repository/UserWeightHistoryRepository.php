<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Repository;

use Kcalculator\Entity\UserWeightHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserWeightHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserWeightHistory::class);
    }

    /**
     * @return weighHistory[]
     */
    public function showHistory(int $id)
    {      
        $qb = $this->createQueryBuilder('w');

        $qb->select('w.userWeight')
            ->where('w.user = :user')     
            ->setParameter('user', $id);
    
        return $qb->getQuery()->getResult();
    }

    /**
     * @return MonthHistory[]
     */
    public function monthHistory(int $id)
    {       
        $qb = $this->createQueryBuilder('w');

        $qb->select('w.datetime')
            ->where('w.user = :user')     
            ->setParameter('user', $id);
 
        return $qb->getQuery()->getResult();
    }
}