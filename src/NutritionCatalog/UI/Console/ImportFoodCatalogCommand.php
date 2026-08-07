<?php

declare(strict_types=1);

namespace Kcalculator\NutritionCatalog\UI\Console;

use Kcalculator\NutritionCatalog\Application\Import\FoodCatalogImporter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'food-catalog:import',
    description: 'Imports food catalog records from the configured source.',
    aliases: ['csv:import'],
)]
final class ImportFoodCatalogCommand extends Command
{
    public function __construct(private readonly FoodCatalogImporter $importer)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Importing food catalog');

        $imported = $this->importer->import();

        $io->success(sprintf('Imported %d food catalog records.', $imported));

        return self::SUCCESS;
    }
}
