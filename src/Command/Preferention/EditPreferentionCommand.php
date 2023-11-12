<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Command\Preferention;

use App\Entity\User;
use App\Entity\UserPreferention;

class EditPreferentionCommand
{
    private UserPreferention $userPreferention;
    private User $user;
    private string $gender;
    private float $weight;
    private float $height;
    private int $age;
    private string $activity;
    private string $intentions;

    public function __construct(UserPreferention $userPreferention, User $user, string $gender, float $weight, float $height, int $age, string $activity, string $intentions)
    {
        $this->userPreferention = $userPreferention;
        $this->user = $user;
        $this->gender = $gender;
        $this->weight = $weight;
        $this->height = $height;
        $this->age = $age;
        $this->activity = $activity;
        $this->intentions = $intentions;
    }

    public function getUserPreferention(): UserPreferention
    {
        return $this->userPreferention;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getActivity(): string
    {
        return $this->activity;
    }

    public function getIntentions(): string
    {
        return $this->intentions;
    }

}