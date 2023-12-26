<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Kcalculator\Domain\Preference\Entity\Preference;
use Kcalculator\Domain\Preference\UserPreferenceRepositoryInterface;

class PreferenceRepository extends ServiceEntityRepository implements UserPreferenceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Preference::class);
    }

    public function showKcalPerDay(int $id): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p.kcal_day, p.proteinPerDay, p.fatPerDay, p.carboPerDay, p.intentions')
            ->innerJoin('Kcalculator\Domain\User\Entity\User', 'u', Join::WITH, 'u = p.user')
            ->where('p.kcal_day IS NOT NULL')
            ->andWhere('u.id like :users')
            ->setParameter('users', $id);
   
        return $qb->getQuery()->getArrayResult();

    }
}