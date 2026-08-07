<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Unit\MealJournal\Application\Handler;

use Kcalculator\Domain\Entry\Entity\Entry;
use Kcalculator\MealJournal\Application\Command\DeleteMealEntryCommand;
use Kcalculator\MealJournal\Application\Exception\MealEntryNotFound;
use Kcalculator\MealJournal\Application\Handler\DeleteMealEntryHandler;
use Kcalculator\MealJournal\Application\Port\MealEntryLookup;
use Kcalculator\MealJournal\Application\Port\MealEntryRepository;
use PHPUnit\Framework\TestCase;

final class DeleteMealEntryHandlerTest extends TestCase
{
    public function testItLoadsOwnedEntryAndRemovesIt(): void
    {
        $entry = new Entry();

        $mealEntryLookup = $this->createMock(MealEntryLookup::class);
        $mealEntryRepository = $this->createMock(MealEntryRepository::class);

        $mealEntryLookup
            ->expects(self::once())
            ->method('findOwnedById')
            ->with(15, 7)
            ->willReturn($entry);

        $mealEntryRepository
            ->expects(self::once())
            ->method('remove')
            ->with($entry);

        $handler = new DeleteMealEntryHandler($mealEntryLookup, $mealEntryRepository);

        $handler(new DeleteMealEntryCommand(7, 15));
    }

    public function testItFailsWhenEntryDoesNotExist(): void
    {
        $mealEntryLookup = $this->createMock(MealEntryLookup::class);
        $mealEntryRepository = $this->createMock(MealEntryRepository::class);

        $mealEntryLookup
            ->expects(self::once())
            ->method('findOwnedById')
            ->with(404, 7)
            ->willReturn(null);

        $mealEntryRepository
            ->expects(self::never())
            ->method('remove');

        $handler = new DeleteMealEntryHandler($mealEntryLookup, $mealEntryRepository);

        $this->expectException(MealEntryNotFound::class);

        $handler(new DeleteMealEntryCommand(7, 404));
    }
}
