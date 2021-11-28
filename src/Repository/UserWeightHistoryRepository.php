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