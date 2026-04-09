<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Unit\MealJournal\Application\Handler;

use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\MealJournal\Application\Command\AddMealEntryCommand;
use Kcalculator\MealJournal\Application\Exception\FoodProductNotFound;
use Kcalculator\MealJournal\Application\Handler\AddMealEntryHandler;
use Kcalculator\MealJournal\Application\Port\FoodProductLookup;
use Kcalculator\MealJournal\Application\Port\MealEntryRepository;
use Kcalculator\MealJournal\Application\Service\CalculatedNutrition;
use Kcalculator\MealJournal\Application\Service\NutritionCalculator;
use PHPUnit\Framework\TestCase;

final class AddMealEntryHandlerTest extends TestCase
{
    public function testItLoadsProductCalculatesNutritionAndPersistsEntry(): void
    {
        $product = new Product();
        $product->setEnergy(52.0);
        $product->setProtein(0.3);
        $product->setFat(0.2);
        $product->setCarbo(14.0);

        $foodProductLookup = $this->createMock(FoodProductLookup::class);
        $mealEntryRepository = $this->createMock(MealEntryRepository::class);

        $foodProductLookup
            ->expects(self::once())
            ->method('findById')
            ->with(10)
            ->willReturn($product);

        $mealEntryRepository
            ->expects(self::once())
            ->method('add')
            ->with(
                7,
                'Śniadanie',
                0.5,
                $product,
                self::callback(static function (CalculatedNutrition $nutrition): bool {
                    return $nutrition->getEnergy() === 26.0
                        && $nutrition->getProtein() === 0.15
                        && $nutrition->getFat() === 0.1
                        && $nutrition->getCarbohydrates() === 7.0;
                }),
            );

        $handler = new AddMealEntryHandler(
            $foodProductLookup,
            new NutritionCalculator(),
            $mealEntryRepository,
        );

        $handler(new AddMealEntryCommand(7, 10, 'Śniadanie', 0.5));
    }

    public function testItFailsWhenProductDoesNotExist(): void
    {
        $foodProductLookup = $this->createMock(FoodProductLookup::class);
        $mealEntryRepository = $this->createMock(MealEntryRepository::class);

        $foodProductLookup
            ->expects(self::once())
            ->method('findById')
            ->with(404)
            ->willReturn(null);

        $mealEntryRepository
            ->expects(self::never())
            ->method('add');

        $handler = new AddMealEntryHandler(
            $foodProductLookup,
            new NutritionCalculator(),
            $mealEntryRepository,
        );

        $this->expectException(FoodProductNotFound::class);

        $handler(new AddMealEntryCommand(7, 404, 'Śniadanie', 1.0));
    }
}
