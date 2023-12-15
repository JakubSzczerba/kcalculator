<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Command\Daily;

use Kcalculator\DTO\EntryDTO;
use Kcalculator\Entity\UsersEntries;

class EditEntryCommand
{
    private EntryDTO $entryDTO;

    private UsersEntries $userEntry;


    public function __construct(EntryDTO $entryDTO, UsersEntries $userEntry)
    {
        $this->entryDTO = $entryDTO;
        $this->userEntry = $userEntry;
    }

    public function getEntryDTO(): EntryDTO
    {
        return $this->entryDTO;
    }

    public function getUserEntry(): UsersEntries
    {
        return $this->userEntry;
    }
}