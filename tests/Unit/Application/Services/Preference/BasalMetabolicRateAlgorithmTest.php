<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Unit\Application\Services\Preference;

use Kcalculator\Application\Services\Preference\BasalMetabolicRateAlgorithm;
use PHPUnit\Framework\TestCase;

final class BasalMetabolicRateAlgorithmTest extends TestCase
{
    public function testItCalculatesCuttingPlanForMaleUser(): void
    {
        $algorithm = new BasalMetabolicRateAlgorithm();

        $result = $algorithm->calculate('man', 80.0, 180.0, 30, 'activity1', 'intension1');

        self::assertSame([
            'caloric_requirement' => 2581,
            'kcal_per_day' => 2281,
            'protein' => 160,
            'fat' => 63,
            'carbohydrates' => 267,
        ], $result);
    }

    public function testItCalculatesMaintenancePlanForFemaleUser(): void
    {
        $algorithm = new BasalMetabolicRateAlgorithm();

        $result = $algorithm->calculate('woman', 62.0, 168.0, 29, 'activity2', 'intension2');

        self::assertSame([
            'caloric_requirement' => 2387,
            'kcal_per_day' => 2387,
            'protein' => 99,
            'fat' => 66,
            'carbohydrates' => 348,
        ], $result);
    }
}
