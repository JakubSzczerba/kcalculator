<?php
namespace App\Command;
use App\Entity\Products;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;



class CsvImportCommand extends Command

{
    /**
     * @var EntityManagerInterface
     */
    private $em;

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
   
   

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Attempting to import the feed');

        $products = new Products();
        $products->setProduct('Actimel, mleko jogurtowe');
        $products->setEnergy('88');
        $products->setProtein('3');
        $products->setFat('2');
        $products->setCarbo('16');

        $this->em->persist($products);
    
        $this->em->flush();

        $io->success('Everything went well');
    }

    






    private function parseCSV()
    {
        

    }
}