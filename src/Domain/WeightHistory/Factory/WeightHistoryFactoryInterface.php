<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\WeightHistory\Factory;

use Kcalculator\Domain\User\Entity\User;
use Kcalculator\Domain\WeightHistory\Entity\WeightHistory;

interface WeightHistoryFactoryInterface
{
    public function new(User $user, float $weight): WeightHistory;
}