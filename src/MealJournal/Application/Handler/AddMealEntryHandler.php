<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Handler;

use Kcalculator\MealJournal\Application\Command\AddMealEntryCommand;
use Kcalculator\MealJournal\Application\Exception\FoodProductNotFound;
use Kcalculator\MealJournal\Application\Port\FoodProductLookup;
use Kcalculator\MealJournal\Application\Port\MealEntryRepository;
use Kcalculator\MealJournal\Application\Service\NutritionCalculator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class AddMealEntryHandler
{
    public function __construct(
        private readonly FoodProductLookup $foodProductLookup,
        private readonly NutritionCalculator $nutritionCalculator,
        private readonly MealEntryRepository $mealEntryRepository,
    ) {
    }

    public function __invoke(AddMealEntryCommand $command): void
    {
        $product = $this->foodProductLookup->findById($command->getProductId());

        if ($product === null) {
            throw FoodProductNotFound::withId($command->getProductId());
        }

        $nutrition = $this->nutritionCalculator->calculate($product, $command->getGrammage());

        $this->mealEntryRepository->add(
            $command->getUserId(),
            $command->getMealType(),
            $command->getGrammage(),
            $product,
            $nutrition,
        );
    }
}
