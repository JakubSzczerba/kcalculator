<?php

namespace App\Repository;

use App\Entity\UserPreferention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;

class PreferentionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPreferention::class);
        
    }

    /**
     * @return preferention[]
     */

    public function addPreferention()
    {
        
        $qb = $this->createQueryBuilder('p');


        




    }



}





















