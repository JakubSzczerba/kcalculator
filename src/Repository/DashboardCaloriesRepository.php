<?php

namespace App\Repository;

use App\Entity\UserPreferention;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;
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

        $qb->select('p.kcal_day, p.proteinPerDay, p.fatPerDay, p.carboPerDay')
            ->innerJoin('App\Entity\User', 'u', Join::WITH, 'u = p.users')
            ->where('p.kcal_day IS NOT NULL')
            ->andWhere('u.id like :users')
            ->setParameter('users', $id);
            //>andWhere('p.kcal_day IS NULL')
            //->setParameter('p.kcal_day', 0);

            

            
        //dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getArrayResult();

    }


}