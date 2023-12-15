<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\DTO;

class PreferentionDTO
{
    private string $gender;
    private float $weight;
    private float $height;
    private int $age;
    private string $activity;
    private string $intentions;

    public function __construct(string $gender, float $weight, string $height, string $age, string $activity, string $intentions)
    {
        $this->gender = $gender;
        $this->weight = $weight;
        $this->height = (float)$height;
        $this->age = (int)$age;
        $this->activity = $activity;
        $this->intentions = $intentions;
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