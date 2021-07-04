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

    public function displayEntry(int $id)
    {
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')
            ->innerJoin('App\Entity\User', 'u', Join::WITH, 'u = e.user')
            ->where('u.id = :user') 
            //->andWhere('e.datetime = datatime')     
            ->setParameter('user', $id);
            //->setParameter('datatime', $datetime);


            
        dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getArrayResult();

    }


}