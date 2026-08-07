<?php

declare(strict_types=1);

namespace Kcalculator\NutritionCatalog\Infrastructure\Import;

use Kcalculator\NutritionCatalog\Application\Import\FoodCatalogImportRecord;
use Kcalculator\NutritionCatalog\Application\Port\FoodCatalogImportSource;
use League\Csv\Reader;

final class CsvFoodCatalogImportSource implements FoodCatalogImportSource
{
    public function __construct(private readonly string $csvPath)
    {
    }

    public function records(): iterable
    {
        $reader = Reader::createFromPath($this->csvPath);
        $reader->setHeaderOffset(0);

        foreach ($reader->getRecords() as $row) {
            $name = trim((string) ($row['product'] ?? ''));

            if ($name === '') {
                continue;
            }

            yield new FoodCatalogImportRecord(
                $name,
                (float) ($row['energy'] ?? 0),
                (float) ($row['protein'] ?? 0),
                (float) ($row['fat'] ?? 0),
                (float) ($row['carbo'] ?? 0),
            );
        }
    }
}
