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

    /**
     * @return products[]
     */
    public function findProducts(string $product): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p')
            ->where('p.product LIKE :product')        
            ->setParameter('product', $product.'%')
            ->orderBy('p.product', 'ASC');

        return $qb->getQuery()->getArrayResult();

    }
}