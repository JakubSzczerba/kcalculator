<?php
namespace App\Command;
use Symfony\Component\Console\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;



class CsvImportCommand extends ContainerAwareCommand

{
    // change these options about the file to read
    private $csvParsingOptions = array(
        'finder_in' => 'src/Data/',
        'finder_name' => 'MOCK_DATA.csv',
        'ignoreFirstLine' => true
    );

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // use the parseCSV() function
        $csv = $this->parseCSV();
    }

    /**
     * Parse a csv file
     * 
     * @return array
     */
    private function parseCSV()
    {
        $ignoreFirstLine = $this->csvParsingOptions['ignoreFirstLine'];

        $finder = new Finder();
        $finder->files()
            ->in($this->csvParsingOptions['finder_in'])
            ->name($this->csvParsingOptions['finder_name'])
        ;
        foreach ($finder as $file) { $csv = $file; }

        $rows = array();
        if (($handle = fopen($csv->getRealPath(), "r")) !== FALSE) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
                $i++;
                if ($ignoreFirstLine && $i == 1) { continue; }
                $rows[] = $data;
            }
            fclose($handle);
        }

        return $rows;
    }
}