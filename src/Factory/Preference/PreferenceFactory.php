<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Factory\Preference;

use App\Entity\User;
use App\Entity\UserPreferention;
use Doctrine\ORM\EntityManagerInterface;

class PreferenceFactory
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function new(User $user, string $gender, float $weight, float $height, int $age, string $activity, int $caloric_requirement, string $intentions, int $kcal_day, int $protein, int $fat, int $carbohydrates): UserPreferention
    {
        $preferention = new UserPreferention();
        $preferention->setUsers($user);

        $this->setValues(
            $preferention,
            $gender,
            $weight,
            $height,
            $age,
            $activity,
            $caloric_requirement,
            $intentions,
            $kcal_day,
            $protein,
            $fat,
            $carbohydrates
        );

        $this->em->persist($preferention);
        $this->em->flush();

        return $preferention;
    }

    public function edit(UserPreferention $userPreferention, string $gender, float $weight, float $height, int $age, string $activity, int $caloric_requirement, string $intentions, int $kcal_day, int $protein, int $fat, int $carbohydrates): UserPreferention
    {
        $this->setValues(
            $userPreferention,
            $gender,
            $weight,
            $height,
            $age,
            $activity,
            $caloric_requirement,
            $intentions,
            $kcal_day,
            $protein,
            $fat,
            $carbohydrates
        );

        $this->em->flush();

        return $userPreferention;
    }

    private function setValues(
        UserPreferention $preferention,
        string $gender,
        float $weight,
        float $height,
        int $age,
        string $activity,
        int $caloric_requirement,
        string $intentions,
        int $kcal_day,
        int $protein,
        int $fat,
        int $carbohydrates
    ): void {
        $preferention->setGender($gender);
        $preferention->setWeight($weight);
        $preferention->setHeight($height);
        $preferention->setAge($age);
        $preferention->setActivity($activity);
        $preferention->setKcal($caloric_requirement);
        $preferention->setIntentions($intentions);
        $preferention->setKcalDay($kcal_day);
        $preferention->setProteinPerDay($protein);
        $preferention->setFatPerDay($fat);
        $preferention->setCarboPerDay($carbohydrates);
    }
}