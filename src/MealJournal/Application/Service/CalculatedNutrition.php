<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Service;

final readonly class CalculatedNutrition
{
    public function __construct(
        private float $energy,
        private float $protein,
        private float $fat,
        private float $carbohydrates,
    ) {
    }

    public function getEnergy(): float
    {
        return $this->energy;
    }

    public function getProtein(): float
    {
        return $this->protein;
    }

    public function getFat(): float
    {
        return $this->fat;
    }

    public function getCarbohydrates(): float
    {
        return $this->carbohydrates;
    }
}
