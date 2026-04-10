<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Handler;

use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\MealJournal\Application\Command\EditMealEntryCommand;
use Kcalculator\MealJournal\Application\Exception\MealEntryNotFound;
use Kcalculator\MealJournal\Application\Port\MealEntryLookup;
use Kcalculator\MealJournal\Application\Port\MealEntryRepository;
use Kcalculator\MealJournal\Application\Service\NutritionCalculator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class EditMealEntryHandler
{
    public function __construct(
        private readonly MealEntryLookup $mealEntryLookup,
        private readonly NutritionCalculator $nutritionCalculator,
        private readonly MealEntryRepository $mealEntryRepository,
    ) {
    }

    public function __invoke(EditMealEntryCommand $command): void
    {
        $entry = $this->mealEntryLookup->findOwnedById($command->getEntryId(), $command->getUserId());

        if ($entry === null) {
            throw MealEntryNotFound::withId($command->getEntryId());
        }

        $nutrition = $this->nutritionCalculator->calculate(
            $this->extractProduct($entry),
            $command->getGrammage(),
        );

        $this->mealEntryRepository->update(
            $entry,
            $command->getMealType(),
            $command->getGrammage(),
            $nutrition,
        );
    }

    private function extractProduct(\Kcalculator\Domain\Entry\Entity\Entry $entry): Product
    {
        $product = $entry->getFood()?->first();

        if (!$product instanceof Product) {
            throw new \RuntimeException('Meal entry does not contain a food product.');
        }

        return $product;
    }
}
