<?php
namespace App\DataFixtures;
use App\Entity\DataTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class DataTimeFixtures extends Fixture 

{
    public function load(ObjectManager $manager): void
    {
        $this->loadDataTime($manager);
       
    }

    public function loadDataTime(ObjectManager $manager): void
    {

        foreach ($this->getDataTime() as [$data]) {
            $dataTime = new DataTime();
            $dataTime->setData(new \DateTime());
         

            $manager->persist($dataTime);
            
        }

        $manager->flush();

    }

        public function getDataTime(): array
    {
        return [
            // $DataTime = [$data];
            ['	May 25, 2021'],
            ['	May 26, 2021'],
            ['	May 27, 2021'],
        ];

    }






















}