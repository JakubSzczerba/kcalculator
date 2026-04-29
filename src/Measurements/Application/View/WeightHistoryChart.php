<?php

declare(strict_types=1);

namespace Kcalculator\Measurements\Application\View;

final readonly class WeightHistoryChart
{
    /**
     * @param list<string> $labels
     * @param list<float> $weights
     */
    public function __construct(
        private array $labels,
        private array $weights,
    ) {
    }

    /** @return list<string> */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /** @return list<float> */
    public function getWeights(): array
    {
        return $this->weights;
    }
}
