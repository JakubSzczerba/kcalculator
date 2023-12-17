<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\Command\Preferention;

use Kcalculator\Application\DTO\PreferentionDTO;
use Kcalculator\Domain\User\Entity\User;
use Kcalculator\Domain\Preference\Entity\UserPreference;

class EditPreferentionCommand
{
    private UserPreference $userPreferention;

    private PreferentionDTO $preferentionDTO;

    public function __construct(UserPreference $userPreferention, PreferentionDTO $preferentionDTO)
    {
        $this->userPreferention = $userPreferention;
        $this->preferentionDTO = $preferentionDTO;
    }

    public function getUserPreferention(): UserPreference
    {
        return $this->userPreferention;
    }

    public function getPreferentionDTO(): PreferentionDTO
    {
        return $this->preferentionDTO;
    }

    public function getUser(): User
    {
        return $this->userPreferention->getUsers();
    }
}