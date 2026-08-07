<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kcalculator\Domain\Entry\Entity\Entry;

class EntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entry::class);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findEntriesForDay(\DateTimeInterface $datetime, int $id): array
    {        
        $start = \DateTimeImmutable::createFromInterface($datetime)->setTime(0, 0, 0);
        $end = $start->modify('+1 day');
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')
            ->leftJoin('e.food', 'p')
            ->addSelect('p')
            ->where('e.user = :user') 
            ->andWhere('e.datetime >= :start')
            ->andWhere('e.datetime < :end')
            ->setParameter('user', $id)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('e.id', 'ASC');
            
        return $qb->getQuery()->getArrayResult();
    }

    public function findOwnedEntry(int $id, int $userId): ?Entry
    {
        $qb = $this->createQueryBuilder('e');

        $qb->select('e', 'p')
            ->leftJoin('e.food', 'p')
            ->where('e.id = :id')
            ->andWhere('e.user = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $userId);

        return $qb->getQuery()->getOneOrNullResult();
    }
} 
