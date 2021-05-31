<?php
namespace App\DataFixtures;
use App\Entity\UsersEntries;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UsersEntriesFixtures extends Fixture 

{
    public function load(ObjectManager $manager): void
    {
        $this->loadDateTime($manager);
       
    }

    public function loadDateTime(ObjectManager $manager): void
    {

        foreach ($this->getDateTime() as [$datetime, $meal_type, $grammage]) {
            $datetime = new UsersEntries();
            $datetime->setDateTime(new \DateTime());
            $datetime->setMealType($meal_type);
            $datetime->setGrammage($grammage);
         

            $manager->persist($datetime);
            
        }

        $manager->flush();

    }

        public function getDateTime(): array
    {
        return [
            // $DateTime = [$data];
            ['	May 25, 2021', 'meal_1', '0.5'],
            ['	May 25, 2021', 'meal_2', '1'],
            ['	May 25, 2021', 'meal_3', '2.4'],
        ];

    }






















}