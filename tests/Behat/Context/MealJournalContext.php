<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\MealJournal\Application\Command\AddMealEntryCommand;
use Kcalculator\MealJournal\Application\Exception\FoodProductNotFound;
use Kcalculator\MealJournal\Application\Handler\AddMealEntryHandler;
use Kcalculator\MealJournal\Application\Port\FoodProductLookup;
use Kcalculator\MealJournal\Application\Port\MealEntryRepository;
use Kcalculator\MealJournal\Application\Service\CalculatedNutrition;
use Kcalculator\MealJournal\Application\Service\NutritionCalculator;
use RuntimeException;

final class MealJournalContext implements Context
{
    private InMemoryFoodProductLookup $foodProductLookup;

    private InMemoryMealEntryRepository $mealEntryRepository;

    private ?\Throwable $caughtException = null;

    public function __construct()
    {
        $this->foodProductLookup = new InMemoryFoodProductLookup();
        $this->mealEntryRepository = new InMemoryMealEntryRepository();
    }

    /**
     * @Given the meal journal contains a product :productId named :name with :energy kcal, :protein protein, :fat fat and :carbohydrates carbohydrates
     */
    public function theMealJournalContainsAProductNamedWithNutrition(
        int $productId,
        string $name,
        float $energy,
        float $protein,
        float $fat,
        float $carbohydrates,
    ): void {
        $product = new Product();
        $product->setProduct($name);
        $product->setEnergy($energy);
        $product->setProtein($protein);
        $product->setFat($fat);
        $product->setCarbo($carbohydrates);

        $this->foodProductLookup->addProduct($productId, $product);
    }

    /**
     * @When user :userId adds the product :productId as :mealType with portion multiplier :grammage
     */
    public function userAddsTheProductAsWithPortionMultiplier(
        int $userId,
        int $productId,
        string $mealType,
        float $grammage,
    ): void {
        $handler = new AddMealEntryHandler(
            $this->foodProductLookup,
            new NutritionCalculator(),
            $this->mealEntryRepository,
        );

        $this->caughtException = null;

        try {
            $handler(new AddMealEntryCommand($userId, $productId, $mealType, $grammage));
        } catch (\Throwable $exception) {
            $this->caughtException = $exception;
        }
    }

    /**
     * @Then the meal entry should be stored with :energy kcal, :protein protein, :fat fat and :carbohydrates carbohydrates
     */
    public function theMealEntryShouldBeStoredWithNutrition(
        float $energy,
        float $protein,
        float $fat,
        float $carbohydrates,
    ): void {
        if ($this->caughtException !== null) {
            throw new RuntimeException($this->caughtException->getMessage(), 0, $this->caughtException);
        }

        $entry = $this->mealEntryRepository->lastAddedEntry();

        if ($entry === null) {
            throw new RuntimeException('No meal entry was stored.');
        }

        if ($entry['energy'] !== $energy
            || $entry['protein'] !== $protein
            || $entry['fat'] !== $fat
            || $entry['carbohydrates'] !== $carbohydrates) {
            throw new RuntimeException('Stored meal entry has unexpected nutrition values.');
        }
    }

    /**
     * @Then adding the meal entry should fail because the product does not exist
     */
    public function addingTheMealEntryShouldFailBecauseTheProductDoesNotExist(): void
    {
        if (!$this->caughtException instanceof FoodProductNotFound) {
            throw new RuntimeException('Expected FoodProductNotFound exception.');
        }
    }
}

final class InMemoryFoodProductLookup implements FoodProductLookup
{
    /** @var array<int, Product> */
    private array $products = [];

    public function addProduct(int $productId, Product $product): void
    {
        $this->products[$productId] = $product;
    }

    public function findById(int $productId): ?Product
    {
        return $this->products[$productId] ?? null;
    }
}

final class InMemoryMealEntryRepository implements MealEntryRepository
{
    /** @var list<array<string, float|int|string>> */
    private array $entries = [];

    public function add(
        int $userId,
        string $mealType,
        float $grammage,
        Product $product,
        CalculatedNutrition $nutrition,
    ): void {
        $this->entries[] = [
            'userId' => $userId,
            'mealType' => $mealType,
            'grammage' => $grammage,
            'energy' => $nutrition->getEnergy(),
            'protein' => $nutrition->getProtein(),
            'fat' => $nutrition->getFat(),
            'carbohydrates' => $nutrition->getCarbohydrates(),
        ];
    }

    /** @return array<string, float|int|string>|null */
    public function lastAddedEntry(): ?array
    {
        return $this->entries === [] ? null : $this->entries[array_key_last($this->entries)];
    }
}
