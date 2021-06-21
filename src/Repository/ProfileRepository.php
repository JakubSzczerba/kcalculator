<?php

namespace App\Repository;

use App\Entity\UserPreferention;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;
use Doctrine\ORM\Query\Expr\Join;

class ProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPreferention::class);
    }

    /**
     * @return profile[]
     */

    public function userProfile(int $id)
    {
        
        $qb = $this->createQueryBuilder('p');

        $qb->select('p.kcal_day', 'u.username', 'u.email')
            ->innerJoin('App\Entity\User', 'u', Join::WITH, 'u = p.users')            
            ->where('u.id = :users')
            ->setParameter('users', $id);
            
        
            

            
        dump($qb->getQuery()->getResult());
        //return $qb->getQuery()->getArrayResult();

        

    }














}