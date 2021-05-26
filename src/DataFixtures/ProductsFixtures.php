<?php
namespace App\ProductsFixtures;
use App\Entity\Products;
use App\Command\CsvImportCommand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ProductsFixtures extends Fixture 

{

    public function load(ObjectManager $manager): void
    {
        $this->loadProducts($manager);
       
    }


    public function loadProducts(ObjectManager $manager): void
    {

        foreach ($this->getProductsData() as [$product, $energy, $protein, $fat, $carbo]) {
            $food = new Products();
            $food->setProduct($product);
            $food->setEnergy($energy);
            $food->setProtein($protein);
            $food->setFat($fat);
            $food->setCarbo($carbo);

            $manager->persist($food);
            
        }

        $manager->flush();

    }

    public function getProductsData(): array
    {

        $ProductsData = new CsvImportCommand();


        return $ProductsData;
    }




































}