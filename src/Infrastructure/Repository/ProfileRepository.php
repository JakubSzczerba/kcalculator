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

class ProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Preference::class);
    }

    public function userProfile(int $id)
    {     
        $qb = $this->createQueryBuilder('p');

        $qb->select('u.username', 'u.email', 'p.gender', 'p.weight', 'p.height', 'p.age', 'p.activity', 'p.caloric_requirement', 'p.intentions', 'p.kcal_day', 'p.id', 'p.proteinPerDay', 'p.fatPerDay', 'p.carboPerDay' )
            ->innerJoin('Kcalculator\Domain\User\Entity\User', 'u', Join::WITH, 'u = p.users')
            ->where('u.id = :users')
            ->setParameter('users', $id);
            
        return $qb->getQuery()->getArrayResult();
    }
}