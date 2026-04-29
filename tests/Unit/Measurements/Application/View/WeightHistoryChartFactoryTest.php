<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Unit\Measurements\Application\View;

use Kcalculator\Measurements\Application\View\WeightHistoryChartFactory;
use PHPUnit\Framework\TestCase;

final class WeightHistoryChartFactoryTest extends TestCase
{
    public function testItBuildsChartPayloadFromWeightHistoryRows(): void
    {
        $factory = new WeightHistoryChartFactory();

        $chart = $factory->create([
            [
                'recordedAt' => new \DateTimeImmutable('2026-04-28 08:15:00'),
                'weight' => '81.4',
            ],
            [
                'recordedAt' => '2026-04-29 08:15:00',
                'weight' => 80.9,
            ],
        ]);

        self::assertSame(['28.04', '29.04'], $chart->getLabels());
        self::assertSame([81.4, 80.9], $chart->getWeights());
    }

    public function testItReturnsEmptyChartForMissingRows(): void
    {
        $factory = new WeightHistoryChartFactory();

        $chart = $factory->create([]);

        self::assertSame([], $chart->getLabels());
        self::assertSame([], $chart->getWeights());
    }
}
