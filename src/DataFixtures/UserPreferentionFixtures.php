<?php
namespace App\DataFixtures;
use App\Entity\UserPreferention;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserPreferentionFixtures extends Fixture 

{

    public function load(ObjectManager $manager): void
    {
        $this->loadPreferention($manager);
       
    }

  
    public function loadPreferention(ObjectManager $manager): void
    {

        foreach ($this->getPreferentionData() as [$cel, $waga, $kcal]) {
            $userpref = new UserPreferention();
            $userpref->setCel($cel);
            $userpref->setWaga($waga);
            $userpref->setKcal($kcal);

            $manager->persist($userpref);
            // $this->addReference($username, $user);
        }

        $manager->flush();

    }


    public function getPreferentionData(): array
    {
        return [
            // $preferentionData = [$cel, $waga, $kcal];
            ['redukcja', '98.7', '2230'],
            ['utrzymanie', '75.4', '2500'],
            ['przybranie', '65.5', '3000'],
        ];
    }


    











}