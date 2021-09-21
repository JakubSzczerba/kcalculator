<?php

namespace App\Repository;

use App\Entity\UserWeightHistory;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;
use Doctrine\ORM\Query\Expr\Join;

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
        
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')
            ->leftJoin('e.food', 'p')
            ->addSelect('p')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')     
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'))
            ->groupBy('e.meal_type', 'p.product');
            


            
        //dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getArrayResult();

    }
}