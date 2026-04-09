<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\QueryHandler\Daily;

use Kcalculator\Application\Query\Daily\DailyEntriesQuery;
use Kcalculator\MealJournal\Application\Port\DailyMealJournalViewReader;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DailyEntriesHandler
{
    private DailyMealJournalViewReader $dailyMealJournalViewReader;

    public function __construct(DailyMealJournalViewReader $dailyMealJournalViewReader)
    {
        $this->dailyMealJournalViewReader = $dailyMealJournalViewReader;
    }

    public function __invoke(DailyEntriesQuery $query): array
    {
        return $this->dailyMealJournalViewReader->getForDay($query->getDateTime(), $query->getUserId());
    }
}
