<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Unit\MealJournal\Application\View;

use Kcalculator\MealJournal\Application\View\DailyNutritionSummaryFactory;
use PHPUnit\Framework\TestCase;

final class DailyNutritionSummaryFactoryTest extends TestCase
{
    public function testItBuildsSummaryFromAggregateRow(): void
    {
        $factory = new DailyNutritionSummaryFactory();

        $summary = $factory->create([
            'energy' => '450',
            'protein' => '32.5',
            'fat' => 14.2,
            'carbohydrates' => '51.3',
        ]);

        self::assertSame(450.0, $summary->getEnergy());
        self::assertSame(32.5, $summary->getProtein());
        self::assertSame(14.2, $summary->getFat());
        self::assertSame(51.3, $summary->getCarbohydrates());
    }

    public function testItNormalizesMissingAggregateValuesToZero(): void
    {
        $factory = new DailyNutritionSummaryFactory();

        $summary = $factory->create([
            'energy' => null,
            'protein' => '',
        ]);

        self::assertSame(0.0, $summary->getEnergy());
        self::assertSame(0.0, $summary->getProtein());
        self::assertSame(0.0, $summary->getFat());
        self::assertSame(0.0, $summary->getCarbohydrates());
    }
}
