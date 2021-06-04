<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    public function findProducts()
    {

        $qb = $this->createQueryBuilder('p');

        $qb->select('p.product', 'p.energy', 'p.protein', 'p.fat', 'p.carbo');

        dump($qb->getQuery()->getResult());


        return $qb->getQuery()->getResult();
    }


































    
}