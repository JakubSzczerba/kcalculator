<?php

namespace App\Repository;

use App\Entity\UsersEntries;
use App\Entity\Products;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;
use Doctrine\ORM\Query\Expr\Join;

class EntriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersEntries::class);
    }

    /**
     * @return entries[]
     */

    public function displayEntry(\DateTime $datetime, int $id)
    {
        
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')
            ->leftJoin('e.food', 'p')
            ->addSelect('p')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')     
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'));
            


            
        //dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getArrayResult();

    }

    /**
     * @return entried_kcal[]
     */

    public function SummEntriedKcal(\DateTime $datetime, int $id)
    {
        
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')
            ->leftJoin('e.food', 'p')
            ->addSelect('p')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')     
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'));
            


            
        //dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getArrayResult();

    }


}