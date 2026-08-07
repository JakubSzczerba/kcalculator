<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Unit\MealJournal\Application\Service;

use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\MealJournal\Application\Service\NutritionCalculator;
use PHPUnit\Framework\TestCase;

final class NutritionCalculatorTest extends TestCase
{
    public function testItCalculatesNutritionForSelectedPortion(): void
    {
        $product = new Product();
        $product->setEnergy(52.0);
        $product->setProtein(0.3);
        $product->setFat(0.2);
        $product->setCarbo(14.0);

        $calculator = new NutritionCalculator();

        $nutrition = $calculator->calculate($product, 1.5);

        self::assertSame(78.0, $nutrition->getEnergy());
        self::assertSame(0.45, $nutrition->getProtein());
        self::assertSame(0.3, $nutrition->getFat());
        self::assertSame(21.0, $nutrition->getCarbohydrates());
    }
}
