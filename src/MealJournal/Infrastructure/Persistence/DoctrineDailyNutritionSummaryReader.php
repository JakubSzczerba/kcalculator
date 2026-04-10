<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use Kcalculator\Domain\Entry\Entity\Entry;
use Kcalculator\MealJournal\Application\Port\DailyNutritionSummaryReader;
use Kcalculator\MealJournal\Application\View\DailyNutritionSummary;
use Kcalculator\MealJournal\Application\View\DailyNutritionSummaryFactory;

final class DoctrineDailyNutritionSummaryReader implements DailyNutritionSummaryReader
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DailyNutritionSummaryFactory $summaryFactory,
    ) {
    }

    public function getForDay(\DateTimeInterface $dateTime, int $userId): DailyNutritionSummary
    {
        $start = \DateTimeImmutable::createFromInterface($dateTime)->setTime(0, 0, 0);
        $end = $start->modify('+1 day');

        $qb = $this->entityManager->createQueryBuilder();
        $qb->from(Entry::class, 'e')
            ->select('COALESCE(SUM(e.energyXgram), 0) AS energy')
            ->addSelect('COALESCE(SUM(e.proteinXgram), 0) AS protein')
            ->addSelect('COALESCE(SUM(e.fatXgram), 0) AS fat')
            ->addSelect('COALESCE(SUM(e.carboXgram), 0) AS carbohydrates')
            ->where('e.user = :user')
            ->andWhere('e.datetime >= :start')
            ->andWhere('e.datetime < :end')
            ->setParameter('user', $userId)
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        /** @var array<string, mixed> $summary */
        $summary = $qb->getQuery()->getSingleResult();

        return $this->summaryFactory->create($summary);
    }
}
