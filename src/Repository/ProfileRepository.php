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
        parent::__construct($registry, User::class);
    }

    /**
     * @return profile[]
     */

    public function userProfile(int $id)
    {
        
        $qb = $this->createQueryBuilder('u');

        $qb->select('u.username', 'u.email')
            //->innerJoin('App\Entity\UserPreferention', 'p', Join::WITH, 'p = u.preferentions')
            ->where('u.id = :id')
            ->setParameter('id', $id);
            
        
            

            
        dump($qb->getQuery()->getResult());
        //return $qb->getQuery()->getArrayResult();

        

    }














}