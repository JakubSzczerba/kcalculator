<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\View;

final class DailyNutritionSummaryFactory
{
    /** @param array<string, mixed> $row */
    public function create(array $row): DailyNutritionSummary
    {
        return new DailyNutritionSummary(
            $this->normalize($row['energy'] ?? null),
            $this->normalize($row['protein'] ?? null),
            $this->normalize($row['fat'] ?? null),
            $this->normalize($row['carbohydrates'] ?? null),
        );
    }

    private function normalize(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        return (float) $value;
    }
}
