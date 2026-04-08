<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Unit\NutritionCatalog\Infrastructure\Import;

use Kcalculator\NutritionCatalog\Infrastructure\Import\CsvFoodCatalogImportSource;
use PHPUnit\Framework\TestCase;

final class CsvFoodCatalogImportSourceTest extends TestCase
{
    public function test_it_reads_records_from_csv(): void
    {
        $source = new CsvFoodCatalogImportSource(__DIR__ . '/../../../../Fixtures/nutrition_catalog.csv');

        $records = iterator_to_array($source->records());

        self::assertCount(2, $records);
        self::assertSame('Apple', $records[0]->name);
        self::assertSame(52.0, $records[0]->energy);
        self::assertSame(10.0, $records[1]->protein);
    }
}
