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
use Kcalculator\Domain\Preference\Entity\Preference;

class PreferenceFactory implements PreferenceFactoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function new(
        User $user,
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
    ): Preference {
        $preference = new Preference();
        $preference->setUser($user);

        $this->setValues(
            $preference,
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

        $this->em->persist($preference);
        $this->em->flush();

        return $preference;
    }

    public function edit(
        Preference $preference,
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
    ): Preference {
        $this->setValues(
            $preference,
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

        return $preference;
    }

    private function setValues(
        Preference $preference,
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
        $preference->setGender($gender);
        $preference->setWeight($weight);
        $preference->setHeight($height);
        $preference->setAge($age);
        $preference->setActivity($activity);
        $preference->setKcal($caloric_requirement);
        $preference->setIntentions($intentions);
        $preference->setKcalDay($kcal_day);
        $preference->setProteinPerDay($protein);
        $preference->setFatPerDay($fat);
        $preference->setCarboPerDay($carbohydrates);
    }
}