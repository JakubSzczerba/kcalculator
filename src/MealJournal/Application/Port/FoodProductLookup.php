<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Port;

use Kcalculator\Domain\Product\Entity\Product;

interface FoodProductLookup
{
    public function findById(int $productId): ?Product;
}
