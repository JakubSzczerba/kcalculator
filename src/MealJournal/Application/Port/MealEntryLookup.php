<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Port;

use Kcalculator\Domain\Entry\Entity\Entry;

interface MealEntryLookup
{
    public function findOwnedById(int $entryId, int $userId): ?Entry;
}
