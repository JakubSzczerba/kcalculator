<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Port;

use Kcalculator\MealJournal\Application\View\DailyNutritionSummary;

interface DailyNutritionSummaryReader
{
    public function getForDay(\DateTimeInterface $dateTime, int $userId): DailyNutritionSummary;
}
