<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Query\Daily;

class DailyEntriesQuery
{
    private \DateTime $dateTime;

    private int $userId;

    public function __construct(\DateTime $dateTime, int $userId)
    {
        $this->dateTime = $dateTime;
        $this->userId = $userId;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}