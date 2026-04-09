<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Port;

use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\MealJournal\Application\Service\CalculatedNutrition;

interface MealEntryRepository
{
    public function add(
        int $userId,
        string $mealType,
        float $grammage,
        Product $product,
        CalculatedNutrition $nutrition,
    ): void;
}
