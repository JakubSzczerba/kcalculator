<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\Command\Daily;

use Kcalculator\Application\DTO\EntryDTO;
use Kcalculator\Domain\User\Entity\User;

class AddEntryCommand
{
    private EntryDTO $entryDTO;

    private User $user;


    public function __construct(EntryDTO $entryDTO, User $user)
    {
        $this->entryDTO = $entryDTO;
        $this->user = $user;
    }

    public function getEntryDTO(): EntryDTO
    {
        return $this->entryDTO;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}