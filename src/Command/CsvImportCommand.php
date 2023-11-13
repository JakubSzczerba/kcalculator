<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Command;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use League\Csv\Reader;

class CsvImportCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure()
    {
        $this->setName('csv:import');
        $this->setDescription('Imports a mock CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Attempting to import the feed');

        $reader = Reader::createFromPath('%kernel.root_dir%/../src/Data/MOCK_DATA.csv');
        $results = $reader->fetchAssoc();

        foreach ($results as $row) {
            $products = new Products();
            $products->setProduct($row['product']);
            $products->setEnergy($row['energy']);
            $products->setProtein($row['protein']);
            $products->setFat($row['fat']);
            $products->setCarbo($row['carbo']);

            $this->em->persist($products);
        }
        $this->em->flush();
        $io->success('Everything went well');

        return 1;
    }
}