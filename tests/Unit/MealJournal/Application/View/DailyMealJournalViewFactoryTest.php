<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Unit\MealJournal\Application\View;

use Kcalculator\MealJournal\Application\View\DailyMealJournalViewFactory;
use PHPUnit\Framework\TestCase;

final class DailyMealJournalViewFactoryTest extends TestCase
{
    public function testItBuildsLegacyDailyViewPayloadFromEntries(): void
    {
        $factory = new DailyMealJournalViewFactory();
        $date = new \DateTimeImmutable('2026-04-09');

        $view = $factory->create([
            [
                'id' => 1,
                'meal_type' => 'Przekąska',
                'energyXgram' => 120.0,
                'food' => [['product' => 'Banana']],
            ],
            [
                'id' => 2,
                'meal_type' => 'Śniadanie',
                'energyXgram' => 250.0,
                'food' => [['product' => 'Eggs']],
            ],
            [
                'id' => 3,
                'meal_type' => 'Śniadanie',
                'energyXgram' => 100.0,
                'food' => [['product' => 'Bread']],
            ],
        ], $date);

        self::assertCount(3, $view['entry']);
        self::assertCount(1, $view['snack']);
        self::assertCount(2, $view['breakfast']);
        self::assertSame(120.0, $view['snackcal']);
        self::assertSame(350.0, $view['breakcal']);
        self::assertSame($date, $view['dataTest']);
    }

    public function testItReturnsEmptySectionsWhenDayHasNoEntries(): void
    {
        $factory = new DailyMealJournalViewFactory();
        $date = new \DateTimeImmutable('2026-04-09');

        $view = $factory->create([], $date);

        self::assertSame([], $view['entry']);
        self::assertSame([], $view['snack']);
        self::assertSame([], $view['breakfast']);
        self::assertSame(0.0, $view['snackcal']);
        self::assertSame(0.0, $view['breakcal']);
        self::assertSame($date, $view['dataTest']);
    }
}
