<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Unit\NutritionCatalog\Application\Import;

use Doctrine\ORM\EntityManagerInterface;
use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\NutritionCatalog\Application\Import\FoodCatalogImportRecord;
use Kcalculator\NutritionCatalog\Application\Import\FoodCatalogImporter;
use Kcalculator\NutritionCatalog\Application\Port\FoodCatalogImportSource;
use PHPUnit\Framework\TestCase;

final class FoodCatalogImporterTest extends TestCase
{
    public function test_it_persists_imported_products(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $source = $this->createMock(FoodCatalogImportSource::class);

        $source->expects(self::once())
            ->method('records')
            ->willReturn([
                new FoodCatalogImportRecord('Apple', 52.0, 0.3, 0.2, 14.0),
                new FoodCatalogImportRecord('Greek Yogurt', 59.0, 10.0, 0.4, 3.6),
            ]);

        $entityManager->expects(self::exactly(2))
            ->method('persist')
            ->with(self::callback(static function (mixed $product): bool {
                return $product instanceof Product && $product->getProduct() !== null;
            }));

        $entityManager->expects(self::once())
            ->method('flush');

        $importer = new FoodCatalogImporter($entityManager, $source);

        self::assertSame(2, $importer->import());
    }
}
