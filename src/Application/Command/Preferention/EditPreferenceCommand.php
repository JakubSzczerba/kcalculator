<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\Command\Preferention;

use Kcalculator\Application\DTO\PreferenceDTO;
use Kcalculator\Domain\User\Entity\User;
use Kcalculator\Domain\Preference\Entity\Preference;

class EditPreferenceCommand
{
    private Preference $preference;

    private PreferenceDTO $preferenceDTO;

    public function __construct(Preference $preference, PreferenceDTO $preferenceDTO)
    {
        $this->preference = $preference;
        $this->preferenceDTO = $preferenceDTO;
    }

    public function getPreference(): Preference
    {
        return $this->preference;
    }

    public function getPreferenceDTO(): PreferenceDTO
    {
        return $this->preferenceDTO;
    }

    public function getUser(): User
    {
        return $this->preference->getUser();
    }
}