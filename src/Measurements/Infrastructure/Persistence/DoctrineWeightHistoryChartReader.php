<?php

declare(strict_types=1);

namespace Kcalculator\Measurements\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use Kcalculator\Domain\WeightHistory\Entity\WeightHistory;
use Kcalculator\Measurements\Application\Port\WeightHistoryChartReader;
use Kcalculator\Measurements\Application\View\WeightHistoryChart;
use Kcalculator\Measurements\Application\View\WeightHistoryChartFactory;

final class DoctrineWeightHistoryChartReader implements WeightHistoryChartReader
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WeightHistoryChartFactory $chartFactory,
    ) {
    }

    public function getForUser(int $userId): WeightHistoryChart
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->from(WeightHistory::class, 'w')
            ->select('w.datetime AS recordedAt')
            ->addSelect('w.userWeight AS weight')
            ->where('w.user = :user')
            ->orderBy('w.datetime', 'ASC')
            ->setParameter('user', $userId);

        /** @var list<array<string, mixed>> $rows */
        $rows = $qb->getQuery()->getArrayResult();

        return $this->chartFactory->create($rows);
    }
}
