<?php

declare(strict_types=1);

namespace Kcalculator\NutritionCatalog\Application\Import;

use Doctrine\ORM\EntityManagerInterface;
use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\NutritionCatalog\Application\Port\FoodCatalogImportSource;

final class FoodCatalogImporter
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FoodCatalogImportSource $source,
    ) {
    }

    public function import(): int
    {
        $imported = 0;

        foreach ($this->source->records() as $record) {
            $product = new Product();
            $product->setProduct($record->name);
            $product->setEnergy($record->energy);
            $product->setProtein($record->protein);
            $product->setFat($record->fat);
            $product->setCarbo($record->carbohydrates);

            $this->entityManager->persist($product);
            ++$imported;
        }

        $this->entityManager->flush();

        return $imported;
    }
}
