<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\Preference\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Kcalculator\Domain\User\Entity\User;
use Kcalculator\Domain\Preference\Entity\UserPreference;

class PreferenceFactory implements PreferenceFactoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function new(User $user, string $gender, float $weight, float $height, int $age, string $activity, int $caloric_requirement, string $intentions, int $kcal_day, int $protein, int $fat, int $carbohydrates): UserPreference
    {
        $preferention = new UserPreference();
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

    public function edit(UserPreference $userPreferention, string $gender, float $weight, float $height, int $age, string $activity, int $caloric_requirement, string $intentions, int $kcal_day, int $protein, int $fat, int $carbohydrates): UserPreference
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
        UserPreference $preferention,
        string         $gender,
        float          $weight,
        float          $height,
        int            $age,
        string         $activity,
        int            $caloric_requirement,
        string         $intentions,
        int            $kcal_day,
        int            $protein,
        int            $fat,
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