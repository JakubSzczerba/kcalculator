<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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