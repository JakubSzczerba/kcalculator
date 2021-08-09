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

        $qb->select('u.username', 'u.email', 'p.gender', 'p.weight', 'p.height', 'p.age', 'p.activity', 'p.caloric_requirement', 'p.intentions', 'p.kcal_day', 'p.id')
            ->innerJoin('App\Entity\User', 'u', Join::WITH, 'u = p.users')            
            ->where('u.id = :users')
            ->setParameter('users', $id);
            
        
            

            
        //dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getArrayResult();

        

    }














}