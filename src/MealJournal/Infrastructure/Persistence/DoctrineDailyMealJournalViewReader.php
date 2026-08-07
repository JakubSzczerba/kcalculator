<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Infrastructure\Persistence;

use Kcalculator\Infrastructure\Repository\EntryRepository;
use Kcalculator\MealJournal\Application\Port\DailyMealJournalViewReader;
use Kcalculator\MealJournal\Application\View\DailyMealJournalViewFactory;

final class DoctrineDailyMealJournalViewReader implements DailyMealJournalViewReader
{
    public function __construct(
        private readonly EntryRepository $entryRepository,
        private readonly DailyMealJournalViewFactory $viewFactory,
    ) {
    }

    public function getForDay(\DateTimeInterface $dateTime, int $userId): array
    {
        return $this->viewFactory->create(
            $this->entryRepository->findEntriesForDay($dateTime, $userId),
            $dateTime,
        );
    }
}
