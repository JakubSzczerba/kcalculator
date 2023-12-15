<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Command\Preferention;

use Kcalculator\DTO\PreferentionDTO;
use Kcalculator\Entity\User;

class SetPreferentionCommand
{
    private User $user;

    private PreferentionDTO $preferentionDTO;

    public function __construct(User $user, PreferentionDTO $preferentionDTO)
    {
        $this->user = $user;
        $this->preferentionDTO = $preferentionDTO;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPreferentionDTO(): PreferentionDTO
    {
        return $this->preferentionDTO;
    }
}