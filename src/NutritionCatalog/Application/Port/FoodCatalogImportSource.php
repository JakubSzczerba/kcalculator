<?php

declare(strict_types=1);

namespace Kcalculator\NutritionCatalog\Application\Port;

use Kcalculator\NutritionCatalog\Application\Import\FoodCatalogImportRecord;

interface FoodCatalogImportSource
{
    /**
     * @return iterable<FoodCatalogImportRecord>
     */
    public function records(): iterable;
}
