<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Command;

final readonly class DeleteMealEntryCommand
{
    public function __construct(
        private int $userId,
        private int $entryId,
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
}
