<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Handler;

use Kcalculator\MealJournal\Application\Command\DeleteMealEntryCommand;
use Kcalculator\MealJournal\Application\Exception\MealEntryNotFound;
use Kcalculator\MealJournal\Application\Port\MealEntryLookup;
use Kcalculator\MealJournal\Application\Port\MealEntryRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class DeleteMealEntryHandler
{
    public function __construct(
        private readonly MealEntryLookup $mealEntryLookup,
        private readonly MealEntryRepository $mealEntryRepository,
    ) {
    }

    public function __invoke(DeleteMealEntryCommand $command): void
    {
        $entry = $this->mealEntryLookup->findOwnedById($command->getEntryId(), $command->getUserId());

        if ($entry === null) {
            throw MealEntryNotFound::withId($command->getEntryId());
        }

        $this->mealEntryRepository->remove($entry);
    }
}
