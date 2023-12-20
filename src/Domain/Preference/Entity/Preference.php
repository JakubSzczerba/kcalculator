<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\Preference\Entity;

use Kcalculator\Domain\User\Entity\User;

class Preference
{
    private $id;

    private User $user;

    private string $gender;

    private float $weight;

    private float $height;

    private int $age;

    private string $activity;

    public int $caloric_requirement;

    private string $intentions;

    public int $kcal_day;

    public int $proteinPerDay;

    public int $fatPerDay;

    public int $carboPerDay;

    public function getId()
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    public function getIntentions(): ?string
    {
        return $this->intentions;
    }

    public function setIntentions($intentions): void
    {
        $this->intentions = $intentions;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight($weight): void
    {
        $this->weight = $weight;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): void
    {
        $this->height = $height;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity($activity): void
    {
        $this->activity = $activity;
    }

    public function getKcal(): ?int
    {
        return $this->caloric_requirement;
    }

    public function setKcal($caloric_requirement): void
    {
        $this->caloric_requirement = $caloric_requirement;
    }

    public function getKcalDay(): ?int
    {
        return $this->kcal_day;
    }

    public function setKcalDay($kcal_day): void
    {
        $this->kcal_day = $kcal_day;
    }

    public function getProteinPerDay(): ?int
    {
        return $this->proteinPerDay;
    }

    public function setProteinPerDay($proteinPerDay): void
    {
        $this->proteinPerDay = $proteinPerDay;
    }

    public function getFatPerDay(): ?int
    {
        return $this->fatPerDay;
    }

    public function setFatPerDay($fatPerDay): void
    {
        $this->fatPerDay = $fatPerDay;
    }

    public function getCarboPerDay(): ?int
    {
        return $this->carboPerDay;
    }

    public function setCarboPerDay($carboPerDay): void
    {
        $this->carboPerDay = $carboPerDay;
    }
}