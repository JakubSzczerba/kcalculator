<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\Command\Daily;

use Kcalculator\Application\DTO\EntryDTO;
use Kcalculator\Domain\Entry\Entity\UserEntry;

class EditEntryCommand
{
    private EntryDTO $entryDTO;

    private UserEntry $userEntry;


    public function __construct(EntryDTO $entryDTO, UserEntry $userEntry)
    {
        $this->entryDTO = $entryDTO;
        $this->userEntry = $userEntry;
    }

    public function getEntryDTO(): EntryDTO
    {
        return $this->entryDTO;
    }

    public function getUserEntry(): UserEntry
    {
        return $this->userEntry;
    }
}