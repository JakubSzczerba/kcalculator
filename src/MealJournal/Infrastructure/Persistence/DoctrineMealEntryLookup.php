<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Infrastructure\Persistence;

use Kcalculator\Domain\Entry\Entity\Entry;
use Kcalculator\Infrastructure\Repository\EntryRepository;
use Kcalculator\MealJournal\Application\Port\MealEntryLookup;

final class DoctrineMealEntryLookup implements MealEntryLookup
{
    public function __construct(private readonly EntryRepository $entryRepository)
    {
    }

    public function findOwnedById(int $entryId, int $userId): ?Entry
    {
        return $this->entryRepository->findOwnedEntry($entryId, $userId);
    }
}
