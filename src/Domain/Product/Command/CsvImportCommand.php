<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\Product\Command;

use Doctrine\ORM\EntityManagerInterface;
use Kcalculator\Domain\Product\Entity\Product;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $this->setDescription('Imports a products');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Attempting to import the products');

        $reader = Reader::createFromPath('%kernel.root_dir%/../src/Application/Data/Products.csv');
        $reader->setHeaderOffset(0);

        foreach ($reader as $row) {
            $products = new Product();
            $products->setProduct($row['product']);
            $products->setEnergy((float)$row['energy']);
            $products->setProtein((float)$row['protein']);
            $products->setFat((float)$row['fat']);
            $products->setCarbo((float)$row['carbo']);

            $this->em->persist($products);
        }
        $this->em->flush();
        $io->success('Products has been imported');

        return self::SUCCESS;
    }
}