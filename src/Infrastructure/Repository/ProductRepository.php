<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\Domain\Product\ProductRepositoryInterface;

class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

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