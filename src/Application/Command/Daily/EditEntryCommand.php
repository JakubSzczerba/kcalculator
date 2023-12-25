<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\Command\Daily;

use Kcalculator\Application\DTO\EntryDTO;
use Kcalculator\Domain\Entry\Entity\Entry;

class EditEntryCommand
{
    private EntryDTO $entryDTO;

    private Entry $entry;


    public function __construct(EntryDTO $entryDTO, Entry $entry)
    {
        $this->entryDTO = $entryDTO;
        $this->entry = $entry;
    }

    public function getEntryDTO(): EntryDTO
    {
        return $this->entryDTO;
    }

    public function getEntry(): Entry
    {
        return $this->entry;
    }
}