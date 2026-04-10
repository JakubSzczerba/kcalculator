<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use Kcalculator\Domain\Entry\Entity\Entry;
use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\Domain\User\Entity\User;
use Kcalculator\MealJournal\Application\Port\MealEntryRepository;
use Kcalculator\MealJournal\Application\Service\CalculatedNutrition;

final class DoctrineMealEntryRepository implements MealEntryRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function add(
        int $userId,
        string $mealType,
        float $grammage,
        Product $product,
        CalculatedNutrition $nutrition,
    ): void {
        $entry = new Entry();
        $entry->setUser($this->entityManager->getReference(User::class, $userId));
        $entry->setDateTime(new \DateTime());
        $entry->setMealType($mealType);
        $entry->setGrammage($grammage);
        $entry->setFood($product);
        $entry->setEnergyXgram($nutrition->getEnergy());
        $entry->setProteinXgram($nutrition->getProtein());
        $entry->setFatXgram($nutrition->getFat());
        $entry->setCarboXgram($nutrition->getCarbohydrates());

        $this->entityManager->persist($entry);
        $this->entityManager->flush();
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

        $this->entityManager->flush();
    }

    public function remove(Entry $entry): void
    {
        $this->entityManager->remove($entry);
        $this->entityManager->flush();
    }
}
