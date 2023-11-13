<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Command\Preferention;

use App\DTO\PreferentionDTO;
use App\Entity\User;
use App\Entity\UserPreferention;

class EditPreferentionCommand
{
    private UserPreferention $userPreferention;

    private PreferentionDTO $preferentionDTO;

    public function __construct(UserPreferention $userPreferention, PreferentionDTO $preferentionDTO)
    {
        $this->userPreferention = $userPreferention;
        $this->preferentionDTO = $preferentionDTO;
    }

    public function getUserPreferention(): UserPreferention
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