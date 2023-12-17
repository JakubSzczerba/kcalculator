<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\QueryHandler\Daily;

use Kcalculator\Application\Prodiver\Entry\MealsDataProvider;
use Kcalculator\Application\Query\Daily\DailyEntriesQuery;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DailyEntriesHandler
{
    private MealsDataProvider $mealsDataProvider;

    public function __construct(MealsDataProvider $mealsDataProvider)
    {
        $this->mealsDataProvider = $mealsDataProvider;
    }

    public function __invoke(DailyEntriesQuery $query): array
    {
        return $this->mealsDataProvider->getData($query->getDateTime(), $query->getUserId());
    }
}