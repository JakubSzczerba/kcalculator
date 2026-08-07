<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Kcalculator\Domain\Entry\Entity\Entry;
use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\MealJournal\Application\Command\AddMealEntryCommand;
use Kcalculator\MealJournal\Application\Command\DeleteMealEntryCommand;
use Kcalculator\MealJournal\Application\Command\EditMealEntryCommand;
use Kcalculator\MealJournal\Application\Exception\FoodProductNotFound;
use Kcalculator\MealJournal\Application\Exception\MealEntryNotFound;
use Kcalculator\MealJournal\Application\Handler\AddMealEntryHandler;
use Kcalculator\MealJournal\Application\Handler\DeleteMealEntryHandler;
use Kcalculator\MealJournal\Application\Handler\EditMealEntryHandler;
use Kcalculator\MealJournal\Application\Port\FoodProductLookup;
use Kcalculator\MealJournal\Application\Port\MealEntryLookup;
use Kcalculator\MealJournal\Application\Port\MealEntryRepository;
use Kcalculator\MealJournal\Application\Service\CalculatedNutrition;
use Kcalculator\MealJournal\Application\Service\NutritionCalculator;
use RuntimeException;

final class MealJournalContext implements Context
{
    private InMemoryFoodProductLookup $foodProductLookup;

    private InMemoryMealEntryStore $mealEntryStore;

    private ?\Throwable $caughtException = null;

    public function __construct()
    {
        $this->foodProductLookup = new InMemoryFoodProductLookup();
        $this->mealEntryStore = new InMemoryMealEntryStore();
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
            $this->mealEntryStore,
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

        $entry = $this->mealEntryStore->lastAddedEntry();

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

    /**
     * @Given the meal journal contains an entry :entryId for user :userId with product :productId as :mealType with portion multiplier :grammage
     */
    public function theMealJournalContainsAnEntryForUserWithProductAsWithPortionMultiplier(
        int $entryId,
        int $userId,
        int $productId,
        string $mealType,
        float $grammage,
    ): void {
        $product = $this->foodProductLookup->findById($productId);

        if ($product === null) {
            throw new RuntimeException(sprintf('Product %d must exist before seeding an entry.', $productId));
        }

        $this->mealEntryStore->seedEntry(
            $entryId,
            $userId,
            $mealType,
            $grammage,
            $product,
            (new NutritionCalculator())->calculate($product, $grammage),
        );
    }

    /**
     * @When user :userId edits the entry :entryId to :mealType with portion multiplier :grammage
     */
    public function userEditsTheEntryToWithPortionMultiplier(
        int $userId,
        int $entryId,
        string $mealType,
        float $grammage,
    ): void {
        $handler = new EditMealEntryHandler(
            $this->mealEntryStore,
            new NutritionCalculator(),
            $this->mealEntryStore,
        );

        $this->caughtException = null;

        try {
            $handler(new EditMealEntryCommand($userId, $entryId, $mealType, $grammage));
        } catch (\Throwable $exception) {
            $this->caughtException = $exception;
        }
    }

    /**
     * @Then the meal entry :entryId should be stored as :mealType with :energy kcal, :protein protein, :fat fat and :carbohydrates carbohydrates
     */
    public function theMealEntryShouldBeStoredAsWithNutrition(
        int $entryId,
        string $mealType,
        float $energy,
        float $protein,
        float $fat,
        float $carbohydrates,
    ): void {
        if ($this->caughtException !== null) {
            throw new RuntimeException($this->caughtException->getMessage(), 0, $this->caughtException);
        }

        $entry = $this->mealEntryStore->getEntry($entryId);

        if ($entry === null) {
            throw new RuntimeException(sprintf('Meal entry %d does not exist.', $entryId));
        }

        if ($entry['mealType'] !== $mealType
            || $entry['energy'] !== $energy
            || $entry['protein'] !== $protein
            || $entry['fat'] !== $fat
            || $entry['carbohydrates'] !== $carbohydrates) {
            throw new RuntimeException('Stored meal entry has unexpected data after edit.');
        }
    }

    /**
     * @When user :userId deletes the entry :entryId
     */
    public function userDeletesTheEntry(int $userId, int $entryId): void
    {
        $handler = new DeleteMealEntryHandler($this->mealEntryStore, $this->mealEntryStore);

        $this->caughtException = null;

        try {
            $handler(new DeleteMealEntryCommand($userId, $entryId));
        } catch (\Throwable $exception) {
            $this->caughtException = $exception;
        }
    }

    /**
     * @Then the meal entry :entryId should no longer exist
     */
    public function theMealEntryShouldNoLongerExist(int $entryId): void
    {
        if ($this->caughtException !== null) {
            throw new RuntimeException($this->caughtException->getMessage(), 0, $this->caughtException);
        }

        if ($this->mealEntryStore->getEntry($entryId) !== null) {
            throw new RuntimeException(sprintf('Meal entry %d still exists.', $entryId));
        }
    }

    /**
     * @Then editing or deleting the meal entry should fail because the entry does not exist
     */
    public function editingOrDeletingTheMealEntryShouldFailBecauseTheEntryDoesNotExist(): void
    {
        if (!$this->caughtException instanceof MealEntryNotFound) {
            throw new RuntimeException('Expected MealEntryNotFound exception.');
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

final class InMemoryMealEntryStore implements MealEntryRepository, MealEntryLookup
{
    /** @var array<int, Entry> */
    private array $entries = [];

    /** @var array<int, int> */
    private array $entryOwners = [];

    private int $nextId = 1;

    public function add(
        int $userId,
        string $mealType,
        float $grammage,
        Product $product,
        CalculatedNutrition $nutrition,
    ): void {
        $entry = new Entry();
        $entry->setMealType($mealType);
        $entry->setGrammage($grammage);
        $entry->setFood($product);
        $entry->setEnergyXgram($nutrition->getEnergy());
        $entry->setProteinXgram($nutrition->getProtein());
        $entry->setFatXgram($nutrition->getFat());
        $entry->setCarboXgram($nutrition->getCarbohydrates());

        $entryId = $this->nextId++;
        $this->entries[$entryId] = $entry;
        $this->entryOwners[$entryId] = $userId;
    }

    public function update(
        Entry $entry,
        string $mealType,
        float $grammage,
        CalculatedNutrition $nutrition,
    ): void {
        $entry->setMealType($mealType);
        $entry->setGrammage($grammage);
        $entry->setEnergyXgram($nutrition->getEnergy());
        $entry->setProteinXgram($nutrition->getProtein());
        $entry->setFatXgram($nutrition->getFat());
        $entry->setCarboXgram($nutrition->getCarbohydrates());
    }

    public function remove(Entry $entry): void
    {
        foreach ($this->entries as $entryId => $storedEntry) {
            if ($storedEntry === $entry) {
                unset($this->entries[$entryId], $this->entryOwners[$entryId]);

                return;
            }
        }
    }

    public function findOwnedById(int $entryId, int $userId): ?Entry
    {
        if (($this->entryOwners[$entryId] ?? null) !== $userId) {
            return null;
        }

        return $this->entries[$entryId] ?? null;
    }

    public function seedEntry(
        int $entryId,
        int $userId,
        string $mealType,
        float $grammage,
        Product $product,
        CalculatedNutrition $nutrition,
    ): void {
        $entry = new Entry();
        $entry->setMealType($mealType);
        $entry->setGrammage($grammage);
        $entry->setFood($product);
        $entry->setEnergyXgram($nutrition->getEnergy());
        $entry->setProteinXgram($nutrition->getProtein());
        $entry->setFatXgram($nutrition->getFat());
        $entry->setCarboXgram($nutrition->getCarbohydrates());

        $this->entries[$entryId] = $entry;
        $this->entryOwners[$entryId] = $userId;
        $this->nextId = max($this->nextId, $entryId + 1);
    }

    /** @return array<string, float|int|string>|null */
    public function lastAddedEntry(): ?array
    {
        if ($this->entries === []) {
            return null;
        }

        $entryId = array_key_last($this->entries);
        $entry = $this->entries[$entryId];

        return $this->normalizeEntry($entryId, $entry);
    }

    /** @return array<string, float|int|string>|null */
    public function getEntry(int $entryId): ?array
    {
        $entry = $this->entries[$entryId] ?? null;

        if (!$entry instanceof Entry) {
            return null;
        }

        return $this->normalizeEntry($entryId, $entry);
    }

    /** @return array<string, float|int|string> */
    private function normalizeEntry(int $entryId, Entry $entry): array
    {
        return [
            'entryId' => $entryId,
            'userId' => $this->entryOwners[$entryId],
            'mealType' => $entry->getMealType(),
            'grammage' => $entry->getGrammage(),
            'energy' => $entry->getEnergyXgram(),
            'protein' => $entry->getProteinXgram(),
            'fat' => $entry->getFatXgram(),
            'carbohydrates' => $entry->getCarboXgram(),
        ];
    }
}
