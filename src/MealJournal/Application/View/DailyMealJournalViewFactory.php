<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\View;

final class DailyMealJournalViewFactory
{
    /** @var array<string, array{entries: string, total: string}> */
    private const SECTION_MAP = [
        'Przekąska' => ['entries' => 'snack', 'total' => 'snackcal'],
        'Śniadanie' => ['entries' => 'breakfast', 'total' => 'breakcal'],
        'Drugie śniadanie' => ['entries' => 'lunch', 'total' => 'lunchkcal'],
        'Obiad' => ['entries' => 'dinner', 'total' => 'dinnerkcal'],
        'Podwieczorek' => ['entries' => 'tea', 'total' => 'teakcal'],
        'Kolacja' => ['entries' => 'supper', 'total' => 'supperkcal'],
    ];

    /**
     * @param array<int, array<string, mixed>> $entries
     *
     * @return array<string, mixed>
     */
    public function create(array $entries, \DateTimeInterface $dateTime): array
    {
        $view = [
            'entry' => $entries,
            'snack' => [],
            'breakfast' => [],
            'lunch' => [],
            'dinner' => [],
            'tea' => [],
            'supper' => [],
            'snackcal' => 0.0,
            'breakcal' => 0.0,
            'lunchkcal' => 0.0,
            'dinnerkcal' => 0.0,
            'teakcal' => 0.0,
            'supperkcal' => 0.0,
            'dataTest' => $dateTime,
        ];

        foreach ($entries as $entry) {
            $mealType = $entry['meal_type'] ?? null;

            if (!is_string($mealType) || !isset(self::SECTION_MAP[$mealType])) {
                continue;
            }

            $section = self::SECTION_MAP[$mealType];
            $view[$section['entries']][] = $entry;
            $view[$section['total']] += (float) ($entry['energyXgram'] ?? 0.0);
        }

        return $view;
    }
}
