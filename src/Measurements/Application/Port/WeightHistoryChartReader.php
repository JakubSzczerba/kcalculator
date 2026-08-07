<?php

declare(strict_types=1);

namespace Kcalculator\Measurements\Application\Port;

use Kcalculator\Measurements\Application\View\WeightHistoryChart;

interface WeightHistoryChartReader
{
    public function getForUser(int $userId): WeightHistoryChart;
}
