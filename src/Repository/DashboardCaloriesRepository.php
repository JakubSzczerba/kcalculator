<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Repository;

use Kcalculator\Entity\UserPreferention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

class DashboardCaloriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPreferention::class);
    }

    /**
     * @return calories[]
     */
    public function showKcalPerDay(int $id)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p.kcal_day, p.proteinPerDay, p.fatPerDay, p.carboPerDay, p.intentions')
            ->innerJoin('Kcalculator\Entity\User', 'u', Join::WITH, 'u = p.users')
            ->where('p.kcal_day IS NOT NULL')
            ->andWhere('u.id like :users')
            ->setParameter('users', $id);
   
        return $qb->getQuery()->getArrayResult();

    }
}