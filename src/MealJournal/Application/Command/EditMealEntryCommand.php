<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Command;

final readonly class EditMealEntryCommand
{
    public function __construct(
        private int $userId,
        private int $entryId,
        private string $mealType,
        private float $grammage,
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getEntryId(): int
    {
        return $this->entryId;
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
