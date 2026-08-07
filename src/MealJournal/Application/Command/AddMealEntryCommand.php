<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Command;

final readonly class AddMealEntryCommand
{
    public function __construct(
        private int $userId,
        private int $productId,
        private string $mealType,
        private float $grammage,
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getMealType(): string
    {
        return $this->mealType;
    }

    public function getGrammage(): float
    {
        return $this->grammage;
    }
}
