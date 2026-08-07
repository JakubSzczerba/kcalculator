<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Exception;

use RuntimeException;

final class MealEntryNotFound extends RuntimeException
{
    public static function withId(int $entryId): self
    {
        return new self(sprintf('Meal entry with id %d was not found.', $entryId));
    }
}
