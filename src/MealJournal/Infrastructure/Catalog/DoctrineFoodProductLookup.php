<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Infrastructure\Catalog;

use Doctrine\ORM\EntityManagerInterface;
use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\MealJournal\Application\Port\FoodProductLookup;

final class DoctrineFoodProductLookup implements FoodProductLookup
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function findById(int $productId): ?Product
    {
        $product = $this->entityManager->find(Product::class, $productId);

        return $product instanceof Product ? $product : null;
    }
}
