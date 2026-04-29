<?php

declare(strict_types=1);

namespace Kcalculator\Measurements\Application\View;

final class WeightHistoryChartFactory
{
    /** @param list<array<string, mixed>> $rows */
    public function create(array $rows): WeightHistoryChart
    {
        $labels = [];
        $weights = [];

        foreach ($rows as $row) {
            $labels[] = $this->normalizeDate($row['recordedAt'] ?? null);
            $weights[] = $this->normalizeWeight($row['weight'] ?? null);
        }

        return new WeightHistoryChart($labels, $weights);
    }

    private function normalizeDate(mixed $value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('d.m');
        }

        if (is_string($value) && $value !== '') {
            return (new \DateTimeImmutable($value))->format('d.m');
        }

        return '';
    }

    private function normalizeWeight(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        return (float) $value;
    }
}
