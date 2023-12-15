<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Dictionary\Preference;

class BasalMetabolicRateDictionary
{
    /* Gender */
    public const MALE = 'man';
    public const FEMALE = 'woman';

    /* Activity */
    public const LOW_ACTIVITY = 'activity1';
    public const MEDIUM_ACTIVITY = 'activity2';
    public const HIGH_ACTIVITY = 'activity3';

    /* Intentions */
    public const BURN_FAT = 'intension1';
    public const MAINTENANCE = 'intension2';
    public const BUILD_MUSCLES = 'intension3';

}