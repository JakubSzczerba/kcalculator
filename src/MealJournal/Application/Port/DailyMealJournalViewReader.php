<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Port;

interface DailyMealJournalViewReader
{
    /**
     * @return array<string, mixed>
     */
    public function getForDay(\DateTimeInterface $dateTime, int $userId): array;
}
