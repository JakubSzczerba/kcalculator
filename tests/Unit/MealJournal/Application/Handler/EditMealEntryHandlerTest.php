<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Unit\MealJournal\Application\Handler;

use Kcalculator\Domain\Entry\Entity\Entry;
use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\MealJournal\Application\Command\EditMealEntryCommand;
use Kcalculator\MealJournal\Application\Exception\MealEntryNotFound;
use Kcalculator\MealJournal\Application\Handler\EditMealEntryHandler;
use Kcalculator\MealJournal\Application\Port\MealEntryLookup;
use Kcalculator\MealJournal\Application\Port\MealEntryRepository;
use Kcalculator\MealJournal\Application\Service\CalculatedNutrition;
use Kcalculator\MealJournal\Application\Service\NutritionCalculator;
use PHPUnit\Framework\TestCase;

final class EditMealEntryHandlerTest extends TestCase
{
    public function testItLoadsOwnedEntryRecalculatesNutritionAndPersistsChanges(): void
    {
        $product = new Product();
        $product->setEnergy(52.0);
        $product->setProtein(0.3);
        $product->setFat(0.2);
        $product->setCarbo(14.0);

        $entry = new Entry();
        $entry->setFood($product);

        $mealEntryLookup = $this->createMock(MealEntryLookup::class);
        $mealEntryRepository = $this->createMock(MealEntryRepository::class);

        $mealEntryLookup
            ->expects(self::once())
            ->method('findOwnedById')
            ->with(15, 7)
            ->willReturn($entry);

        $mealEntryRepository
            ->expects(self::once())
            ->method('update')
            ->with(
                $entry,
                'Obiad',
                0.5,
                self::callback(static function (CalculatedNutrition $nutrition): bool {
                    return $nutrition->getEnergy() === 26.0
                        && $nutrition->getProtein() === 0.15
                        && $nutrition->getFat() === 0.1
                        && $nutrition->getCarbohydrates() === 7.0;
                }),
            );

        $handler = new EditMealEntryHandler(
            $mealEntryLookup,
            new NutritionCalculator(),
            $mealEntryRepository,
        );

        $handler(new EditMealEntryCommand(7, 15, 'Obiad', 0.5));
    }

    public function testItFailsWhenEntryDoesNotExist(): void
    {
        $mealEntryLookup = $this->createMock(MealEntryLookup::class);
        $mealEntryRepository = $this->createMock(MealEntryRepository::class);

        $mealEntryLookup
            ->expects(self::once())
            ->method('findOwnedById')
            ->with(404, 7)
            ->willReturn(null);

        $mealEntryRepository
            ->expects(self::never())
            ->method('update');

        $handler = new EditMealEntryHandler(
            $mealEntryLookup,
            new NutritionCalculator(),
            $mealEntryRepository,
        );

        $this->expectException(MealEntryNotFound::class);

        $handler(new EditMealEntryCommand(7, 404, 'Obiad', 1.0));
    }
}
