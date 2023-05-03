<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Services\Preference;

use App\Dictionary\Preference\BasalMetabolicRateDictionary;

/* BMR calculation */
class BasalMetabolicRateAlgorithm
{
    private const BURN = -300;

    private const KEEP = 0;

    private const GAIN = 300;

    /* Mifflin's formula */
    public function calculate(string $gender, float $weight, float $height, int $age, string $activity, string $intentions): array
    {
        $result = 0;
        $kcal_day = 0;
        $protein = 1;
        $fat = 1;
        $carbohydrates = 1;
        switch ($gender) {
            case BasalMetabolicRateDictionary::MALE:
                $result = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
                break;
            case BasalMetabolicRateDictionary::FEMALE:
                $result = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
                break;
        }

        switch ($activity) {
            case BasalMetabolicRateDictionary::LOW_ACTIVITY:
                $result = $result * 1.45;
                break;
            case BasalMetabolicRateDictionary::MEDIUM_ACTIVITY:
                $result = $result * 1.75;
                break;
            case BasalMetabolicRateDictionary::HIGH_ACTIVITY:
                $result = $result * 2.0;
                break;
        }
        $caloric_requirement = (int)$result;

        switch ($intentions) {
            case BasalMetabolicRateDictionary::BURN_FAT:
                $kcal_day = ($caloric_requirement + self::BURN);
                $protein = 2 * $weight;
                $fat = ($kcal_day * 0.25) / 9;
                $carbohydrates = ($kcal_day - ($protein * 4) - ($fat * 9)) / 4;
                break;
            case BasalMetabolicRateDictionary::MAINTENANCE:
                $kcal_day = ($caloric_requirement + self::KEEP);
                $protein = 1.60 * $weight;
                $fat = ($kcal_day * 0.25) / 9;
                $carbohydrates = ($kcal_day - ($protein * 4) - ($fat * 9)) / 4;
                break;
            case BasalMetabolicRateDictionary::BUILD_MUSCLES:
                $kcal_day = ($caloric_requirement + self::GAIN);
                $protein = 1.85 * $weight;
                $fat = ($kcal_day * 0.25) / 9;
                $carbohydrates = ($kcal_day - ($protein * 4) - ($fat * 9)) / 4;
                break;
        }

        $array['kcal'] = $kcal_day;
        $array['protein'] = (int)$protein;
        $array['fat'] = (int)$fat;
        $array['carbohydrates'] = (int)$carbohydrates;

        return $array;
    }
}