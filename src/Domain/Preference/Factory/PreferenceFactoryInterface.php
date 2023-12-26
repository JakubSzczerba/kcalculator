<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\Preference\Factory;

use Kcalculator\Domain\Preference\Entity\Preference;
use Kcalculator\Domain\User\Entity\User;

interface PreferenceFactoryInterface
{
    public function new(User $user, string $gender, float $weight, float $height, int $age, string $activity, int $caloric_requirement, string $intentions, int $kcal_day, int $protein, int $fat, int $carbohydrates): Preference;

    public function edit(Preference $userPreferention, string $gender, float $weight, float $height, int $age, string $activity, int $caloric_requirement, string $intentions, int $kcal_day, int $protein, int $fat, int $carbohydrates): Preference;

}