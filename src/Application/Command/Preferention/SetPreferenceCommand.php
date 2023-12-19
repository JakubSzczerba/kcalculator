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

class SetPreferenceCommand
{
    private User $user;

    private PreferenceDTO $preferenceDTO;

    public function __construct(User $user, PreferenceDTO $preferenceDTO)
    {
        $this->user = $user;
        $this->preferenceDTO = $preferenceDTO;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPreferenceDTO(): PreferenceDTO
    {
        return $this->preferenceDTO;
    }
}