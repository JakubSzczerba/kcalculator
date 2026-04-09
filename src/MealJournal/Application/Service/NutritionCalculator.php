<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Service;

use Kcalculator\Domain\Product\Entity\Product;

final class NutritionCalculator
{
    public function calculate(Product $product, float $grammage): CalculatedNutrition
    {
        return new CalculatedNutrition(
            round($product->getEnergy() * $grammage, 0),
            round($product->getProtein() * $grammage, 2),
            round($product->getFat() * $grammage, 2),
            round($product->getCarbo() * $grammage, 2),
        );
    }
}
