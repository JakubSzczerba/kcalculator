<?php

declare(strict_types=1);

namespace Kcalculator\NutritionCatalog\Application\Import;

final readonly class FoodCatalogImportRecord
{
    public function __construct(
        public string $name,
        public float $energy,
        public float $protein,
        public float $fat,
        public float $carbohydrates,
    ) {
    }
}
