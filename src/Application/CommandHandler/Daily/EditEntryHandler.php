<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\CommandHandler\Daily;

use Kcalculator\Application\Command\Daily\EditEntryCommand;
use Kcalculator\Application\Prodiver\Entry\EntryDataProvider;
use Kcalculator\Domain\Entry\Factory\EntryFactory;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EditEntryHandler
{
    private EntryDataProvider $entryDataProvider;

    private EntryFactory $entryFactory;

    public function __construct(EntryDataProvider $entryDataProvider, EntryFactory $entryFactory)
    {
        $this->entryDataProvider = $entryDataProvider;
        $this->entryFactory = $entryFactory;
    }

    public function __invoke(EditEntryCommand $command): void
    {
        $entryDTO = $command->getEntryDTO();

        /* Get data counting via grammage */
        $grammarValues = $this->entryDataProvider->getGrammageValues($entryDTO->getGrammage(), $entryDTO->getProduct());

        /* Edit entry */
        $this->entryFactory->edit(
            $command->getUserEntry(),
            $entryDTO->getMeal(),
            $grammarValues['grammage'],
            $grammarValues['energy'],
            $grammarValues['protein'],
            $grammarValues['fat'],
            $grammarValues['carbohydrates']);
    }
}